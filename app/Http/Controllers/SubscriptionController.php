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
    
        if (!$user->hasPaymentMethod()) {
            return redirect()->back()->with('error', 'Please add a payment method first');
        }
    
        try {
            $user->newSubscription('default', $plan->stripe_plan_id)
                ->create($user->defaultPaymentMethod()->id);
            return redirect()->route('home')->with('success', 'Subscribed successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Subscription failed: ' . $e->getMessage());
        }
    }

   
    public function cancel(Request $request)
    {
        $user = auth()->user();
        try {
            if ($user->subscribed('default')) {
                $user->subscription('default')->cancel();
                return redirect()->route('home')->with('success', 'Subscription canceled');
            }
            return redirect()->route('home')->with('error', 'No active subscription');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Cancellation failed: ' . $e->getMessage());
        }
    }
}
