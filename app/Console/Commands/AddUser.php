<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AddUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \DB::table('users')->insert(array (
                0 => 
                array (
                    'role_id' => 3,
                    'name' => 'Wave User',
                    'email' => 'admin@admin.com',
                    'username' => 'admin',
                    'avatar' => 'users/default.png',
                    'password' => '$2y$10$L8MjmjVVOCbyLHbp7pq/9.1ZEEa5AqE67ZXLd2M4.res05a3Rz/G2',
                    'remember_token' => '4oXDVo48Lm1pc4j7NkWI9cMO4hv5OIEJFMrqjSCKQsIwWMGRFYDvNpdioBfo',
                    'settings' => NULL,
                    'created_at' => '2017-11-21 16:07:22',
                    'updated_at' => '2018-09-22 23:34:02',
                    'stripe_id' => NULL,
                    'card_brand' => NULL,
                    'card_last_four' => NULL,
                    'trial_ends_at' => NULL,
                    'verification_code' => NULL,
                    'verified' => 1,
                ),
        ));
    }
}
