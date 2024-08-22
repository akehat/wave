<?php

namespace App\Http\Controllers;
use App\Models\Broker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GearmanClientController;
class UserBackendController extends Controller
{
    function portal(){
        return view('user-backend.index');
    }
    function contact(){
        return view('user-backend.contact');
    }
    function howto(){
        return view('user-backend.howto');
    }
    function menu(){
        return view('user-backend.menu');
    }
    function site(){
        return view('user-backend.site');
    }
    function general(){
        return view('user-backend.general');
    }

    public function save_brokers(Request $request)
        {
            // dd($request);

            // Validate the incoming request data
            $validatedData = $request->validate([
                'tradier_username' => 'nullable|string',
                'tradier_password' => 'nullable|string',
                'tradier_token' => 'nullable|string',
                'robinhood_username' => 'nullable|string',
                'robinhood_password' => 'nullable|string',
                'schwab_username' => 'nullable|string',
                'schwab_password' => 'nullable|string',
                'schwab_totp' => 'nullable|string',
                'fidelity_username' => 'nullable|string',
                'fidelity_password' => 'nullable|string',
                'fennel_email' => 'nullable|string',
                'firstrade_username' => 'nullable|string',
                'firstrade_password' => 'nullable|string',
                'firstrade_pin' => 'nullable|string',
                'public_username' => 'nullable|string',
                'public_password' => 'nullable|string',
                'tastytrade_username' => 'nullable|string',
                'tastytrade_password' => 'nullable|string',
                'vanguard_username' => 'nullable|string',
                'vanguard_password' => 'nullable|string',
                'vanguard_phone_last_four' => 'nullable|string',
                'webull_username' => 'nullable|string',
                'webull_password' => 'nullable|string',
                'webull_did' => 'nullable|string',
                'webull_trading_pin' => 'nullable|string',
            ]);

            // Get the authenticated user
            $user = auth()->user();

            // Store broker data for Tradier
            Broker::updateOrCreate(
                ['user_id' => $user->id, 'broker_name' => 'Tradier'],
                [
                    'enabled' => $request->tradier_enabled=="on"?0:1,
                    'username' => $request->tradier_username,
                    'password' => $request->tradier_password,
                    'token' => $request->tradier_token,
                ]
            );

            // Store broker data for Robinhood
            Broker::updateOrCreate(
                ['user_id' => $user->id, 'broker_name' => 'Robinhood'],
                [
                    'enabled' => $request->robinhood_enabled=="on"?0:1,
                    'username' => $request->robinhood_username,
                    'password' => $request->robinhood_password,
                ]
            );

            // Store broker data for Schwab
            Broker::updateOrCreate(
                ['user_id' => $user->id, 'broker_name' => 'Schwab'],
                [
                    'enabled' => $request->schwab_enabled=="on"?0:1,
                    'username' => $request->schwab_username,
                    'password' => $request->schwab_password,
                    'totp' => $request->schwab_totp,
                ]
            );

            // Store broker data for Fidelity
            Broker::updateOrCreate(
                ['user_id' => $user->id, 'broker_name' => 'Fidelity'],
                [
                    'enabled' => $request->fidelity_enabled=="on"?0:1,
                    'username' => $request->fidelity_username,
                    'password' => $request->fidelity_password,
                ]
            );

            // Store broker data for Fennel
            Broker::updateOrCreate(
                ['user_id' => $user->id, 'broker_name' => 'Fennel'],
                [
                    'enabled' => $request->fennel_enabled=="on"?0:1,
                    'email' => $request->fennel_email,
                ]
            );

            // Store broker data for Firstrade
            Broker::updateOrCreate(
                ['user_id' => $user->id, 'broker_name' => 'Firstrade'],
                [
                    'enabled' => $request->firstrade_enabled=="on"?0:1,
                    'username' => $request->firstrade_username,
                    'password' => $request->firstrade_password,
                    'pin' => $request->firstrade_pin,
                ]
            );

            // Store broker data for Public
            Broker::updateOrCreate(
                ['user_id' => $user->id, 'broker_name' => 'Public'],
                [
                    'enabled' => $request->public_enabled=="on"?0:1,
                    'username' => $request->public_username,
                    'password' => $request->public_password,
                ]
            );

            // Store broker data for Tastytrade
            Broker::updateOrCreate(
                ['user_id' => $user->id, 'broker_name' => 'Tastytrade'],
                [
                    'enabled' => $request->tastytrade_enabled=="on"?0:1,
                    'username' => $request->tastytrade_username,
                    'password' => $request->tastytrade_password,
                ]
            );

            // Store broker data for Vanguard
            Broker::updateOrCreate(
                ['user_id' => $user->id, 'broker_name' => 'Vanguard'],
                [
                    'enabled' => $request->vanguard_enabled=="on"?0:1,
                    'username' => $request->vanguard_username,
                    'password' => $request->vanguard_password,
                    'phone_last_four' => $request->vanguard_phone_last_four,
                ]
            );

            // Store broker data for Webull
            Broker::updateOrCreate(
                ['user_id' => $user->id, 'broker_name' => 'Webull'],
                [
                    'enabled' => $request->webull_enabled=="on"?0:1,
                    'username' => $request->webull_username,
                    'password' => $request->webull_password,
                    'did' => $request->webull_did,
                    'trading_pin' => $request->webull_trading_pin,
                ]
            );
            // Redirect back with success message
            return redirect()->back()->with('success', 'Broker information saved successfully!');
        }

        function page($page){
            if(in_array($page,["brokersDefinition","brokersAction"])){
                $user = Auth::user();
                // Retrieve the user's broker data
                $brokers = Broker::where('user_id', $user->id)
                ->orderBy('broker_name', 'asc')
                ->get()
                ->groupBy('broker_name')
                ->map(function ($group) {
                    return $group->first(); // Take the first broker in each group
                });

                // Pass the broker data to the view
                return view('user-backend.pages.' . $page, compact('brokers'));
            }
            return view('user-backend.pages.'.$page);
        }
        public function do_action(Request $request)
        {
            // Retrieve the authenticated user
            $user = Auth::user();

            // Retrieve the user's broker data
            $brokers = Broker::where('user_id', $user->id)->get();

            if ($request->input('broker') == "all") {
                $chase = $brokers->firstWhere('broker_name', "chase");
                $fennel = $brokers->firstWhere('broker_name', "fennel");
                $fidelity = $brokers->firstWhere('broker_name', "fidelity");
                $firstrade = $brokers->firstWhere('broker_name', "firstrade");
                $public = $brokers->firstWhere('broker_name', "public");
                $robinhood = $brokers->firstWhere('broker_name', "robinhood");
                $schwab = $brokers->firstWhere('broker_name', "schwab");
                $tradier = $brokers->firstWhere('broker_name', "tradier");
                $tastytrade = $brokers->firstWhere('broker_name', "tastytrade");
                $vanguard = $brokers->firstWhere('broker_name', "vanguard");
                $webull = $brokers->firstWhere('broker_name', "webull");

                $brokerData = GearmanClientController::prepareEnvContent(
                    null, // Discord token
                    null, // Discord channel
                    "false", // Danger mode
                    optional($chase)->username,
                    optional($chase)->password,
                    optional($chase)->phone_last_four,
                    optional($chase)->debug,
                    optional($fennel)->email,
                    optional($fidelity)->username, // Fidelity
                    optional($fidelity)->password, // Fidelity
                    optional($firstrade)->username, // Firstrade
                    optional($firstrade)->password, // Firstrade
                    optional($firstrade)->pin, // Firstrade PIN
                    optional($public)->username, // Public
                    optional($public)->password, // Public
                    optional($robinhood)->username, // Robinhood
                    optional($robinhood)->password, // Robinhood
                    optional($robinhood)->totp, // Robinhood TOTP
                    optional($schwab)->username, // Schwab
                    optional($schwab)->password, // Schwab
                    optional($schwab)->totp, // Schwab TOTP
                    optional($tradier)->token, // Tradier Access Token
                    optional($tastytrade)->username, // Tastytrade
                    optional($tastytrade)->password, // Tastytrade
                    optional($vanguard)->username, // Vanguard
                    optional($vanguard)->password, // Vanguard
                    optional($vanguard)->phone_last_four, // Vanguard Phone Last Four
                    optional($vanguard)->debug, // Vanguard Debug
                    optional($webull)->username, // Webull
                    optional($webull)->password, // Webull
                    optional($webull)->did, // Webull DID
                    optional($webull)->pin // Webull Trading PIN
                );
            } else {
                // Find the selected broker and its credentials
                $selectedBroker = $brokers->firstWhere('broker_name', $request->input('broker'));

                // Prepare environment variables for the selected broker
                $brokerData = GearmanClientController::prepareEnvContent(
                    null, // Discord token
                    null, // Discord channel
                    "false", // Danger mode
                    optional($selectedBroker)->username,
                    optional($selectedBroker)->password,
                    optional($selectedBroker)->phone_last_four,
                    optional($selectedBroker)->debug,
                    optional($selectedBroker)->email,
                    optional($selectedBroker)->username, // Fidelity
                    optional($selectedBroker)->password, // Fidelity
                    optional($selectedBroker)->username, // Firstrade
                    optional($selectedBroker)->password, // Firstrade
                    optional($selectedBroker)->pin, // Firstrade PIN
                    optional($selectedBroker)->username, // Public
                    optional($selectedBroker)->password, // Public
                    optional($selectedBroker)->username, // Robinhood
                    optional($selectedBroker)->password, // Robinhood
                    optional($selectedBroker)->totp, // Robinhood TOTP
                    optional($selectedBroker)->username, // Schwab
                    optional($selectedBroker)->password, // Schwab
                    optional($selectedBroker)->totp, // Schwab TOTP
                    optional($selectedBroker)->token, // Tradier Access Token
                    optional($selectedBroker)->username, // Tastytrade
                    optional($selectedBroker)->password, // Tastytrade
                    optional($selectedBroker)->username, // Vanguard
                    optional($selectedBroker)->password, // Vanguard
                    optional($selectedBroker)->phone_last_four, // Vanguard Phone Last Four
                    optional($selectedBroker)->debug, // Vanguard Debug
                    optional($selectedBroker)->username, // Webull
                    optional($selectedBroker)->password, // Webull
                    optional($selectedBroker)->did, // Webull DID
                    optional($selectedBroker)->pin // Webull Trading PIN
                );
            }

            // Determine the action (buy, sell, get_holdings)
            $action = $request->input('action');

            // Generate the command based on the selected action and other inputs
            $command = GearmanClientController::generateCommand(
                $action,
                $request->input('quantity'),
                $request->input('symbol'),
                $request->input('broker')
            );

            // Send the task to the Gearman worker and get the result
            $result = GearmanClientController::sendTaskToWorker($command, $brokerData);

            // Return the result
            return response()->json($result);
        }

}
