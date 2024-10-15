<?php

namespace App\Console\Commands;

use App\Http\Controllers\GearmanClientController;
use App\Http\Controllers\UserBackendController;
use App\Models\Broker;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class autoUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically update accounts and holdings for all enabled confirmed brokers for each user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
            Log::info("autoUpdate process started...");
            $users = User::all();
            // Loop over each user
            foreach ($users as $user) {
                // Retrieve all enabled brokers for the user
                $enabledBrokers = Broker::where('user_id', $user->id)
                                        ->where('enabled', 1)
                                        ->where('confirmed', 1)
                                        ->get();
                $records=[];
                // Loop over each enabled broker
                foreach ($enabledBrokers as $broker) {
                    try {
                        // Call the do_action function for "get_accounts"
                        $records[]=$this->callDoAction($user, $broker, 'get_accounts');
                        // Call the do_action function for "holdings"
                        $records[]=$this->callDoAction($user, $broker, 'holdings');

                    } catch (\Exception $e) {
                        Log::error("Error processing broker {$broker->broker_name} for user {$user->id}: " . $e->getMessage());
                    }
                }
                (new GearmanClientController())->sendTasksToWorkerTwo($records);
            }
            Log::info("autoUpdate process completed. Sleeping for 24 hours...");
    }

    /**
     * Helper function to call do_action with specific parameters.
     */
    private function callDoAction($user, $broker, $action)
    {
        $request = new \Illuminate\Http\Request();
        $request->merge([
            'broker' => $broker->broker_name,
            'action' => $action,
        ]);

        // Optional: Set any additional request parameters as needed

        // Create an instance of the controller and call the function
        $controller = new UserBackendController(); // Replace with actual controller instance
        return $controller->do_action($request, $user);
    }
}
