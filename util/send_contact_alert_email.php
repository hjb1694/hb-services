<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendContactInquiryAlertEmail($submitter_email, $submitter_full_name, $submitter_message) {

    try{

        $sender = $_ENV['CONTACT_ALERT_FROM_EMAIL'];
        $sender_name = "Automatic Sender";
        $SMTP_username = $_ENV['AMAZON_SMTP_USERNAME'];
        $SMTP_password = $_ENV['AMAZON_SMTP_PASSWORD'];
        $host = 'email-smtp.us-east-2.amazonaws.com';
        $port = 587;
        $to_email = $_ENV['CONTACT_ALERT_TO_EMAIL'];
        $cc_email = $_ENV['CONTACT_ALERT_CC_EMAIL'];
        $subject = "New Contact Inquiry Submission";

        $html_body = "<p>You have received the following contact inquiry for Hayden Bradfield Web Services:</p><table style=\"margin-top:16px;border-collapse:collapse;border:1px solid #333;\"><tbody><tr><td style=\"border:1px solid #333;padding:10px;\">Full Name</td><td style=\"border: 1px solid #333;padding:10px;\">$submitter_full_name</td></tr><tr><td style=\"border:1px solid #333;padding:10px;\">Email Address</td><td style=\"border:1px solid #333;padding:10px;\">$submitter_email</td></tr><tr><td style=\"border:1px solid #333;padding:10px;\">Message Body</td><td style=\"border:1px solid #333;padding:10px;\">$submitter_message</td></tr></tbody></table>";

        $mail = new PHPMailer(true);

        $mail->SMTPDebug = 2;

        $mail->isSMTP();
        $mail->setFrom($sender, $sender_name);
        $mail->Username   = $SMTP_username;
        $mail->Password   = $SMTP_password;
        $mail->Host       = $host;
        $mail->Port       = $port;
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = 'tls';

        $mail->addAddress($to_email);
        $mail->addCC($cc_email);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $html_body;

        $mail->Send();

    }catch(phpMailerException $e){
        
    }catch(Exception $e){
        
    }

}


?>