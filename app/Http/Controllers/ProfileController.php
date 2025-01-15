<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserProfile;
use App\Models\Payment;
use App\Models\Chat;
use App\Models\Card;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Show the user profile page
    public function index()
{
    $user = Auth::user();
    $profile = UserProfile::firstOrCreate(
        ['user_id' => $user->id],
        [
            'picture' => null,
            'phone' => null,
            'name' => null,
            'email' => null,
            'auto_buy_feature' => false,
            'auto_sell_toggle' => false,
        ]
    );
    $payments = Payment::where('user_id', $user->id)->get();
    $chats = Chat::where('user_id', $user->id)->orWhere('to_user_id', $user->id)->get();
    $cards = Card::where('user_id', $user->id)->get();

    return view('user-backend.pages.profile', compact('profile', 'payments', 'chats',"cards","user"));
}


    // Show the form to edit user profile
    public function edit()
    {
        $user = Auth::user();
        $profile = UserProfile::where('user_id', $user->id)->first();
        return view('user_backend.pages.profile_edit', compact('profile'));
    }
    public function cardUpdate(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string', // Stripe Payment Method ID from the frontend
        ]);

        $stripeSecretKey = env('STRIPE_SECRET'); // Your Stripe Secret Key
        $paymentMethodId = $request->input('payment_method');
        $user = auth()->user(); // Get the authenticated user

        try {
            // Step 1: Create a Stripe Customer if the user doesn't have one
            if (!$user->stripe_id) {
                $customerData = [
                    'email' => $user->email,
                    'name' => $user->name,
                ];

                $customerResponse = $this->makeCurlRequest(
                    'https://api.stripe.com/v1/customers',
                    $customerData,
                    $stripeSecretKey
                );

                $customer = json_decode($customerResponse, true);
                if (isset($customer['error'])) {
                    throw new Exception($customer['error']['message']);
                }

                $user->stripe_id = $customer['id'];
                $user->save();
            }

            // Step 2: Attach the payment method to the customer
            $attachData = [
                'customer' => $user->stripe_id,
            ];

            $attachResponse = $this->makeCurlRequest(
                "https://api.stripe.com/v1/payment_methods/$paymentMethodId/attach",
                $attachData,
                $stripeSecretKey
            );

            $paymentMethod = json_decode($attachResponse, true);
            if (isset($paymentMethod['error'])) {
                throw new Exception($paymentMethod['error']['message']);
            }

            // Step 3: Set the payment method as the default for the customer
            $defaultMethodData = [
                'invoice_settings[default_payment_method]' => $paymentMethodId,
            ];

            $this->makeCurlRequest(
                "https://api.stripe.com/v1/customers/{$user->stripe_id}",
                $defaultMethodData,
                $stripeSecretKey
            );

            // Step 4: Save card details in your database
            Card::updateOrCreate(
                ['user_id' => $user->id, 'last_four_digits' => $paymentMethod['card']['last4']],
                [
                    'stripe_id' => $user->stripe_id,
                    'card_brand' => $paymentMethod['card']['brand'],
                    'last_four_digits' => $paymentMethod['card']['last4'],
                    'card_holder_name' => $request->input('cardholder_name'),
                    'billing_address_line1' => $request->input('billing_address_line1'),
                    'billing_address_line2' => $request->input('billing_address_line2'),
                    'billing_city' => $request->input('billing_city'),
                    'billing_state' => $request->input('billing_state'),
                    'billing_zip' => $request->input('billing_zip'),
                    'billing_country' => $request->input('billing_country'),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Card information updated successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper function to make cURL requests
     */
    private function makeCurlRequest($url, $postData, $apiKey)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/x-www-form-urlencoded',
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception('cURL Error: ' . curl_error($ch));
        }
        curl_close($ch);

        return $response;
    }

    public function userLookup(Request $request)
    {
        $query = $request->query('query');
        $users = User::where('name', 'like', '%' . $query . '%')->orWhere('id', 'like', '%' . $query . '%')->select('name')->limit(10)->get();
        return response()->json($users);
    }

    // Update user profile
    public function update(Request $request)
    {
        $user = Auth::user();

        // Check if the user profile exists and has a picture
        $profile = UserProfile::where('user_id', $user->id)->first();
        if ($profile && $profile->picture) {
            // Delete the old picture file if it exists
            $oldPicturePath = public_path('uploads/profile_pictures/' . $profile->picture);
            if (file_exists($oldPicturePath)) {
                unlink($oldPicturePath);
            }
        }

        // Handle the new picture upload
        $newPictureName = $profile->picture ?? null;
        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $newPictureName = time() . '_picture_' . $user->username . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile_pictures'), $newPictureName);

            // Update the request data to include the new picture name
            $request->merge(['picture' => $newPictureName]);
        }

        // Update or create the user profile
        $profile = UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            $request->only(['picture', 'phone', 'name', 'email', 'auto_buy_feature', 'auto_sell_toggle'])
        );

        // Update the user model's avatar and name if provided
        if ($newPictureName) {
            $user->avatar = $newPictureName;
        }
        if ($request->filled('name')) {
            $user->name = $request->input('name');
        }
        $user->save();

        // Prepare the JSON response
        return response()->json([
            'name' => $profile->name ?? $user->username,
            'email' => $profile->email ?? $user->email,
            'phone' => $profile->phone ?? 'Not Provided',
            'picture' => url('uploads/profile_pictures/' . ($profile->picture ?? $user->avatar)),
            'auto_buy_feature' => $profile->auto_buy_feature ? true : false,
            'auto_sell_toggle' => $profile->auto_sell_toggle ? true : false,
            'success' => true,
            'message' => 'Profile updated successfully.'
        ]);
    }

    // Store payment details
    public function storePayment(Request $request)
    {
        $user = Auth::user();
        Payment::create([
            'email' => $request->email,
            'name' => $request->name,
            'phone' => $request->phone,
            'creditcard_number' => $request->creditcard_number,
            'cvc' => $request->cvc,
            'stripe_or_paddle_id' => $request->stripe_or_paddle_id,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'time' => now(),
            'card_id' => $request->card_id,
            'user_id' => $user->id,
            'reason' => $request->reason,
        ]);

        return redirect()->route('profile.index')->with('success', 'Payment added successfully.');
    }

    // View chat conversations
    public function viewChats()
    {
        $user = Auth::user();

        // Get all chats where the user is either the sender or receiver
        $chats = Chat::where('user_id', $user->id)
            ->orWhere('to_user_id', $user->id)
            ->get();

        // Collect all user IDs involved in the chats
        $userIds = $chats->pluck('user_id')->merge($chats->pluck('to_user_id'))->unique();

        // Retrieve the users' details (id, picture, username)
        $users = User::whereIn('id', $userIds)->get(['id', 'picture', 'username']);

        // Return the chats and user details as JSON
        return response()->json([
            'chats' => $chats,
            'users' => $users,
        ]);
    }


    // Send a new message
    public function sendMessage(Request $request)
    {
        $user = Auth::user();
        $chat = Chat::firstOrCreate([
            'user_id' => $user->id,
            'to_user_id' => $request->to_user_id,
        ]);
        $message = Message::create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'to_user_id' => $request->to_user_id,
            'message' => $request->message,
        ]);
        return response()->json(['success' => true, 'message' => 'Message sent successfully.', 'data' => $message]);
    }

    // Retrieve chat messages
    public function getMessages($chatId)
    {
        // Find the chat, but don't fail if it doesn't exist
        $chat = Chat::find($chatId);

        // If the chat doesn't exist, return an empty response
        if (!$chat) {
            return response()->json(['messages' => [], 'users' => []]);
        }

        // Get all messages for the chat
        $messages = $chat->messages()->get();

        // Get the user IDs involved in the chat
        $userIds = [$chat->user_id, $chat->to_user_id];

        // Fetch users with their picture and username
        $users = User::whereIn('id', $userIds)->get(['id', 'picture', 'username']);

        // Prepare the response with messages and users
        return response()->json([
            'messages' => $messages,
            'users' => $users,
        ]);
    }


}

