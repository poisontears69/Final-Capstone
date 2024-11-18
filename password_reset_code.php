<?php
session_start();
include('includes/dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
//require __DIR__ . '/../vendor/autoload.php';
require('vendor/autoload.php');
function send_password_reset($get_name, $get_email, $token)
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

    $mail->setFrom('tagoy513@gmail.com', $get_name);
    $mail->addAddress($get_email);

    $mail->isHTML(true);
    $mail->Subject = 'Registration Verification';
    
    $email_template = "
    <h2>Hi $name</h2>
    <h3>You are requesting a password reset link for your account </h3>
    <br/><br/>
    <a href=https://healthconnect.website/password_change.php?token=$token&email=$get_email>Change Password</a>
    ";

    $mail->Body = $email_template;
    $mail->send();
}

if(isset($_POST['password_reset_link']))
{
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $token = md5(rand());

    $check_email = "SELECT email FROM users WHERE email='$email' LIMIT 1";
    $check_email_run = mysqli_query($con, $check_email);

    if(mysqli_num_rows($check_email_run) > 0)
    {
        $row = mysqli_fetch_array($check_email_run);
        $get_name = $row['name'];
        $get_email = $row['email'];

        $update_token = "UPDATE users SET verify_token='$token' WHERE email='$get_email' LIMIT 1";
        $update_token_run = mysqli_query($con, $update_token);

        if($update_token_run)
        {
            send_password_reset($get_name, $get_email, $token);
            $_SESSION['success'] = "Check your email to reset your password";
            header('location: password_reset.php');
        }
        else
        {
            $_SESSION['error'] = "Something went wrong #1";
            header('location: password_reset.php');
        }
    }
    else
    {
        $_SESSION['error'] = "No Email Found";
        header('location: password_reset.php');
    }
}

if(isset($_POST['password_update']))
{
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    $token = mysqli_real_escape_string($con, $_POST['password_token']);

    if(!empty($token))
    {
        if(!empty($email) && !empty($new_password) && !empty($confirm_password))
        {
            // Checking token is valid or not
            $check_token = "SELECT email, verify_token FROM users WHERE email='$email' AND verify_token='$token' LIMIT 1";
            $check_token_run = mysqli_query($con, $check_token);

            if(mysqli_num_rows($check_token_run) > 0)
            {
                if($new_password == $confirm_password)
                {
                    $update_password = "UPDATE users SET password='$new_password' WHERE verify_token='$token' LIMIT 1";
                    $update_password_run = mysqli_query($con, $update_password);
                    if($update_password_run)
                    {
                        $new_token = md5(rand());
                        $update_to_new_token = "UPDATE users SET verify_token='$new_token' WHERE verify_token='$token' LIMIT 1";
                        $update_to_new_token_run = mysqli_query($con, $update_to_new_token);
                        $_SESSION['success'] = "Password has been updated successfully";
                        header('location: login.php');
                    }
                    else
                    {
                        $_SESSION['error'] = "Did not update password. Something went wrong";
                        header('location: login.php');
                    }
                }
                else
                {
                    $_SESSION['error'] = "Password and Confirmed Password are not matched";
                    header("Location: password_change.php?token=$token&email=$email");
                }
            }
            else
            {
                $_SESSION['error'] = "Invalid Token";
                header("Location: password_change.php?token=$token&email=$email");
            }
        }
        else
        {
            $_SESSION['error'] = "All fields are required";
            header('location: password_change.php');
        }
    }
    else
    {
        $_SESSION['error'] = "No Token Found";
        header('location: password_reset.php');
    }


}

?>