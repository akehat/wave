<?php

// app/Http/Controllers/SubscriptionController.php
namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        return view('plans', compact('plans'));
    }

    public function subscribe(Request $request, Plan $plan)
    {
        $user = auth()->user();
        // Assumes payment method is set up (simplified for this example)
        $user->newSubscription('default', $plan->stripe_plan_id)->create($request->paymentMethod);
        return redirect()->route('home')->with('success', 'Subscribed successfully');
    }

    public function cancel(Request $request)
    {
        $user = auth()->user();
        $user->subscription('default')->cancel();
        return redirect()->route('home')->with('success', 'Subscription canceled');
    }
}
