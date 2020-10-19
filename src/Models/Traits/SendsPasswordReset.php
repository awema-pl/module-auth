<?php

namespace AwemaPL\Auth\Models\Traits;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

trait SendsPasswordReset
{
    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        if (!self::setPasswordResetNotification()) {
            $this->notify(new ResetPasswordNotification($token));
        }

    }

    public static function setPasswordResetNotification()
    {
        if ($mailable = config('awemapl-auth.mailables.reset_password')) {

            ResetPasswordNotification::toMailUsing(

                function ($notifiable, $token) use ($mailable) {

                    $url = url(route('password.reset', [
                        'token' => $token,
                        'email' => $notifiable->getEmailForPasswordReset(),
                    ], false));

                    return (new $mailable)
                        ->subject(Lang::get('Reset Password Notification'))
                        ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
                        ->action(Lang::get('Reset Password'), $url)
                        ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
                        ->line(Lang::get('If you did not request a password reset, no further action is required.'))
                        ->markdown('awemapl-auth::email')
                        ->theme('awemapl-auth::mail.themes.default');
                }
            );

            return true;
        }

        return false;
    }
}
