<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GearmanClient;
use Illuminate\Support\Facades\Log;

class TestGearmanCommand extends Command
{
    protected $signature = 'test:gearman';
    protected $description = 'Send a test command to the Gearman worker';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Create a Gearman client
        $client = new \GearmanClient();
        $client->addServer('localhost', 4730);

        // Load environment variables
        $tradierToken = env('TRADIER', 'SANDBOXBYLlMdC5Y1ZtGMjFEv341FHpdPAu');

        // Define the test command with environment variables
        $testCommand = [
            'env' => [
                'TRADIER' => $tradierToken,
            ],
            'args' => ['HOLDINGS', 'tradier', 'false']  // Example command to buy 1 share of AAPL using Tradier
        ];

        // JSON encode the command
        $testCommandJson = json_encode($testCommand);

        try {
            // Send the task to the Gearman worker
            $result = $client->doNormal('execute_command', $testCommandJson);

            // Log the result and output it to the console
            Log::info('Gearman Worker Response:', ['response' => $result]);
            $this->info('Gearman Worker Response: ' . $result);
        } catch (\Exception $e) {
            Log::error('Gearman Worker Error:', ['error' => $e->getMessage()]);
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
