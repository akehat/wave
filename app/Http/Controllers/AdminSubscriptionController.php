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
        $plans = Plan::all();
        // dd(config('app.stripe.private_key'));
        Stripe::setApiKey(config('app.stripe.private_key'));
        $coupons = \Stripe\Coupon::all(['limit' => 100])['data']; // Fetch all coupons from Stripe
        return view('admin.subscriptions', compact('users', 'plans', 'coupons'));
    }

    public function storePlan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'interval' => 'required|in:day,week,month,year',
            'interval_count' => 'required|integer|min:1',
        ]);
    
        try {
            $stripePlan = \Stripe\Plan::create([
                'amount' => $request->price * 100, // Convert to cents
                'currency' => $request->currency,
                'interval' => $request->interval,
                'interval_count' => $request->interval_count,
                'product' => ['name' => $request->name],
            ]);
    
            Plan::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'currency' => $request->currency,
                'interval' => $request->interval,
                'interval_count' => $request->interval_count,
                'stripe_plan_id' => $stripePlan->id,
            ]);
    
            return redirect()->back()->with('success', 'Plan created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Plan creation failed: ' . $e->getMessage());
        }
    }

    public function storeCoupon(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'percent_off' => 'required|numeric|min:0|max:100',
            'duration' => 'required|in:once,repeating,forever',
            'duration_in_months' => 'nullable|integer|min:1|required_if:duration,repeating',
        ]);

        $couponData = [
            'name' => $request->name,
            'percent_off' => $request->percent_off,
            'duration' => $request->duration,
        ];

        if ($request->duration === 'repeating') {
            $couponData['duration_in_months'] = $request->duration_in_months;
        }

        \Stripe\Coupon::create($couponData);

        return redirect()->back()->with('success', 'Coupon created successfully');
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'coupon_id' => 'nullable|string',
        ]);
    
        $user = User::findOrFail($request->user_id);
        $plan = Plan::findOrFail($request->plan_id);
        $coupon = $request->coupon_id;
    
        // Check if the plan exists in Stripe
        try {
            \Stripe\Plan::retrieve($plan->stripe_plan_id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invalid plan');
        }
    
        // Check if the coupon exists in Stripe if provided
        if ($coupon) {
            try {
                \Stripe\Coupon::retrieve($coupon);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Invalid coupon');
            }
        }
    
        // Ensure user has a payment method
        if (!$user->hasPaymentMethod()) {
            return redirect()->back()->with('error', 'User has no payment method');
        }
    
        try {
            if ($coupon) {
                $user->newSubscription('default', $plan->stripe_plan_id)
                    ->withCoupon($coupon)
                    ->create($user->defaultPaymentMethod()->id);
            } else {
                $user->newSubscription('default', $plan->stripe_plan_id)
                    ->create($user->defaultPaymentMethod()->id);
            }
            \Log::info("Admin subscribed user {$user->id} to plan {$plan->id}", ['admin_id' => auth()->id()]);
            return redirect()->back()->with('success', 'User subscribed successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Subscription failed: ' . $e->getMessage());
        }
    }

    public function cancel(User $user)
    {
        if ($user->subscribed('default')) {
            $user->subscription('default')->cancel();
            return redirect()->back()->with('success', 'Subscription canceled');
        }
        return redirect()->back()->with('error', 'User has no active subscription');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'coupon_id' => 'required|string',
        ]);

        $user = User::findOrFail($request->user_id);

        if ($user->subscribed('default')) {
            $user->subscription('default')->applyCoupon($request->coupon_id);
            return redirect()->back()->with('success', 'Coupon applied successfully');
        }

        return redirect()->back()->with('error', 'User is not subscribed');
    }
}