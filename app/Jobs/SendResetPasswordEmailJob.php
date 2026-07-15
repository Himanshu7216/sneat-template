<?php

namespace App\Jobs;

use App\Mail\SendEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendResetPasswordEmailJob implements ShouldQueue
{
    use Queueable;

    protected string $email;
    protected string $subject;
    protected array $message;

    /**
     * Create a new job instance.
     */
    public function __construct(string $email, string $subject, array $message)
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)
            ->send(new SendEmail($this->subject, $this->message));
    }
}
