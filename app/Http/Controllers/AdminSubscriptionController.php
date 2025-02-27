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

        // Create the plan in Stripe
        $stripePlan = \Stripe\Plan::create([
            'amount' => $request->price * 100, // Convert to cents
            'currency' => $request->currency,
            'interval' => $request->interval,
            'interval_count' => $request->interval_count,
            'product' => ['name' => $request->name],
        ]);

        // Store in local database
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

        // Ensure user has a payment method (assuming it's set up elsewhere)
        if (!$user->hasPaymentMethod()) {
            return redirect()->back()->with('error', 'User has no payment method');
        }

        if ($coupon) {
            $user->newSubscription('default', $plan->stripe_plan_id)
                ->withCoupon($coupon)
                ->create($user->defaultPaymentMethod()->id);
        } else {
            $user->newSubscription('default', $plan->stripe_plan_id)
                ->create($user->defaultPaymentMethod()->id);
        }

        return redirect()->back()->with('success', 'User subscribed successfully');
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