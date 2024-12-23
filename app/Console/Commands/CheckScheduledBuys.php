<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ScheduleBuy;
use Carbon\Carbon; // Import the Carbon class
use App\Http\Controllers\GearmanClientController;
use Auth;

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = Carbon::now('UTC');
        $oneMinuteFromNow = $now->copy()->addMinute();
        
        // Fetch scheduled buys where server_time is within the current minute in UTC
        $scheduledBuys = ScheduleBuy::whereDate('date', $now->toDateString())
            ->whereTime('server_time', '>=', $now->format('H:i:s'))
            ->whereTime('server_time', '<', $oneMinuteFromNow->format('H:i:s'))
            ->get();
        
        $actions = [];
        foreach ($scheduledBuys as $buy) {
            $result = $gearmanController->sendTasksToWorkerTwo(json_decode($buy->action_json),TRUE);
            $this->info(json_encode(['message' => $result]));
        }
    }
}
