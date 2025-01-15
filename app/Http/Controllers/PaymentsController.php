<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public static function makePayment($apiKey, $amount, $currency, $source, $description)
    {
        $url = "https://api.stripe.com/v1/payment_intents";

        $data = [
            'amount' => $amount,
            'currency' => $currency,
            'payment_method' => $source,
            'confirm' => true,
            'description' => $description,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $apiKey",
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['error' => $error];
        }

        return json_decode($response, true);
    }

    public static function returnPayment($apiKey, $paymentIntentId)
    {
        $url = "https://api.stripe.com/v1/refunds";

        $data = [
            'payment_intent' => $paymentIntentId,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $apiKey",
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['error' => $error];
        }

        return json_decode($response, true);
    }
    public static function checkCard($apiKey, $cardNumber, $expMonth, $expYear, $cvc)
    {
        $url = "https://api.stripe.com/v1/tokens";

        $data = [
            'card' => [
                'number' => $cardNumber,
                'exp_month' => $expMonth,
                'exp_year' => $expYear,
                'cvc' => $cvc,
            ],
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $apiKey",
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['error' => $error];
        }

        return json_decode($response, true);
    }
    public static function tokenizeAndPay($apiKey, $cardDetails, $amount, $currency, $description)
    {
        // Extract card details
        $cardNumber = $cardDetails['number'];
        $expMonth = $cardDetails['exp_month'];
        $expYear = $cardDetails['exp_year'];
        $cvc = $cardDetails['cvc'];

        // Step 1: Tokenize the card
        $tokenResponse = self::checkCard($apiKey, $cardNumber, $expMonth, $expYear, $cvc);

        // Check if tokenization succeeded
        if (isset($tokenResponse['error'])) {
            return [
                'success' => false,
                'message' => 'Card tokenization failed.',
                'error' => $tokenResponse['error'],
            ];
        }

        if (!isset($tokenResponse['id'])) {
            return [
                'success' => false,
                'message' => 'Token not created. Unknown error occurred.',
            ];
        }

        $cardToken = $tokenResponse['id'];

        // Step 2: Make the payment
        $paymentResponse = self::makePayment($apiKey, $amount, $currency, $cardToken, $description);

        // Check if payment succeeded
        if (isset($paymentResponse['error'])) {
            return [
                'success' => false,
                'message' => 'Payment failed.',
                'error' => $paymentResponse['error'],
            ];
        }

        return [
            'success' => true,
            'message' => 'Payment processed successfully.',
            'payment_details' => $paymentResponse,
        ];
    }
    public static function formatCardDetails($cardNumber, $expMonth, $expYear, $cvc)
    {
        return [
            'number' => $cardNumber,
            'exp_month' => $expMonth,
            'exp_year' => $expYear,
            'cvc' => $cvc,
        ];
    }
     /**
     * Charge a user for a subscription or one-time payment.
     *
     * @param \App\Models\User $user
     * @param int $amount Amount in cents (e.g., $10 = 1000)
     * @param string $currency Currency code (e.g., "usd")
     * @return array
     * @throws \Exception
     */
    public static function chargeUser($user, $amount, $currency ,$method=1)
    {
        $stripeSecretKey = env('STRIPE_SECRET'); // Your Stripe Secret Key

        try {
            // Ensure the user has a Stripe Customer ID
            if (!$user->stripe_id) {
                throw new Exception('User does not have a saved payment method.');
            }

            // Create a PaymentIntent
            $paymentData = [
                'amount' => $amount, // Amount in cents
                'currency' => $currency, // Currency code
                'customer' => $user->stripe_id, // Stripe Customer ID
                'payment_method' => $method==1?self::getDefaultPaymentMethod($user->stripe_id, $stripeSecretKey):$user->payments_id, // Default payment method
                'off_session' => true, // Use the card without user interaction
                'confirm' => true, // Automatically confirm the payment
            ];

            $paymentResponse = self::makeCurlRequest(
                'https://api.stripe.com/v1/payment_intents',
                $paymentData,
                $stripeSecretKey
            );

            $paymentIntent = json_decode($paymentResponse, true);

            // Check for errors in the response
            if (isset($paymentIntent['error'])) {
                throw new Exception($paymentIntent['error']['message']);
            }

            return [
                'success' => true,
                'message' => 'Payment successful!',
                'payment_intent' => $paymentIntent,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Helper function to get the default payment method for a customer
     *
     * @param string $customerId
     * @param string $apiKey
     * @return string
     * @throws \Exception
     */
    public static function getDefaultPaymentMethod($customerId, $apiKey)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/customers/$customerId");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/x-www-form-urlencoded',
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception('cURL Error: ' . curl_error($ch));
        }
        curl_close($ch);

        $customer = json_decode($response, true);
        if (isset($customer['invoice_settings']['default_payment_method'])) {
            return $customer['invoice_settings']['default_payment_method'];
        }

        throw new Exception('No default payment method found for this customer.');
    }

    /**
     * Helper function to make a cURL request to the Stripe API
     *
     * @param string $url
     * @param array $data
     * @param string $apiKey
     * @return string
     * @throws \Exception
     */
    public static function makeCurlRequest($url, $data, $apiKey)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
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
    public function cardUpdate(Request $request)
{
    $request->validate([
        'cardholder_name' => 'required|string',
        'card_number' => 'required|string',
        'expiry_month' => 'required|numeric',
        'expiry_year' => 'required|numeric',
        'cvc' => 'required|string',
        'billing_address_line1' => 'required|string',
        'billing_address_line2' => 'nullable|string',
        'billing_city' => 'required|string',
        'billing_state' => 'required|string',
        'billing_zip' => 'required|string',
        'billing_country' => 'required|string',
    ]);

    $stripeSecretKey = env('STRIPE_SECRET'); // Your Stripe Secret Key
    $user = auth()->user(); // Get the authenticated user

    try {
        // Step 1: Create a Stripe Customer if the user doesn't have one
        if (!$user->stripe_id) {
            $customerData = [
                'email' => $user->email,
                'name' => $user->name,
            ];

            $customerResponse = $this->makeCurlRequest(
                'https://api.stripe.com/v1/customers',
                $customerData,
                $stripeSecretKey
            );

            $customer = json_decode($customerResponse, true);
            if (isset($customer['error'])) {
                throw new Exception($customer['error']['message']);
            }

            $user->stripe_id = $customer['id'];
            $user->save();
        }

        // Step 2: Create a Payment Method
        $paymentMethodData = [
            'type' => 'card',
            'card' => [
                'number' => $request->input('card_number'),
                'exp_month' => $request->input('expiry_month'),
                'exp_year' => $request->input('expiry_year'),
                'cvc' => $request->input('cvc'),
            ],
        ];

        $paymentMethodResponse = $this->makeCurlRequest(
            'https://api.stripe.com/v1/payment_methods',
            $paymentMethodData,
            $stripeSecretKey
        );

        $paymentMethod = json_decode($paymentMethodResponse, true);
        if (isset($paymentMethod['error'])) {
            throw new Exception($paymentMethod['error']['message']);
        }

        // Step 3: Attach the payment method to the customer
        $attachData = [
            'customer' => $user->stripe_id,
        ];

        $attachResponse = $this->makeCurlRequest(
            "https://api.stripe.com/v1/payment_methods/{$paymentMethod['id']}/attach",
            $attachData,
            $stripeSecretKey
        );

        $attachedPaymentMethod = json_decode($attachResponse, true);
        if (isset($attachedPaymentMethod['error'])) {
            throw new Exception($attachedPaymentMethod['error']['message']);
        }

        // Step 4: Set the payment method as the default for the customer
        $defaultMethodData = [
            'invoice_settings[default_payment_method]' => $paymentMethod['id'],
        ];

        $this->makeCurlRequest(
            "https://api.stripe.com/v1/customers/{$user->stripe_id}",
            $defaultMethodData,
            $stripeSecretKey
        );

        // Step 5: Save card details in your database
        Card::updateOrCreate(
            ['user_id' => $user->id, 'last_four_digits' => $paymentMethod['card']['last4']],
            [
                'payments_id' => $paymentMethod['id'],
                'stripe_id' => $user->stripe_id,
                'card_brand' => $paymentMethod['card']['brand'],
                'last_four_digits' => $paymentMethod['card']['last4'],
                'card_holder_name' => $request->input('cardholder_name'),
                'billing_address_line1' => $request->input('billing_address_line1'),
                'billing_address_line2' => $request->input('billing_address_line2'),
                'billing_city' => $request->input('billing_city'),
                'billing_state' => $request->input('billing_state'),
                'billing_zip' => $request->input('billing_zip'),
                'billing_country' => $request->input('billing_country'),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Card information updated successfully!',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}


    /**
     * Helper function to make cURL requests
     */

}
