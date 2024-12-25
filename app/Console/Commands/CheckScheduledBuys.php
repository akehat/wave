<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ScheduleBuy;
use Carbon\Carbon;
use App\Http\Controllers\GearmanClientController;
use Illuminate\Support\Facades\Cache;
use Core_Daemon;

class CheckScheduledBuys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'buy:check-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for scheduled buy actions and execute them';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command using a PHP-Daemon class.
     *
     * @return mixed
     */
    public function handle()
    {
        $daemon = new class extends Core_Daemon {
            public function __construct()
            {
                parent::__construct();
            }
            /**
             * Daemon setup logic.
             */
            protected function setup()
            {
                $this->log("Daemon setup complete. Ready to start execution.");
            }

            /**
             * Main execution logic for the daemon.
             */
            protected function execute()
            {
                error_log('Some message here.');
                $now = Carbon::now('UTC');
                $oneMinuteFromNow = $now->copy()->addMinute();

                // Fetch all cached scheduled buys
                $allScheduledBuys = Cache::get('all_schedule_buys', null);
                if ($allScheduledBuys == null) {
                    ScheduleBuy::cacheAll();
                    $allScheduledBuys = Cache::get('all_schedule_buys', []);
                }

                // Filter the ones that match the criteria for execution
                $scheduledBuys = collect($allScheduledBuys)
                    ->filter(function ($buy) use ($now, $oneMinuteFromNow) {
                        return $buy['date'] === $now->toDateString() &&
                            $buy['server_time'] >= $now->format('H:i:s') &&
                            $buy['server_time'] < $oneMinuteFromNow->format('H:i:s');
                    });

                $deleted = false;

                // Execute scheduled buys and delete them from the DB
                foreach ($scheduledBuys as $buy) {
                    $gearmanController = new GearmanClientController();
                    $result = $gearmanController->sendTasksToWorkerTwo(json_decode($buy['action_json']), true);
                    $this->log("Scheduled buy executed: " . json_encode(['message' => $result]));

                    // Delete the buy after processing
                    ScheduleBuy::where('id', $buy['id'])->delete();
                    $deleted = true;
                }

                // Refresh the cache with updated data if entries were deleted
                if ($deleted) {
                    ScheduleBuy::cacheAll();
                }
            }

            /**
             * Set the interval for the daemon loop (55 seconds).
             *
             * @return int
             */
            protected function run_interval()
            {
                return 55; // 55 seconds
            }
            protected $loop_interval = 55;
            /**
             * Specify the log file location.
             *
             * @return string
             */
            protected function log_file()
            {
                return storage_path('logs/daemon.log'); // Set log file path
            }
        };

        // Run the daemon
        try {
            $daemon->run();
            $this->info("Daemon working: ");

        } catch (\Exception $e) {
            $this->error("Daemon Error: " . $e->getMessage());
        }
    }
}
