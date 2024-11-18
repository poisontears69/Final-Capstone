<?php
session_start();
include('includes/dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
// require __DIR__ . '/../vendor/autoload.php';
require('vendor/autoload.php');
function sendemail_verify($name, $email, $verify_token)
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
    $mail->Subject = 'Registration Verification';
    
    $email_template = "
    <h2>Hi $name</h2>
    <p>Click on the link below to verify your email address</p>
    <br/><br/>
    <a href=https://healthconnect.website/verify_email.php?token=$verify_token>Verify Email</a>
    ";

    $mail->Body = $email_template;
    $mail->send();
    echo 'Message has been sent';
}

if (isset($_POST['register_btn']))
{
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $verify_token = md5(rand());

    //Email Exists or not
    $check_email = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $check_email_run = mysqli_query($con, $check_email);

    if(mysqli_num_rows($check_email_run) > 0)
    {
        $_SESSION['error'] = "Email Already Exists";
        header('Location: registration.php');
        exit(0);
    }
    else
    {
        //Insert User / Registered User Data
        $stmt = $con->prepare("INSERT INTO users (name, phone, email, password, verify_token) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $phone, $email, $password, $verify_token);
        $query_run = $stmt->execute();


         if($query_run)
         {
            sendemail_verify("$name", "$email", "$verify_token");
            $_SESSION['success'] = "Registration Successful. Please verify your Email address";
            header('Location: registration.php');
            exit(0);
         }
         else
         {
            $_SESSION['error'] = "Registration Failed";
            header('Location: registration.php');
            exit(0);
         }
    }
}

?>
