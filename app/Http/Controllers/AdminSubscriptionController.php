<?php

// app/Http/Controllers/AdminSubscriptionController.php
namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Stripe\Stripe;

class AdminSubscriptionController extends Controller
{
    public function index()
    {
        $users = User::with('subscriptions')->get();
        return view('admin.subscriptions', compact('users'));
    }

    public function create(User $user)
    {
        $plans = Plan::all();
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $coupons = \Stripe\Coupon::all(['limit' => 100])['data'];
        return view('admin.subscribe', compact('user', 'plans', 'coupons'));
    }

    public function subscribe(Request $request, User $user)
    {
        $plan = Plan::findOrFail($request->plan_id);
        $coupon = $request->coupon_id;

        if ($coupon) {
            $user->newSubscription('default', $plan->stripe_plan_id)->withCoupon($coupon)->create();
        } else {
            $user->newSubscription('default', $plan->stripe_plan_id)->create();
        }

        return redirect()->route('admin.subscriptions')->with('success', 'User subscribed successfully');
    }

    public function cancel(User $user)
    {
        $user->subscription('default')->cancel();
        return redirect()->back()->with('success', 'Subscription canceled');
    }
}
