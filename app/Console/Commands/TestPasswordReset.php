<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestPasswordReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:password-reset {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test password reset functionality';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        try {
            $status = \Illuminate\Support\Facades\Password::sendResetLink(['email' => $email]);
            
            $this->info('Password reset status: ' . $status);
            
            switch ($status) {
                case \Illuminate\Support\Facades\Password::RESET_LINK_SENT:
                    $this->info('✅ Link reset password berhasil dikirim!');
                    break;
                case \Illuminate\Support\Facades\Password::INVALID_USER:
                    $this->error('❌ Email tidak ditemukan');
                    break;
                case \Illuminate\Support\Facades\Password::RESET_THROTTLED:
                    $this->error('❌ Terlalu banyak permintaan, silakan tunggu');
                    break;
                default:
                    $this->error('❌ Status tidak dikenal: ' . $status);
            }
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Exception: ' . $e->getMessage());
            $this->error('Trace: ' . $e->getTraceAsString());
            return Command::FAILURE;
        }
    }
}
