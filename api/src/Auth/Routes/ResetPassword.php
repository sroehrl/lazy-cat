<?php

namespace App\Auth\Routes;

use App\Auth\Error\Api400;
use App\Auth\Support\GenerateJWT;
use App\Mail\Services\Mail;
use App\User\Models\User;
use Neoan\Helper\Env;
use Neoan\Request\Request;
use Neoan\Routing\Attributes\Post;
use Neoan\Routing\Interfaces\Routable;

#[Post('/api/auth/reset-password')]
class ResetPassword implements Routable
{
    public function __invoke(Mail $mail): array|Api400
    {
        $user = User::retrieveOne(['email' => Request::getInput('email')]);

        if (!$user) {
            sleep(1);
            return ['msg' => 'maybe'];
        }
        // flow separation
        if (Request::getInput('code')) {
            if (Request::getInput('code') !== md5($user->updatedAt->value) || $user->updatedAt->stamp < time() - 3600) {
                return new Api400('Invalid code');
            }

            $user->password = Request::getInput('password');
            $user->store();
            return [...GenerateJWT::generate($user), 'admin' => $user->isAdmin];
        } else {
            // store user
            $user->store();

            $name = $user->member()?->name() ?? 'Member';

            $introText = 'You have requested a password reset. Please follow the button below to set your new password.';
            $introText .= '<br><br>';
            $introText .= 'If you did not request a password reset, please ignore this email.';
            $introText .= '<br><br>';
            $introText .= 'The link will expire in 1 hour. ';

            $mail->setTemplate((int) Env::get('MAILJET_BASE_TEMPLATE'));
            $mail->setSubject('Reset your Lazy Cat Cafe password');
            $mail->addRecipient($user->email, $name);
            $mail->setVariables([
                'salutation' => 'Dear ' . $name . ',',
                'introText' => $introText,
                'buttonText' => 'Reset Password',
                'link' => Env::get('APP_URL') . '/auth/forgot-password?code=' . md5($user->updatedAt->value) . '&email=' . $user->email,
            ]);

            try {
                $mail->send();
            } catch (\Exception $e) {

                return ['msg' => 'error sending'];
            }

            return ['msg' => 'maybe'];
        }




    }
}