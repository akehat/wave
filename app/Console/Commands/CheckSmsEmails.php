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
                    $message = $this->strip_tags_content(imap_fetchbody($inbox, $email_number, 1));
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
                        $code=$this->getCode($message);
                        if ($code) {
                            $affected = PendingSms::where('user_id', $user->id)
                                ->whereNull('code')
                                ->where('expires_at', '>', now())
                                ->update(['code' => $code]);
                            
                            if ($affected) {
                                $this->info("Updated SMS code for user: {$username}");
                                $this->notifyGearman($user, $code, $affected); // Notify user via the route
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

    private function notifyGearman($user, $code, $affected)
    {
        $brokerAndUsername =  $affected->broker  . "_" . $user->id. "_" . $affected->for  ; // Example construction, adjust as needed
        
        // Directly call the Gearman function
        $result = GearmanClientController::sendTaskToTwoFactor($brokerAndUsername, $code, $user);
        if ($result) {
            $this->info("Successfully notified Gearman for code: {$code}");
        } else {
            $this->error("Failed to notify Gearman for code: {$code}");
        }
    }
    function strip_tags_content($string) { 
        // ----- remove HTML TAGs ----- 
        $string = preg_replace ('/<[^>]*>/', ' ', $string); 
        // ----- remove control characters ----- 
        $string = str_replace("\r", '', $string);
        $string = str_replace("\n", ' ', $string);
        $string = str_replace("\t", ' ', $string);
        // ----- remove multiple spaces ----- 
        $string = trim(preg_replace('/ {2,}/', ' ', $string));
        return $string; 
    
    }
    function getCode($message){
        $code = null;
        $brokerFound = false;
        if (preg_match('/verification code, (\d{6}),/', $message, $matches)) {
            $code = $matches[1];
            $brokerFound = true; // BBAE or dSPAC
        } 
        elseif (preg_match('/code: \*(\d{6})\*/', $message, $matches)) {
            $code = $matches[1];
            $brokerFound = true; // fennel
        } 
        elseif (preg_match('/Schwab (\d{6})/', $message, $matches)) {
            $code = $matches[1];
            $brokerFound = true; // Schwab
        } 
        elseif (preg_match('/Chase: DON\'T share\. Use code\s*(\d{8})\s*to confirm you\'re signing in/', $message, $matches)) {
            $code = $matches[1];
            $brokerFound = true; // Chase
        } 
        elseif (preg_match('/verification code is (\d{6})/', $message, $matches)) {
            $code = $matches[1];
            $brokerFound = true; // Webull
        } 
        elseif (preg_match('/Merrill: Your authorization code is\s*(\d{6})\s*\. It expires in 10 minutes/', $message, $matches)) {
            $code = $matches[1];
            $brokerFound = true; // Merrill
        } 
        elseif (preg_match('/verification code: (\d{6})/', $message, $matches)) {
            $code = $matches[1];
            $brokerFound = true; // Public
        } 
        elseif (preg_match('/Code (\d{6})/', $message, $matches)) {
            $code = $matches[1];
            $brokerFound = true; // Robinhood
        } 
        if($brokerFound){
            return $code;
        }
        return false;
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
                    $message = $this->strip_tags_content(imap_fetchbody($inbox, $email_number, 1)); 
                    
                    $header = imap_headerinfo($inbox, $email_number);
                    $from = $header->from[0]->mailbox . '@' . $header->from[0]->host;
                    $to = $header->to[0]->mailbox . '@' . $header->to[0]->host;
                    // $to = $header->toaddress;
                    $this->info("From: {$from}, To: {$to}");
                    
                    if($from=="byersolomon@gmail.com"){
                        // $this->info($message);
                    }
                    $code=$this->getCode($message);
                    if($code){
                        imap_delete($inbox, $email_number); // Mark for deletion
                        $this->info("code:".$code);
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
}