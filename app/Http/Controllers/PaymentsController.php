<?php
// app/Http/Controllers/PaymentsController.php
namespace App\Http\Controllers;

use App\Models\Card; // Assuming you have a Card model
use Illuminate\Http\Request;
use Stripe\Stripe;

class PaymentsController extends Controller
{
    public function cardUpdate(Request $request)
{
    $request->validate([
        'cardholder_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\.]+$/',
        'card_number' => 'required|string|regex:/^\d{13,19}$/',
        'expiry_month' => 'required|numeric|between:1,12',
        'expiry_year' => 'required|numeric|min:' . date('Y'),
        'cvc' => 'required|string|regex:/^\d{3,4}$/',
        'billing_address_line1' => 'required|string',
        'billing_address_line2' => 'nullable|string',
        'billing_city' => 'required|string',
        'billing_state' => 'required|string',
        'billing_zip' => 'required|string',
        'billing_country' => 'required|string',
    ]);

    $user = auth()->user();
    Stripe::setApiKey(env('STRIPE_SECRET'));

    try {
        // Create Stripe customer if not exists
        if (!$user->stripe_id) {
            $user->createAsStripeCustomer();
        }

        // Create payment method
        $paymentMethod = \Stripe\PaymentMethod::create([
            'type' => 'card',
            'card' => [
                'number' => $request->card_number,
                'exp_month' => $request->expiry_month,
                'exp_year' => $request->expiry_year,
                'cvc' => $request->cvc,
            ],
        ]);

        // Attach to customer
        $user->addPaymentMethod($paymentMethod->id);

        // Set as default payment method
        $user->updateDefaultPaymentMethod($paymentMethod->id);

        // Save card details locally (avoid storing sensitive data in production)
        Card::updateOrCreate(
            ['user_id' => $user->id, 'last_four_digits' => $paymentMethod->card->last4],
            [
                'payments_id' => $paymentMethod->id,
                'stripe_id' => $user->stripe_id,
                'card_brand' => $paymentMethod->card->brand,
                'last_four_digits' => $paymentMethod->card->last4,
                'card_holder_name' => $request->cardholder_name,
                'billing_address_line1' => $request->billing_address_line1,
                'billing_address_line2' => $request->billing_address_line2,
                'billing_city' => $request->billing_city,
                'billing_state' => $request->billing_state,
                'billing_zip' => $request->billing_zip,
                'billing_country' => $request->billing_country,
            ]
        );

        return response()->json(['success' => true, 'message' => 'Card updated successfully']);
    } catch (\Stripe\Exception\CardException $e) {
        return response()->json(['success' => false, 'message' => 'Card declined: ' . $e->getMessage()], 400);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}

    // Refactor chargeUser to use Cashier
    public static function chargeUser($user, $amount, $currency, $method = 1)
    {
        try {
            if (!$user->stripe_id) {
                $user->createAsStripeCustomer();
            }

            $paymentMethod = $method == 1 ? $user->defaultPaymentMethod()->id : $user->payments_id;

            if (!$paymentMethod) {
                throw new \Exception('No payment method available');
            }

            // Charge the user (amount in cents)
            $charge = $user->charge($amount * 100, $paymentMethod, ['currency' => $currency]);

            return [
                'success' => true,
                'message' => 'Payment successful',
                'charge' => $charge,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}