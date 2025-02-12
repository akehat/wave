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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Log;
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
    $chats = Chat::where('user_id', $user->id)
             ->orWhere('to_user_id', $user->id)
             ->get();
    // Collect messages from the fetched chats
    $messages = $chats->flatMap(function ($chat) {
        return $chat->messages; // Assuming 'messages' is a relationship on the Chat model
    });
    $cards = Card::where('user_id', $user->id)->get();

    return view('user-backend.pages.profile', compact('profile', 'payments', 'chats',"cards","user",'messages'));
}


    // Show the form to edit user profile
    public function edit()
    {
        $user = Auth::user();
        $profile = UserProfile::where('user_id', $user->id)->first();
        return view('user_backend.pages.profile_edit', compact('profile'));
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
        $users = User::where('email', 'like', '%' . $query . '%')->orWhere('id', 'like', '%' . $query . '%')->select('name')->limit(10)->get();
        return response()->json($users);
    }
    public function sendMessage(Request $request)
    {
        $user = Auth::user();
        try {
            $to_user = User::where('email', $request->recipient)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred.']);
        }
        $chat = Chat::firstOrCreate([
            'user_id' => $user->id,
            'to_user_id' => $to_user->id,
        ]);
        $message = Message::create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'to_user_id' => $to_user->id,
            'text' => $request->message,
        ]);
        $data = [
            'type' => 'new_message',
            'chat_id' => $chat->id,
            'from_user' => $user->email,
            'message' => $message->text,
            'timestamp' => now()->toDateTimeString(),
        ];
        try{
            // Send the message to the WebSocket server
            $this->notifyWebSocketServer($data, $to_user);
        }catch(Exception $e){
            Log::info($e);
        }
        return response()->json(['success' => true, 'message' => 'Message sent successfully.', 'data' => $message]);
    }
        protected function notifyWebSocketServer(array $data, User $to_user)
        {
            $gearmanHost = $to_user->gearman_ip ?? 'localhost'; // fallback to localhost if null
            $hostParts = explode(":::", $gearmanHost);
            $useWebsocket = (isset($hostParts[1]) && str_contains(strtolower($hostParts[1]),"websocket") );
            if($useWebsocket){
                if($hostParts[0]=="localhost" || $hostParts[0]=='127.0.0.1'){
                    $wsUrl='ws://localhost:8080';
                }else{
                    $wsUrl='wss://'.$hostParts[0].'/ws/';
                }
            }else{
                $wsUrl= 'ws://127.0.0.1:8080';
            }
            // Use a WebSocket client to send the message
            $client = new \WebSocket\Client($wsUrl);
        
            $payload = [
                'action' => 'broadcast',
                'gearmanCode' => config('app.secretcode', 'defaultSecretCode'),
                'msg' => json_encode($data),
                'user' => $to_user->id,
            ];
        
            try {
                $client->send(json_encode($payload));
                $client->close();
            } catch (\Exception $e) {
                \Log::error('Failed to send WebSocket notification: ' . $e->getMessage());
            }
        }    // Update user profile
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
        // Update or create the user profile
        $profile = UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'picture' => $request->input('picture'),
                'phone' => $request->input('phone'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'auto_buy_feature' => $request->has('auto_buy_feature') ? 1 : 0, // Default to 0 if not set
                'auto_sell_toggle' => $request->has('auto_sell_toggle') ? 1 : 0, // Default to 0 if not set
            ]
        );

        // Update the user model's avatar and name if provided
        if ($newPictureName) {
            $user->avatar = $newPictureName;
        }
        if ($request->filled('name')) {
            $user->name = $request->input('name');
        }
        $user->save();
        $profile->save();
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

