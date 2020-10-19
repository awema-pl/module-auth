<?php

namespace AwemaPL\Auth\Models\Traits;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Notifications\VerifyEmail;

trait SendsEmailVerification
{
    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        if (!self::setSendEmailVerificationNotification()) {
            $this->notify(new VerifyEmail);
        }
    }

    public static function setSendEmailVerificationNotification()
    {
        if ($mailable = config('awemapl-auth.mailables.email_verification')) {
            VerifyEmail::toMailUsing(
                function ($notifiable) use ($mailable) {

                    $verificationUrl = URL::temporarySignedRoute(
                        'verification.verify',
                        Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                        [
                            'id' => $notifiable->getKey(),
                            'hash' => sha1($notifiable->getEmailForVerification()),
                        ]
                    );
                    return (new $mailable)
                        ->subject(Lang::get('Verify Email Address'))
                        ->line(Lang::get('Please click the button below to verify your email address.'))
                        ->action(Lang::get('Verify Email Address'), $verificationUrl)
                        ->line(Lang::get('If you did not create an account, no further action is required.'))
                        ->markdown('awemapl-auth::email')
                        ->theme('awemapl-auth::mail.themes.default');;
                }
            );
            return true;
        }
        return false;
    }
}
