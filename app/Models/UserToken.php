<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserToken extends Model
{
    protected $fillable = ['user_id', 'token'];

    // Function to generate a token for the current user
    public static function generateToken()
    {
        $user = Auth::user();

        if (!$user) {
            return null;
        }

        // Delete any old tokens (older than 5 hours)
        self::where('created_at', '<', Carbon::now()->subHours(5))->delete();

        // Generate token using user details and some random junk
        $token = md5($user->password . $user->email . Str::random(40) . microtime());

        // Create a new token record
        return self::create([
            'user_id' => $user->id,
            'token' => $token,
        ])->token;
    }

    // Function to get the user_id associated with a token
    public static function getUserByToken($token)
    {
        $tokenRecord = self::where('token', $token)->first();

        if ($tokenRecord) {
            return $tokenRecord->user_id;
        }

        return null;
    }

    // Function to delete a token (e.g., on connection close)
    public static function deleteToken($token)
    {
        self::where('token', $token)->delete();
    }
}
