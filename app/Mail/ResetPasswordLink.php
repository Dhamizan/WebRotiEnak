<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordLink extends Mailable
{
    use Queueable, SerializesModels;

    public $resetLink;
    public $name;

    public function __construct($resetLink, $name)
    {
        $this->resetLink = $resetLink;
        $this->name = $name;
    }

    public function build()
    {
        return $this->subject('Reset Password')
                    ->view('emails.reset_password_link')
                    ->with([
                        'resetLink' => $this->resetLink,
                        'name' => $this->name,
                    ]);
    }
}
