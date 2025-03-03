<?php

namespace App\Http\Controllers;
use App\Models\Broker;
use App\Models\UserToken;
use App\Models\UserProfile;
use App\Models\User;
use App\Models\ScheduleBuy;
use App\Models\Account;
use App\Models\PendingSms;
use App\Models\Stock;
use App\Models\BroadcastMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GearmanClientController;
use App\Models\ArchivedAccount;
use App\Models\ArchivedStock;
use App\Models\Email;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use function Laravel\Prompts\confirm;


class UserBackendController extends Controller
{
    function portal(){
        return view('user-backend.index');
    }
    function contact(){
        return view('user-backend.contact');
    }
    public function submitContact(Request $request)
{
    // Validate the request data
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
    ]);

    // Create a new email entry
    $email=Email::create([
        'type' => 'contact',               // You can define this or make it dynamic if needed
        'from_name' => $request->name,
        'from_email' => $request->email,
        'to_email' => config('app.contact_email'), // Update with your desired default recipient email
        'content' => $request->message,
        'title' => $request->subject,
    ]);
    $email->save();
    $email->refresh();
    // Redirect or return a success message
    return response()->json(['success' => true, 'message' => 'Your message has been sent successfully!']);
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
                'webull_pin' => 'nullable|string',
                'broker' => 'nullable|string',
            ]);

            // Get the authenticated user
             // Get the authenticated user
            $user = auth()->user();

            // List of brokers and corresponding update logic
            $brokers = [
                'Tradier' => [
                    'enabled' => $request->tradier_enabled != "on" ? 0 : 1,
                    'username' => $request->tradier_username,
                    'password' => $request->tradier_password,
                    'token' => $request->tradier_token,
                ],
                'Robinhood' => [
                    'enabled' => $request->robinhood_enabled != "on" ? 0 : 1,
                    'username' => $request->robinhood_username,
                    'password' => $request->robinhood_password,
                ],
                'Schwab' => [
                    'enabled' => $request->schwab_enabled != "on" ? 0 : 1,
                    'username' => $request->schwab_username,
                    'password' => $request->schwab_password,
                    'totp' => $request->schwab_totp,
                ],
                'Fidelity' => [
                    'enabled' => $request->fidelity_enabled != "on" ? 0 : 1,
                    'username' => $request->fidelity_username,
                    'password' => $request->fidelity_password,
                ],
                'Fennel' => [
                    'enabled' => $request->fennel_enabled != "on" ? 0 : 1,
                    'email' => $request->fennel_email,
                ],
                'Firstrade' => [
                    'enabled' => $request->firstrade_enabled != "on" ? 0 : 1,
                    'username' => $request->firstrade_username,
                    'password' => $request->firstrade_password,
                    'pin' => $request->firstrade_pin,
                ],
                'Public' => [
                    'enabled' => $request->public_enabled != "on" ? 0 : 1,
                    'username' => $request->public_username,
                    'password' => $request->public_password,
                ],
                'Tastytrade' => [
                    'enabled' => $request->tastytrade_enabled != "on" ? 0 : 1,
                    'username' => $request->tastytrade_username,
                    'password' => $request->tastytrade_password,
                ],
                'Vanguard' => [
                    'enabled' => $request->vanguard_enabled != "on" ? 0 : 1,
                    'username' => $request->vanguard_username,
                    'password' => $request->vanguard_password,
                    'phone_last_four' => $request->vanguard_phone_last_four,
                ],
                'Webull' => [
                    'enabled' => $request->webull_enabled != "on" ? 0 : 1,
                    'username' => $request->webull_username,
                    'password' => $request->webull_password,
                    'did' => $request->webull_did,
                    'pin' => $request->webull_pin,
                ],
                'Tornado' => [
                    'enabled' => $request->tornado_enabled != "on" ? 0 : 1,
                    'username' => $request->tornado_username,
                    'password' => $request->tornado_password,
                ],
                'DSPAC' => [
                    'enabled' => $request->DSPAC_enabled != "on" ? 0 : 1,
                    'username' => $request->DSPAC_username,
                    'password' => $request->DSPAC_password,
                ],
                'BBAE' => [
                    'enabled' => $request->BBAE_enabled != "on" ? 0 : 1,
                    'username' => $request->BBAE_username,
                    'password' => $request->BBAE_password,
                ],
            ];
            $b=null;
            // Update only the specified broker if `broker` is present
            if ($request->broker) {
                if (isset($brokers[$request->broker])) {
                    $b=Broker::updateOrCreate(
                        ['user_id' => $user->id, 'broker_name' => $request->broker],
                        $brokers[$request->broker]
                    );
                }
            } else {
                // Update all brokers if no specific broker is provided
                foreach ($brokers as $brokerName => $data) {
                    $b=Broker::updateOrCreate(
                        ['user_id' => $user->id, 'broker_name' => $brokerName],
                        $data
                    );
                }
            }
            // Redirect back with success message
            if ($b) {
                // Return JSON response
                return response()->json([
                    'success' => true,
                    'message' => 'Broker information saved successfully!',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to save broker information.',
            ], 500);
        }
        public function toggleBrokerStatus(Request $request)
            {
                $validated = $request->validate([
                    'broker' => 'required|string',
                    'enabled' => 'required|boolean',
                ]);
                $user = auth()->user();
                $brokerName=$validated['broker'];
                $broker = Broker::updateOrCreate(
                    ['user_id' => $user->id, 'broker_name' => $brokerName],
                    ['enabled'=>$validated['enabled']]
                );
                return response()->json(['success' => true, 'message' => 'Broker status updated.']);
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
        public function do_action(Request $request,$useraccount=null)
        {
            // Retrieve the authenticated user
            $user = $useraccount ?? Auth::user();

            // Retrieve the user's broker data
            $brokers = Broker::where('user_id', $user->id)->get();

            if ($request->input('broker') == "all") {
                $chase = $brokers->firstWhere('broker_name', "Chase");
                $fennel = $brokers->firstWhere('broker_name', "Fennel");
                $fidelity = $brokers->firstWhere('broker_name', "Fidelity");
                $firstrade = $brokers->firstWhere('broker_name', "Firstrade");
                $public = $brokers->firstWhere('broker_name', "Public");
                $robinhood = $brokers->firstWhere('broker_name', "Robinhood");
                $schwab = $brokers->firstWhere('broker_name', "Schwab");
                $tradier = $brokers->firstWhere('broker_name', "Tradier");
                $tastytrade = $brokers->firstWhere('broker_name', "Tastytrade");
                $vanguard = $brokers->firstWhere('broker_name', "Vanguard");
                $webull = $brokers->firstWhere('broker_name', "Webull");
                $tornado = $brokers->firstWhere('broker_name', "Tornado");
                $dspac = $brokers->firstWhere('broker_name', "DSPAC");
                $bbae = $brokers->firstWhere('broker_name', "BBAE");

                $brokerData = (new GearmanClientController())->prepareEnvContent(
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
                    optional($webull)->pin, // Webull Trading PIN
                    optional($tornado)->username, // Tornado
                    optional($tornado)->password, // Tornado
                    optional($dspac)->username, // dspac
                    optional($dspac)->password, // dspac
                    optional($bbae)->username, // bbae
                    optional($bbae)->password, // bbae
                    turnArray:true
                );
            } else {
                // Find the selected broker and its credentials
                $selectedBroker = $brokers->firstWhere('broker_name', $request->input('broker'));
                // Prepare environment variables for the selected broker
                $brokerData = (new GearmanClientController())->prepareEnvContent(
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
                    optional($selectedBroker)->pin, // Webull Trading PIN
                    optional($selectedBroker)->username, // Tornado
                    optional($selectedBroker)->password, // Tornado
                    optional($selectedBroker)->username, // DSPAC
                    optional($selectedBroker)->password, // DSPAC
                    optional($selectedBroker)->username, // BBAE
                    optional($selectedBroker)->password, // BBAE
                    turnArray:true
                );
            }

            // Determine the action (buy, sell, get_holdings,accounts)
            $action = $request->input('action');
            $broker=$request->input('broker');
            $creds=$brokerData[strtoupper($broker)];
            $endpoint=null;
            if(strtolower($broker)=="tradier" && str_starts_with($creds,"SANDBOX")){
                $creds=substr($creds,strlen("SANDBOX"));
                $endpoint="sandbox.tradier.com";
            }

            $onAccounts=$request->input('onAccounts')??null;
            if ($onAccounts != null) {
                $onAccounts = explode(",", $onAccounts);

                // Define which field to use for each broker
                $brokerFieldMap = [
                    'public' => 'account_name',
                    'fidelity' => 'account_number',
                    'fennel' => 'account_name',
                    'robinhood' => 'account_name',
                    'schwab' => 'account_number',
                    'tradier' => 'account_number',
                    'webull' => 'account_name',
                ];

                // Determine the field to use based on the broker
                $field = $brokerFieldMap[strtolower($broker)] ?? 'account_name';  // Default to account_name

                // Fetch accounts using the determined field
                $onAccounts = Account::where('user_id', $user->id)
                                    ->where('broker_name', ucfirst($broker))
                                    ->whereIn('account_number', $onAccounts)->pluck($field)
                                    ->toArray();
            }
            if($useraccount!=null){
                //if user is set then this is from the server and wants to do a lot of jogs at once just get the records ready for sending.
                return (new GearmanClientController())->setDataRecord($broker,$creds,$action,$request->input('symbol'),$request->input('quantity'),$request->input('price')??null,endpoint:$endpoint,userToken:$user->id,onAccounts:$onAccounts,user:$user);
            }
            $result = (new GearmanClientController())->sendTaskToWorkerTwo($broker,$creds,$action,$request->input('symbol'),$request->input('quantity'),$request->input('price')??null,endpoint:$endpoint,userToken:$user->id,onAccounts:$onAccounts);

            // Return the result
            return response()->json($result);
        }
        public function do_actions(Request $request)
        {
            // Parse the incoming request data
            $actions = [];
            $user = Auth::user(); // Retrieve the authenticated user

            // Ensure the 'data' field is provided in the request
            if (!$request->has('data')) {
                return response()->json(['error' => 'No data provided'], 400);
            }

            // Decode the JSON data from the request
            $datas = json_decode($request->input('data'));

            // Check if the data is valid
            if (!is_array($datas)) {
                return response()->json(['error' => 'Invalid data format. Expecting an array of actions.'], 400);
            }
            $schedule=FALSE;
            // Loop through each action in the request data
            foreach ($datas as $data) {
                // Convert the action data to a Request instance for compatibility
                $actionRequest = new Request((array) $data);
                if (!$schedule &&
                    isset($data->date, $data->time, $data->timezone) &&
                    strtotime($data->date . ' ' . $data->time . ' ' . $data->timezone) > time()
                ) {
                    $schedule=TRUE;
                }
                // Call do_action for each item, passing the user
                $actions[] = $this->do_action($actionRequest, $user);
            }
            if( $schedule && count($actions) > 0){
                $scheduledAction = ScheduleBuy::create([
                    'user_id' => $user->id,
                    'timezone' => $data->timezone,
                    'time' => $data->time,
                    'server_time' => now()->format('H:i:s'), // Store the server's current time
                    'date' => $data->date,
                    'action_json' => json_encode($actions), // Store the action data as JSON
                    'broker' => $data->broker ?? 'unknown', // Default to 'unknown' if broker is not provided
                ]);
                $scheduledAction->save();
                $scheduledAction->refresh();
                return json_encode(['message' => "booking created ".$scheduledAction ]);
            } elseif (count($actions) > 0) {
                return json_encode(['message' => (new GearmanClientController())->sendTasksToWorkerTwo($actions,TRUE)]);
            }

            // If no actions were processed, return an appropriate response
            return response()->json(['message' => 'No actions to process'], 200);
        }
        public function admin_do_actions(Request $request)
        {
            // Ensure the user is authenticated and has admin privileges
            if (auth()->guest() || !auth()->user()->can('browse_admin')) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $datas = json_decode($request->input('data'));

            if (!is_array($datas)) {
                return response()->json(['error' => 'Invalid data format. Expecting an array of actions.'], 400);
            }

            // Determine which profiles to act upon based on the first action type
            $profiles = [];
            foreach ($datas as $data) {
                if ($data->action === 'buy') {
                    $profiles = UserProfile::where('auto_buy_feature', true)->get();
                } elseif ($data->action === 'sell') {
                    $profiles = UserProfile::where('auto_sell_toggle', true)->get();
                }
                break; // Only check the first action to determine profiles
            }

            if (!$profiles) {
                return response()->json(['message' => 'No applicable profiles found for the action type'], 400);
            }

            $messages = [];
            foreach ($profiles as $profile) {
                $actions = [];
                $schedule = false;
                $userAccount = User::find($profile->user_id);
                foreach ($datas as $data) {
                    $actionRequest = new Request((array) $data);
                    if (!$userAccount) continue; // Skip if user not found

                    if (!$schedule &&
                        isset($data->date, $data->time, $data->timezone) &&
                        strtotime($data->date . ' ' . $data->time . ' ' . $data->timezone) > time()
                    ) {
                        $schedule = true;
                    }

                    // Process the action for each relevant user
                    $actions[] = $this->do_action($actionRequest, $userAccount);
                }

                if ($schedule && count($actions) > 0) {
                    // Use the userAccount instead of $user which is not defined in this scope
                    $scheduledAction = ScheduleBuy::create([
                        'user_id' => $userAccount->id,
                        'timezone' => $data->timezone,
                        'time' => $data->time,
                        'server_time' => now()->format('H:i:s'), // Store the server's current time
                        'date' => $data->date,
                        'action_json' => json_encode($actions), // Store the action data as JSON
                        'broker' => $data->broker ?? 'unknown', // Default to 'unknown' if broker is not provided
                        'adminCreated' => true, // Explicitly set to true since this is an admin action
                    ]);
                    $scheduledAction->save();
                    $scheduledAction->refresh();
                    $messages[] = json_encode(['message' => "Booking created for user ID " . $scheduledAction->user_id . ": " . $scheduledAction->id]);
                } elseif (count($actions) > 0) {
                    $messages[] = json_encode(['message' => (new GearmanClientController())->sendTasksToWorkerTwo($actions, true)]);
                }
            }

            // If actions were processed, return messages
            if (count($messages) > 0) {
                return json_encode($messages);
            }
            // If no actions were processed, return an appropriate response
            return response()->json(['message' => 'No actions to process'], 200);
        }
        public function verify_2fa(Request $request){
            $user_id=Auth::id();
            $broker = Broker::where('user_id', $user_id)->where("broker_name",$request->input('broker'))->firstOrFail();
            return (new GearmanClientController())->sendTaskToTwoFactor($request->input('broker') . "_" . $user_id . "_". $request->input('for'),$request->input('sms_code'));
        }



        public function sendData(Request $request)
        {
            $request=(object)request()->all();
            $request->user_id=$request->user;
            Log::info(json_encode($request));
            // Secret code check
            $code = $request->gearmanSecretCode ?? null;
            if ($code != config("app.secretcode", "defaultSecretCode")) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            $broker = Broker::where('user_id', $request->user_id)
            ->where('broker_name', ucfirst($request->broker))
            ->first();
            Log::info(json_encode($broker));
            // If the type is "account", process the accounts
            if ($request->type == "account") {
                // Fetch the broker name from the request
                $brokerName = ucfirst($request->broker);

                // Find accounts matching user_id and broker_name
                $accounts = Account::where('user_id', $request->user_id)
                                    ->where('broker_name', $brokerName)
                                    ->get();
                $now = now();

                // Get the last slice_time from archived_accounts
                $lastArchivedSliceTime = ArchivedAccount::where('user_id', $request->user_id)
                                                        ->where('broker_name', $brokerName)
                                                        ->max('slice_time');

                // Fetch all accounts from the last archived slice for this broker
                $lastArchivedAccounts = ArchivedAccount::where('user_id', $request->user_id)
                                                       ->where('broker_name', $brokerName)
                                                       ->where('slice_time', $lastArchivedSliceTime)
                                                       ->get();

                $identical = false;

                // Check if the length of the accounts is different from the last slice
                if ($accounts->count() !== $lastArchivedAccounts->count()) {
                    $identical = false;
                } else {
                    // Compare the content of each account (excluding slice_time, created_at, updated_at)
                    $identical = true;
                    foreach ($accounts as $account) {
                        $matchingAccount = $lastArchivedAccounts->first(function($archivedAccount) use ($account) {
                            return $archivedAccount->user_id === $account->user_id &&
                                   $archivedAccount->account_name === $account->account_name &&
                                   $archivedAccount->broker_name === $account->broker_name &&
                                   $archivedAccount->broker_id === $account->broker_id &&
                                   $archivedAccount->account_number === $account->account_number &&
                                   $archivedAccount->meta === $account->meta;
                        });

                        // If no matching account is found, they are not identical
                        if (!$matchingAccount) {
                            $identical = false;
                            break;
                        }
                    }
                }

                // If the accounts are not identical, archive them
                if (!$identical) {
                    foreach ($accounts as $account) {
                        ArchivedAccount::create([
                            'user_id' => $account->user_id,
                            'account_name' => $account->account_name,
                            'broker_name' => $account->broker_name,
                            'broker_id' => $account->broker_id,
                            'account_number' => $account->account_number,
                            'meta' => $account->meta,
                            'slice_time' => $now,  // Current timestamp as slice time
                            'created_at' => $account->created_at,
                            'updated_at' => $account->updated_at,
                        ]);
                    }
                }

                // Delete the accounts (whether archived or not)
                Account::where('user_id', $request->user_id)
                       ->where('broker_name', $brokerName)
                       ->delete();

                // Add new account data from the request
                $newAccounts = $request->accounts; // Assume incoming accounts data is an array
                if(count((array)$newAccounts)>0){
                    $broker->confirmed=true;
                    $broker->save();
                    $broker->refresh();
                }
                foreach ($newAccounts as $newAccount) {
                    $newAccount = (array) $newAccount;
                    $accountName = isset($newAccount['account_name']) ? $newAccount['account_name'] : $newAccount['account_number'];

                    try {
                        Account::updateOrCreate(
                            ['user_id' => $request->user_id, 'broker_name' => $brokerName, 'account_number' => $newAccount['account_number']],
                            [
                                'account_name' => $accountName,
                                'broker_id' => $broker->id, // Assuming broker_id is provided in the new data
                                'meta' => $newAccount['meta'] ?? null, // Additional data
                            ]
                        );
                    } catch (Exception $e) {
                        Log::info($e);
                    }
                }

                return response()->json(['message' => 'Accounts updated successfully'], 200);
            }


            // If the type is "stocks", process the stocks
            if ($request->type == "stocks") {
                // Fetch the broker name from the request
                $brokerName = ucfirst($request->broker);

                // Find stocks matching user_id and broker_name
                $stocks = Stock::where('user_id', $request->user_id)
                            ->where('broker_name', $brokerName)
                            ->get();
                $now=now();
                // Delete the stocks found
                $lastArchivedSliceTime = ArchivedStock::where('user_id', $request->user_id)
                                        ->where('broker_name', $brokerName)
                                        ->max('slice_time');

                // Fetch all stocks from the last archived slice for this broker
                $lastArchivedStocks = ArchivedStock::where('user_id', $request->user_id)
                                                    ->where('broker_name', $brokerName)
                                                    ->where('slice_time', $lastArchivedSliceTime)
                                                    ->get();
                $identical = false;
                // Check if the length of the stocks is different from the last slice
                if ($stocks->count() !== $lastArchivedStocks->count()) {
                    $identical = false;
                } else {
                    // Compare the content of each stock (excluding slice_time, created_at, updated_at)
                    $identical = true;
                    foreach ($stocks as $stock) {
                        $matchingStock = $lastArchivedStocks->first(function($archivedStock) use ($stock) {
                            return $archivedStock->user_id === $stock->user_id &&
                                $archivedStock->account_id === $stock->account_id &&
                                $archivedStock->broker_name === $stock->broker_name &&
                                $archivedStock->broker_id === $stock->broker_id &&
                                $archivedStock->stock_name === $stock->stock_name &&
                                $archivedStock->shares === $stock->shares &&
                                $archivedStock->price === $stock->price &&
                                $archivedStock->meta === $stock->meta;
                        });

                        // If no matching stock is found, they are not identical
                        if (!$matchingStock) {
                            $identical = false;
                            break;
                        }
                    }
                }
                // If the stocks are not identical, archive them
                if (!$identical) {
                    foreach ($stocks as $stock) {
                        try{
                        ArchivedStock::create([
                            'user_id' => $stock->user_id,
                            'account_id' => $stock->account_id,
                            'broker_name' => $stock->broker_name,
                            'broker_id' => $stock->broker_id,
                            'stock_name' => $stock->stock_name,
                            'shares' => $stock->shares,
                            'price' => $stock->price,
                            'meta' => $stock->meta,
                            'slice_time' => $now,  // Current timestamp as slice time
                            'created_at' => $stock->created_at,
                            'updated_at' => $stock->updated_at,
                        ]);
                    }catch(Exception $e){Log::error($e);}
                    }
                }

                // Delete the stocks (whether archived or not)
                Stock::where('user_id', $request->user_id)
                    ->where('broker_name', $brokerName)
                    ->delete();


                // Add new stock data from the request
                $newStocks = $request->stocks; // Assume incoming stocks data is an array
                if(count((array)$newStocks)>0){
                    $broker->confirmed=true;
                    $broker->save();
                    $broker->refresh();
                }
                foreach ($newStocks as $newStock) {
                    if(isset($newStock['account_id'])){
                        $newStock['account_id']=Account::where('user_id', $request->user_id)->where('broker_name' , $brokerName)->where('account_number',$newStock['account_id'])->first()->id??null;
                    }else{
                        $newStock['account_id']=null;
                    }
                    if(isset($newStock['account_name'])&&$newStock['account_id']==null){
                        $newStock['account_id']=Account::where('user_id', $request->user_id)->where('broker_name' , $brokerName)->where('account_name',$newStock['account_name'])->first()->id??null;
                    }
                    try{
                        Stock::create([
                            'user_id' => $request->user_id,
                            'account_id' => $newStock['account_id'] ?? null,
                            'broker_name' => $brokerName,
                            'broker_id' => $broker->id, // Assuming broker_id is provided in the new data
                            'stock_name' => $newStock['stock_name'],
                            'shares' => $newStock['shares'],
                            'price' => $newStock['price'],
                            'meta' => $newStock['meta'] ?? null, // Additional data
                        ]);
                     }catch(Exception $e){Log::error($e);}


                }

                return response()->json(['message' => 'Stocks updated successfully'], 200);
            }

            return response()->json(['error' => 'Invalid type provided'], 400);
        }
        public function requestSMS(Request $request){
            $user= UserToken::getUserByToken($request->token);
            if($user){
                $message=BroadcastMessage::create([
                    "user_id"=>$user,
                    "data"=>json_encode(["request"=>"SMS","for"=>$request->number,"broker"=>$request->broker])
                ]);
                $message->save();
                $message->refresh();
                Cache::put('polling_flag', true, 2);
                return Response::json(["success"=>"request added for user"], 200);
            }
            return Response::json(["error"=>"request failed, no user"], 300);
        }
        public function createPendingSms(Request $request)
        {
            $gearmanCode = config("app.secretcode", "defaultSecretCode"); // Ensure this matches your Python's SECRETCODE
            if ($request->input('gearmanCode') !== $gearmanCode) {
                return response()->json(['error' => 'Invalid gearman code'], 403);
            }
            try {
                $data = $request->json()->all();

                $sms = PendingSms::create([
                    'user_id' => $data['user'],
                    'broker' => $data['broker'],
                    'for' => $data['for'],
                    'expires_at' => now()->addMinutes(2),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $sms->save();
                $sms->refresh();
                return response()->json(['message' => 'SMS pending record created successfully', 'sms' => $sms]);
            } catch (\Exception $e) {
                \Log::error("Failed to create SMS record: " . $e->getMessage());
                return response()->json(['error' => 'Failed to create SMS record'], 500);
            }
        }
        function getUserData()
        {
            $userId = Auth::id();
            $accounts = Account::where('user_id', $userId)->get();
            $stocks = Stock::where('user_id', $userId)->get();
            $scheduled = ScheduleBuy::where('user_id', $userId)->get();

            // Default response for all users
            $response = [
                'accounts' => $accounts,
                'stocks' => $stocks,
                'scheduled' => $scheduled
            ];

            // Check if the user is an admin
            if (!auth()->guest() && auth()->user()->can('browse_admin')) {
                // Fetch all ScheduleBuy records where adminCreated is true
                $allScheduled = ScheduleBuy::where('adminCreated', true)->get();

                // Process unique admin actions (group by all fields except user_id)
                $adminActions = $allScheduled->groupBy(function ($item) {
                    return json_encode([
                        'timezone' => $item->timezone,
                        'recurring' => $item->recurring,
                        'time' => $item->time,
                        'server_time' => $item->server_time,
                        'date' => $item->date,
                        'action_json' => $item->action_json,
                        'broker' => $item->broker,
                        'adminCreated' => $item->adminCreated
                    ]);
                })->map(function ($group) {
                    $first = $group->first();
                    $first->user_count = $group->count(); // Add user_count to track affected users
                    return $first;
                })->values();

                // Add admin-specific fields to the response
                $response['admin_actions'] = $adminActions; // Unique admin actions with user_count
                $response['all_admin_actions'] = $allScheduled; // All admin-created records
            }

            // Return as JSON
            return response()->json($response);
        }
        function getUser(){
            $data=(array)(request()->all());
            Log::info($data);
            $websocketSecret = config("app.secretcode", "defaultSecretCode");
            if($websocketSecret==$data['gearmanSecretCode']){
                $user_id = UserToken::getUserByToken($data['login']);
                return response()->json(["user_id"=>$user_id],200);
            }else{
                return 401;
            }
        }
        function deleteUser(){
            $data=(array)(request()->all());
            $websocketSecret = config("app.secretcode", "defaultSecretCode");
            if($websocketSecret==$data['gearmanSecretCode']){
                UserToken::deleteToken($data["token"]);
                return 200;
            }else{
                return 401;
            }
        }
        private function getMassRecords($id)
        {
            // Fetch the reference record, ensuring it was created by an admin
            $reference = ScheduleBuy::where('id', $id)
                ->where('adminCreated', true)
                ->firstOrFail();

            // Find all records created by admins with the same fields (except user_id)
            return ScheduleBuy::where('adminCreated', true)
                ->where('timezone', $reference->timezone)
                ->where('recurring', $reference->recurring)
                ->where('time', $reference->time)
                ->where('server_time', $reference->server_time)
                ->where('date', $reference->date)
                ->where('action_json', $reference->action_json)
                ->where('broker', $reference->broker)
                ->get();
        }
        function editScheduled($id){

    if (!auth()->guest() && auth()->user()->can('browse_admin')) {
        if(request()->query('mass')){
                $records = $this->getMassRecords($id);
                $reference = $records->first();
                return response()->json([
                    'record' => $reference,         // Representative record for editing
                    'count' => $records->count(),   // Number of records affected
                    'mass' => true                  // Flag for frontend to handle mass edit
                ]);
            } else {
                $record = ScheduleBuy::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();
                return response()->json([
                    'record' => $record,
                    'count' => 1,
                    'mass' => false
                ]);
            }
        }
            else{
            return ScheduleBuy::where('id',$id)->where("user_id",Auth::id())->firstOrFail();
            }
        }
        function updateScheduled($id)
        {
            $data = request()->validate([
                'date' => 'required|date',
                'time' => 'required|date_format:H:i',
                'timezone' => 'required|string',
            ]);

            if (!auth()->guest() && auth()->user()->can('browse_admin') && request()->query('mass')) {
                $records = $this->getMassRecords($id);
                $serverTime = Carbon::parse("{$data['date']} {$data['time']}", $data['timezone'])
                    ->setTimezone('UTC')
                    ->format('H:i:s');
                $count = $records->count();
                ScheduleBuy::whereIn('id', $records->pluck('id'))
                    ->update([
                        'date' => $data['date'],
                        'time' => $data['time'],
                        'timezone' => $data['timezone'],
                        'server_time' => $serverTime
                    ]);
                return response()->json(['message' => "Mass updated $count records"], 200);
            } else {
                $update = ScheduleBuy::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();
                $serverTime = Carbon::parse("{$data['date']} {$data['time']}", $data['timezone'])
                    ->setTimezone('UTC')
                    ->format('H:i:s');
                $update->update([
                    'date' => $data['date'],
                    'time' => $data['time'],
                    'timezone' => $data['timezone'],
                    'server_time' => $serverTime
                ]);
                $update->save();
                $update->refresh();
                return response()->json(['message' => 'Event updated successfully'], 200);
            }
        }
        function deleteScheduled($id)
        {
            if (!auth()->guest() && auth()->user()->can('browse_admin') && request()->query('mass')) {
                $records = $this->getMassRecords($id);
                $count = $records->count();
                ScheduleBuy::whereIn('id', $records->pluck('id'))->delete();
                return response()->json(['message' => "Deleted $count records"], 200);
            } else {
                $deleted = ScheduleBuy::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->delete();
                return response()->json(['message' => "Deleted $deleted record(s)"], 200);
            }
        }
    }

