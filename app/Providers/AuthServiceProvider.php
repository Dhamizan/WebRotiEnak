<?php

namespace App\Providers;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            $frontendUrl = config('app.url') . '/api/verify-email?email=' . urlencode($notifiable->email) . '&signature=' . sha1($notifiable->getEmailForVerification());

            return (new MailMessage)
                ->subject('Verifikasi Email')
                ->line('Klik tombol di bawah untuk memverifikasi email Anda.')
                ->action('Verifikasi Email', $frontendUrl)
                ->line('Jika Anda tidak meminta verifikasi, abaikan email ini.');
        });
    }
}
