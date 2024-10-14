<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use Wave\Http\Controllers\Auth\LoginController as AuthLoginController;

class LoginController extends AuthLoginController
{
    protected function authenticated(Request $request, $user)
    {
        // Check if the user is not an admin
        if (!$user->hasRole('admin')) {
            return redirect()->route('user-backend.index');
        }

        // If the user is an admin, redirect them to the default admin dashboard or another route
        return redirect()->route('wave.dashboard'); // Adjust this route as needed
    }
}
