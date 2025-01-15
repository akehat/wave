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
        return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
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

