<?php

namespace App\Http\Controllers;
use App\Models\Broker;
use App\Models\UserToken;
use App\Models\Account;
use App\Models\Stock;
use App\Models\BroadcastMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GearmanClientController;
use App\Models\ArchivedAccount;
use App\Models\ArchivedStock;
use App\Models\Email;
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
                'webull_trading_pin' => 'nullable|string',
            ]);

            // Get the authenticated user
            $user = auth()->user();

            // Store broker data for Tradier
            Broker::updateOrCreate(
                ['user_id' => $user->id, 'broker_name' => 'Tradier'],
                [
                    'enabled' => $request->tradier_enabled!="on"?0:1,
                    'username' => $request->tradier_username,
                    'password' => $request->tradier_password,
                    'token' => $request->tradier_token,
                ]
            );

            // Store broker data for Robinhood
            Broker::updateOrCreate(
                ['user_id' => $user->id, 'broker_name' => 'Robinhood'],
                [
                    'enabled' => $request->robinhood_enabled!="on"?0:1,
                    'username' => $request->robinhood_username,
                    'password' => $request->robinhood_password,
                ]
            );

            // Store broker data for Schwab
            Broker::updateOrCreate(
                ['user_id' => $user->id, 'broker_name' => 'Schwab'],
                [
                    'enabled' => $request->schwab_enabled!="on"?0:1,
                    'username' => $request->schwab_username,
                    'password' => $request->schwab_password,
                    'totp' => $request->schwab_totp,
                ]
            );

            // Store broker data for Fidelity
            Broker::updateOrCreate(
                ['user_id' => $user->id, 'broker_name' => 'Fidelity'],
                [
                    'enabled' => $request->fidelity_enabled!="on"?0:1,
                    'username' => $request->fidelity_username,
                    'password' => $request->fidelity_password,
                ]
            );

            // Store broker data for Fennel
            Broker::updateOrCreate(
                ['user_id' => $user->id, 'broker_name' => 'Fennel'],
                [
                    'enabled' => $request->fennel_enabled!="on"?0:1,
                    'email' => $request->fennel_email,
                ]
            );

            // Store broker data for Firstrade
            Broker::updateOrCreate(
                ['user_id' => $user->id, 'broker_name' => 'Firstrade'],
                [
                    'enabled' => $request->firstrade_enabled!="on"?0:1,
                    'username' => $request->firstrade_username,
                    'password' => $request->firstrade_password,
                    'pin' => $request->firstrade_pin,
                ]
            );

            // Store broker data for Public
            Broker::updateOrCreate(
                ['user_id' => $user->id, 'broker_name' => 'Public'],
                [
                    'enabled' => $request->public_enabled!="on"?0:1,
                    'username' => $request->public_username,
                    'password' => $request->public_password,
                ]
            );

            // Store broker data for Tastytrade
            Broker::updateOrCreate(
                ['user_id' => $user->id, 'broker_name' => 'Tastytrade'],
                [
                    'enabled' => $request->tastytrade_enabled!="on"?0:1,
                    'username' => $request->tastytrade_username,
                    'password' => $request->tastytrade_password,
                ]
            );

            // Store broker data for Vanguard
            Broker::updateOrCreate(
                ['user_id' => $user->id, 'broker_name' => 'Vanguard'],
                [
                    'enabled' => $request->vanguard_enabled!="on"?0:1,
                    'username' => $request->vanguard_username,
                    'password' => $request->vanguard_password,
                    'phone_last_four' => $request->vanguard_phone_last_four,
                ]
            );

            // Store broker data for Webull
            Broker::updateOrCreate(
                ['user_id' => $user->id, 'broker_name' => 'Webull'],
                [
                    'enabled' => $request->webull_enabled!="on"?0:1,
                    'username' => $request->webull_username,
                    'password' => $request->webull_password,
                    'did' => $request->webull_did,
                    'pin' => $request->webull_trading_pin,
                ]
            );

            Broker::updateOrCreate(
                ['user_id' => $user->id, 'broker_name' => 'Tornado'],
                [
                    'enabled' => $request->tornado_enabled!="on"?0:1,
                    'username' => $request->tornado_username,
                    'password' => $request->tornado_password,
                ]
            );
            Broker::updateOrCreate(
                ['user_id' => $user->id, 'broker_name' => 'DSPAC'],
                [
                    'enabled' => $request->DSPAC_enabled!="on"?0:1,
                    'username' => $request->DSPAC_username,
                    'password' => $request->DSPAC_password,
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
                    optional($dspac)->username, // Tornado
                    optional($dspac)->password, // Tornado
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
                return (new GearmanClientController())->setDataRecord($broker,$creds,$action,$request->input('symbol'),$request->input('quantity'),$request->input('price')??null,endpoint:$endpoint,userToker:$user->id,onAccounts:$onAccounts,user:$user);
            }
            $result = (new GearmanClientController())->sendTaskToWorkerTwo($broker,$creds,$action,$request->input('symbol'),$request->input('quantity'),$request->input('price')??null,endpoint:$endpoint,userToker:$user->id,onAccounts:$onAccounts);

            // Return the result
            return response()->json($result);
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
        function getUserData(){
            $userId=Auth::id();
            $accounts = Account::where('user_id', $userId)->get();
            $stocks = Stock::where('user_id', $userId)->get();
            // Return as JSON
            return response()->json([
                'accounts' => $accounts,
                'stocks' => $stocks
            ]);
        }
}
