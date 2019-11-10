<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use Pheanstalk\Pheanstalk;

// Load Composer's autoloader
require 'vendor/autoload.php';


// Create using autodetection of socket implementation
$pheanstalk = Pheanstalk::create('127.0.0.1');


while (1) {

    $job = $pheanstalk
        ->watch('mcq_mails')
        ->ignore('default')
        ->reserve();

    if (!$job) {
        echo "Waiting for job";
        continue;
    }

    echo "Got job " . $job->getData();
    $contestant = json_decode($job->getData(), true);

    $collegeName = $contestant['college_name'];

    $mail = new PHPMailer(true);

    try {

        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host = 'smtp.mailgun.org';                    // Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   // Enable SMTP authentication
        $mail->Username = '';                     // SMTP username
        $mail->Password = '';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port = 587;                                    // TCP port to connect to


        $email = $contestant['email'];
        $token = $contestant['token'];
        //Recipients
        $mail->setFrom('no-reply@practo.net', 'Practo MCQ');
        $mail->addAddress($email);               // Name is optional
        $mail->addReplyTo('no-reply@practo.net', 'Practo');


        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Practo MCQ Test - ' . $collegeName;


        $mailBody = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> <html> <body style="margin-top:0 !important;margin-bottom:0 !important;margin-right:0 !important;margin-left:0 !important;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;background-color:#fff;font-size:14px;font-family:sans-serif;line-height:20px;"> <center style="width:100%;table-layout:fixed;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;"> <div style="max-width:750px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto;background-color:#28328c;"> <!-- Header1--> <!--[if (gte mso 9)|(IE)]> <table width="650" align="center" style="border-spacing:0 !important;color:#414146;"> <tr> <td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;"> <![endif]--> <table align="center" style="border-spacing:0 !important;color:#414146;Margin:0 auto;width:100%;max-width:750px;"> <tr> <td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;"> <table width="100%" style="border-spacing:0 !important;color:#414146;"> <tr> <td style="padding-top:30px;padding-bottom:20px;padding-right:20px;padding-left:20px;text-align:left;"> <img src="https://www.practo.com/bundles/practopractoapp/images/mailers/logo_supply.png" width="129" alt="•practo•" style="border-width:0;margin-bottom:30px;"/> <p style="Margin:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;font-size:22px;font-weight:bold;line-height:1.5;Margin-bottom:10px;color:#eee;"> Your Practo MCQ Test invitation token  {{mcq_token}}</p></td> </tr> </table> </td> </tr> </table> <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]--> </div> <div style="max-width:750px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto;background-color:#f5f5fa;"> <!--[if (gte mso 9)|(IE)]> <table width="650" align="center" style="border-spacing:0 !important;color:#414146;"> <tr> <td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;"> <![endif]--> <table align="center" style="border-spacing:0 !important;color:#414146;Margin:0 auto;width:100%;max-width:750px;"> <tr> <!-- Body --> <td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;"> <table width="100%" style="border-spacing:0 !important;color:#414146;"> <tr> <td style="padding-top:30px;padding-bottom:20px;padding-right:20px;padding-left:20px;text-align:left;"> <p style="Margin:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;margin-bottom:20px;"> Hello, </p> <p style="Margin:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;margin-bottom:20px;"> Email <b> {{mcq_email}} </b></p> <p style="Margin:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;margin-bottom:20px;"> Token <b> {{mcq_token}} </b></p> <a href="https://mcq.practodev.com/signup" target="_blank" style="color: #14bef0; text-decoration: none; font-weight:bold;"> <div style="margin-left: 20px; margin-top: 20px;height: 40px;border-radius: 2px;background-color: #ffa000;width:100px;"> <p style=" height: 20px;font-family: \'Roboto\', sans-serif;font-size: 14px;font-weight: bold;font-style: normal;font-stretch: normal;line-height: 1.43;letter-spacing: -0.3px;color: #ffffff;text-align: center;padding: 10px;">Start Test</p> </div> </a> <p><br></p> <p style="Margin:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;margin-bottom:5px;"> Warm regards, </p> <p style="Margin:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;margin-bottom:20px;"> Team Practo </p></td> </tr> </table> </td> </tr> </table> <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]--> </div> <div style="max-width:750px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto;background-color:#fff;"> <!-- App Download Banner--> </div> <div style="max-width:750px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto;background-color:#f5f5fa;"> <!-- Footer1--> <!--[if (gte mso 9)|(IE)]> <table width="650" align="center" style="border-spacing:0 !important;color:#414146;"> <tr> <td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;"> <![endif]--> <table align="center" style="border-spacing:0 !important;color:#414146;Margin:0 auto;width:100%;max-width:750px;"> <tr> <td style="font-size:0;padding-top:20px;padding-bottom:20px;padding-right:0;padding-left:0;"> <!--[if (gte mso 9)|(IE)]> <table width="100%" style="border-spacing:0 !important;color:#414146;"> <tr> <td width="445" style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;"> <![endif]--> <div style="width:100%;display:inline-block;vertical-align:middle;text-align:center;max-width:445px;"> <table width="100%" style="border-spacing:0 !important;color:#414146;"> <tr> <td style="padding-top:5px;padding-bottom:5px;padding-right:20px;padding-left:20px;font-size:14px;text-align:left;"> <img src="https://www.practo.com/bundles/practopractoapp/images/mailers/logo_supply2.png" width="85" alt="•practo•" style="border-width:0;margin-bottom:10px;margin-top:5px;"/></td> </tr> </table> </div> </td> </tr> </table> <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]--> </div> <div style="max-width:750px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto;"> <!--[if (gte mso 9)|(IE)]> <table width="650" align="center" style="border-spacing:0 !important;color:#414146;"> <tr> <td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;"> <![endif]--> <table align="left" style="width:100%;border-spacing:0 !important;color:#414146;"> <tr> <!-- Body --> <td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;"><p style="margin-top:5px;Margin:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;font-size:10px;"> <a href="https://www.practo.com/company/privacy" style="text-decoration:underline;font-size:10px;color:#787887;">Privacy Policy</a>&nbsp;&nbsp; All Rights Reserved &copy; 2019, Practo </p></td> </tr> </table> <!--[if (gte mso 9)|(IE)]> </td> </tr> </table> <![endif]--> </div> </center> </body> </html>';
        $mailBody = str_replace("{{mcq_email}}", $email, $mailBody);
        $mailBody = str_replace("{{mcq_token}}", $token, $mailBody);


        $mail->Body = $mailBody;
        $mail->AltBody = 'Your Practo MCQ Token is ' . $token . ' Link ' . 'https://mcq.practodev.com/signup';

        $mail->send();
        echo 'Message has been sent to ' . $email;
        $pheanstalk->delete($job);
    } catch (Exception $e) {
        echo "Message could not be sent to " . $email . " Mailer Error: {$mail->ErrorInfo}";
    }
}