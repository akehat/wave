<?php

namespace App\Console\Commands;

use App\Models\PendingSms;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\GearmanClientController;

class CheckSmsEmails extends Command
{
    protected $signature = 'check:sms-emails {--test}';
    
    protected $description = 'Check Gmail for SMS codes, update PendingSms records, and notify via route';

    public function handle()
    {

        if ($this->option('test')) {
            return $this->testEmails();
        }
        $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
        $username = config('services.imap.email');
        $password = config('services.imap.password');

        try {
            $inbox = imap_open($hostname, $username, $password);
            if ($inbox === false) {
                throw new \Exception("Cannot connect to Gmail: " . imap_last_error());
            }

            $emails = imap_search($inbox, 'ALL');
            if ($emails) {
                rsort($emails);  // Sort emails with latest first
                $date = strtotime($header->date); // Convert email date to timestamp
                PendingSms::where('expires_at', '<', now())->delete();
                // Check if the email is older than 3 minutes
                foreach ($emails as $email_number) {
                    $message = imap_fetchbody($inbox, $email_number, 1); 
                    $header = imap_headerinfo($inbox, $email_number);
                    $from = $header->from[0]->mailbox . '@' . $header->from[0]->host;
                    $to = $header->to[0]->mailbox . '@' . $header->to[0]->host;
                    $date = strtotime($header->date); 
                    if (time() - $date > 180) { 
                        imap_delete($inbox, $email_number); 
                        continue; 
                    }
                    $override = false;
                    $username = null;
                    $emailCode = null;
                    $user = \App\Models\User::where('email', $to)->first();
                    if($user){
                        $override=true;
                    }else{
                        preg_match('/^\+(?<username>[^.]+)\.(?<email_code>[^@]+)@gmail\.com$/', $to, $matches);
                    
                        if (!isset($matches['username']) || !isset($matches['email_code'])) {
                            $this->warn("Email format not recognized: " . $header->toaddress);
                            continue;
                        }
                        $username = $matches['username'];
                        $emailCode = $matches['email_code'];
                        $user = \App\Models\User::where('username', $username)->first();
                    }
                 
                    if ($override || ($user && $user->profile()->exists() && $user->profile->email_code === $emailCode)) {
                        if (preg_match('/\b\d{6}\b/', $message, $codeMatches)) {
                            $code = $codeMatches[0];
                            
                            $affected = PendingSms::where('user_id', $user->id)
                                ->whereNull('code')
                                ->where('expires_at', '>', now())
                                ->update(['code' => $code]);
                            
                            if ($affected) {
                                $this->info("Updated SMS code for user: {$username}");
                                $this->notifyGearman($user, $code); // Notify user via the route
                                imap_delete($inbox, $email_number); // Mark for deletion
                            } else {
                                $this->warn("No matching PendingSms record found for user: {$username}");
                            }
                        } else {
                            $this->warn("No SMS code found in email for user: {$username}");
                        }
                    } else {
                        $this->warn("User or profile not found or email code mismatch for: {$username}");
                    }
                }
            } else {
                $this->info("No emails found in the inbox.");
            }

            if (!imap_expunge($inbox)) {
                throw new \Exception("Failed to remove emails: " . imap_last_error());
            }
            imap_close($inbox);
        } catch (\Exception $e) {
            Log::error("IMAP Error: " . $e->getMessage());
            $this->error("An error occurred: " . $e->getMessage());
        }
    }

    private function notifyGearman($user, $code)
    {
        $brokerAndUsername = 'email_' . $user->id . '_' . $user->username; // Example construction, adjust as needed
        
        // Directly call the Gearman function
        $result = GearmanClientController::sendTaskToTwoFactor($brokerAndUsername, $code, $user);
        if ($result) {
            $this->info("Successfully notified Gearman for code: {$code}");
        } else {
            $this->error("Failed to notify Gearman for code: {$code}");
        }
    }

    private function testEmails()
    {
        $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
        $username = config('services.imap.email');
        $password = config('services.imap.password');
        $this->info($username);
        $this->info($password);
        try {
            $inbox = imap_open($hostname, $username, $password);
            if ($inbox === false) {
                throw new \Exception("Cannot connect to Gmail: " . imap_last_error());
            }

            $emails = imap_search($inbox, 'ALL');
            if ($emails) {
                rsort($emails);  // Sort emails with latest first
                foreach ($emails as $email_number) {
                    $header = imap_headerinfo($inbox, $email_number);
                    $from = $header->from[0]->mailbox . '@' . $header->from[0]->host;
                    $to = $header->to[0]->mailbox . '@' . $header->to[0]->host;
                    // $to = $header->toaddress;
                    $this->info("From: {$from}, To: {$to}");
                }
            } else {
                $this->info("No emails found in the inbox.");
            }

            imap_close($inbox);
        } catch (\Exception $e) {
            Log::error("IMAP Error: " . $e->getMessage());
            $this->error("An error occurred: " . $e->getMessage());
        }
    }
}