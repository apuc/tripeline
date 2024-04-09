<?php

namespace App\Helper;
use Mail;

class EmailHelper
{
    public static function sendEmailFromRegPartner($data)
    {
        $to_emails = ['partner@mytripline.com']; // partner@mytripline.com
        $subject = 'Регистрация нового пользователя - '. $data['name'];

        Mail::send('emails.request', [ 'data_send' => $data ], function($message) use ($to_emails, $subject) {
            $message->to($to_emails)->subject($subject);
            $message->from('notification@mytripline.com');
        });
    }

    public static function sendEmailFromRegDriver($data, $to_emails)
    {
        $subject = 'Congratulations, your profile is approved';

        Mail::send('emails.reg_driver', [ 'data_send' => $data ], function($message) use ($to_emails, $subject) {
            $message->to($to_emails)->subject($subject);
            $message->from('notification@mytripline.com');
        });
    }
}
