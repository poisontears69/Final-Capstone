<?php
session_start();
include('includes/dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
// require __DIR__ . '/../vendor/autoload.php';
require('vendor/autoload.php');
function resend_email_verify($name, $email, $verify_token)
{
    $mail = new PHPMailer(true);
    // $mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->SMTPAuth = true;

    $mail->Host = 'smtp.gmail.com';
    $mail->Username = 'tagoy513@gmail.com';
    $mail->Password = 'uswt sadq dzqx ilxw';

    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('tagoy513@gmail.com', $name);
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Resend Email Verification from Health Connect';
    
    $email_template = "
    <h2>Hi $name</h2>
    <p>Click on the link below to verify your email address</p>
    <br/><br/>
    <a href=https://healthconnect.website/verify_email.php?token=$verify_token'>Verify Email</a>
    ";

    $mail->Body = $email_template;
    $mail->send();
}


if(isset($_POST['resend_email_verify_btn']))
{
    if(!empty(trim($_POST['email'])))
    {
        $email = mysqli_real_escape_string($con,$_POST['email']);
        
        $checkemail_query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        $checkemail_query_run = mysqli_query($con, $checkemail_query);

        if(mysqli_num_rows($checkemail_query_run) > 0)
        {
            $row = mysqli_fetch_array($checkemail_query_run);
            if($row['verify_status'] == "0")
            {
                $name = $row['name'];
                $email = $row['email'];
                $verify_token = $row['verify_token'];


                resend_email_verify($name, $email, $verify_token);
                $_SESSION['success'] = "Email sent successfully";
                header('Location: login.php');
            }
            else
            {
                $_SESSION['error'] = "Email already verified";
                header('Location: resend_email_verification.php');
                exit(0);
            }
        }
        else
        {
            $_SESSION['error'] = "Email is not registered";
            header('Location: registration.php');
            exit(0);
        }
    }
    else
    {
        $_SESSION['error'] = "Please enter email address";
        header('Location: resend_email_verification.php');
        exit(0);
    }
}
else
{

}

?>