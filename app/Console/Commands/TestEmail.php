<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email functionality';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        try {
            Mail::raw('Test email dari Laravel', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email');
            });
            
            $this->info('Email berhasil dikirim ke: ' . $email);
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Gagal mengirim email: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
