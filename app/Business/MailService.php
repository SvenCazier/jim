<?php
//App/Business/MailService.php

namespace App\Business;

use App\Business\{ErrorService, LoggerService};
use App\Models\Mail;
use PHPMailer\PHPMailer\{Exception, PHPMailer};
use \Throwable;

class MailService
{

    public static function sendContactMail(string $email, string $name, string $subject, string $message): bool
    {
        try {
            return self::sendMail(new Mail($email, $name, ["justiceismine.jim@gmail.com"], $subject, $message, "contact"));
        } catch (Throwable $e) {
            ErrorService::setLoggedError(LoggerService::logError(LoggerService::ERROR, $e->getMessage()));
            return false;
        }
    }


    private static function sendMail(Mail $mailData): bool
    {
        try {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "justiceismine.jim@gmail.com";
            $mail->Password = "ivxq kbxg dmce lxxl";
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom($mailData->getSenderEmail(), $mailData->getSenderName());

            foreach ($mailData->getRecipients() as $recipient) {
                $mail->addAddress($recipient);
            }

            $mail->Subject = $mailData->getSubject();
            $mail->Body = $mailData->getHTMLBody();
            $mail->AltBody = $mailData->getPlainTextBody();

            return $mail->send();
            return true;
        } catch (Throwable $e) {
            throw $e;
            return false;
        }
    }
}
