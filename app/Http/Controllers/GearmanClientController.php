<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GearmanClient;

class GearmanClientController extends Controller
{
    public static function prepareEnvContent(
        $discord_token = null,
        $discord_channel = null,
        $danger_mode = "false",
        $chase_username = null,
        $chase_password = null,
        $chase_phone_last_four = null,
        $chase_debug = null,
        $fennel_email = null,
        $fidelity_username = null,
        $fidelity_password = null,
        $firstrade_username = null,
        $firstrade_password = null,
        $firstrade_pin = null,
        $public_username = null,
        $public_password = null,
        $robinhood_username = null,
        $robinhood_password = null,
        $robinhood_totp = null,
        $schwab_username = null,
        $schwab_password = null,
        $schwab_totp_secret = null,
        $tradier_access_token = null,
        $tastytrade_username = null,
        $tastytrade_password = null,
        $vanguard_username = null,
        $vanguard_password = null,
        $vanguard_phone_last_four = null,
        $vanguard_debug = null,
        $webull_username = null,
        $webull_password = null,
        $webull_did = null,
        $webull_trading_pin = null
    ) {
        $envArray = [
            'DISCORD_TOKEN' => $discord_token,
            'DISCORD_CHANNEL' => $discord_channel,
            'DANGER_MODE' => $danger_mode,
            'CHASE' => $chase_username && $chase_password ? implode(':', array_filter([$chase_username, $chase_password, $chase_phone_last_four, $chase_debug])) : null,
            'FENNEL' => $fennel_email,
            'FIDELITY' => $fidelity_username && $fidelity_password ? implode(':', array_filter([$fidelity_username, $fidelity_password])) : null,
            'FIRSTRADE' => $firstrade_username && $firstrade_password && $firstrade_pin ? implode(':', array_filter([$firstrade_username, $firstrade_password, $firstrade_pin])) : null,
            'PUBLIC_BROKER' => $public_username && $public_password ? implode(':', array_filter([$public_username, $public_password])) : null,
            'ROBINHOOD' => $robinhood_username && $robinhood_password ? implode(':', array_filter([$robinhood_username, $robinhood_password, $robinhood_totp])) : null,
            'SCHWAB' => $schwab_username && $schwab_password ? implode(':', array_filter([$schwab_username, $schwab_password, $schwab_totp_secret])) : null,
            'TRADIER' => $tradier_access_token,
            'TASTYTRADE' => $tastytrade_username && $tastytrade_password ? implode(':', array_filter([$tastytrade_username, $tastytrade_password])) : null,
            'VANGUARD' => $vanguard_username && $vanguard_password ? implode(':', array_filter([$vanguard_username, $vanguard_password, $vanguard_phone_last_four, $vanguard_debug])) : null,
            'WEBULL' => $webull_username && $webull_password ? implode(':', array_filter([$webull_username, $webull_password, $webull_did, $webull_trading_pin])) : null,
        ];
    
        // Filter out any null values
        $envArray = array_filter($envArray, function ($value) {
            return !is_null($value);
        });
    
        return $envArray;
    }
    /**
     * Static function to generate a command string based on the given parameters.
     * 
     * @param string $prefix The prefix for the command (e.g., !rsa or python autoRSA.py)
     * @param string $action The action to perform ("buy", "sell", "holdings", etc.)
     * @param string|int $amount The amount of stocks to buy or sell (can be null for some actions like "holdings")
     * @param string $ticker The stock ticker(s) to buy or sell, comma-separated with no spaces (e.g., "AAPL,GOOG")
     * @param string $accounts The brokerages to run the command in, comma-separated with no spaces (e.g., "robinhood,schwab,all")
     * @param string $not_accounts Brokerages to exclude, comma-separated with no spaces, prefixed with "not" (e.g., "not schwab,vanguard")
     * @param string|bool $dry Whether to run in dry mode (true/false, or "dry" for true)
     * 
     * @return string The generated command string
     */
    public static function generateCommand(
        $prefix,
        $action,
        $amount = null,
        $ticker = null,
        $accounts = null,
        $not_accounts = null,
        $dry = true
    ) {
        // Start building the command string
        $command = $prefix . ' ' . $action;

        // Append amount and ticker if provided
        if ($amount !== null) {
            $command .= ' ' . $amount;
        }

        if ($ticker !== null) {
            $command .= ' ' . $ticker;
        }

        // Append accounts if provided
        if ($accounts !== null) {
            $command .= ' ' . $accounts;
        }

        // Append not accounts if provided
        if ($not_accounts !== null) {
            $command .= ' not ' . $not_accounts;
        }

        // Append dry mode if set
        if ($dry !== null) {
            $command .= ' ' . ($dry === true || $dry === 'dry' ? 'dry' : 'false');
        }

        return $command;
    }
    /**
     * Static function to generate a command string based on the given parameters.
     * 
     * @param string $command generateCommand()
     * @param array $broker_data prepareEnvContent()
     * @param string $extra_params N/A
     * 
     * @return json The result
     */
    public static function sendTaskToWorker($command,$broker_data,$params=null)
    {

        // Prepare data to send to the worker
        $taskData = [
            'args' => $command,
            'env' => $broker_data,
            'params' => $params ?? [],
        ];

        // JSON encode the task data
        $taskDataJson = json_encode($taskData);

        // Initialize Gearman client
        $client = new GearmanClient();
        $client->addServer(); // Add the default server (localhost)

        // Send the task to the Gearman worker and wait for the result
        $result = $client->doNormal('execute_command', $taskDataJson);

        // Decode the JSON result
        $resultData = json_decode($result, true);

        // Return the result as a JSON response
        return json_encode([
            'status' => 'success',
            'data' => $resultData,
        ]);
    }
    
}
