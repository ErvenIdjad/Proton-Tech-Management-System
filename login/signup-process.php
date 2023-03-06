<?php
session_start();
require_once '../homeIncludes/dbconfig.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';

function sendEmail_verify($fname, $email, $verify_token){

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->SMTPAuth = true;

    $mail->Host = 'smtp.gmail.com';
    $mail->Username = 'protontechonline@gmail.com';
    $mail->Password = 'emehffhcrnwhzafe';

    $mail->SMTPSecure = "ssl";
    $mail->Port = 465;

    $mail->setFrom('protontechonline@gmail.com');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Email verification from Proton Electronics and Services';

    // Email Template
    $email_template = "
    <html>
        <head>
            <style>
                .container {
                    margin: 20px;
                    padding: 20px;
                    background-color: #F7F7F7;
                    font-family: Arial, Helvetica, sans-serif;
                }

                .header {
                    font-size: 24px;
                    font-weight: bold;
                    color: #333333;
                    margin-bottom: 10px;
                }

                .message {
                    font-size: 16px;
                    color: #666666;
                    margin-bottom: 20px;
                }

                .button {
                    display: inline-block;
                    background-color: #015F6B;
                    color: #ffffff !important;
                    text-decoration: none;
                    padding: 10px 20px;
                    border-radius: 5px;
                }

                .button:hover {
                    background-color: #015F6B;
                    color: #ffffff;
                }
            </style>
        </head>

        <body>
            <div class='container'>
                <div class='header'>You have registered with Proton Electronics and Services</div>
                <div class='message'>Please verify your email address by clicking the button below:</div>
                <a href='http://localhost/Proton-Tech-Management-System/login/verify-email.php?token=$verify_token' class='button'>Verify Email Address</a>
            </div>
        </body>
    </html>";

    $mail->Body = $email_template;
    $mail->send();
    // echo "Message sent";

}



// Check if the form was submitted
if (isset($_POST['submit'])) {
    // Get the form data and sanitize it
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $verify_token = md5(rand());
    $usertype = 'customer';


    $checkEmail = "SELECT email FROM accounts WHERE email='$email' LIMIT 1";
    $checkEmailRun = mysqli_query($conn, $checkEmail);

    if(mysqli_num_rows($checkEmailRun) > 0){
        $_SESSION['msg'] = "Email already exists";
        header("Location: signup.php");

    }else{

        // Prepare and execute the first SQL statement to insert email, password, and user type into the accounts table
    $stmt = mysqli_prepare($conn, "INSERT INTO accounts (email, password, user_type, verify_token) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $email, $password, $usertype, $verify_token);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Get the ID of the newly inserted account
    $account_id = mysqli_insert_id($conn);

    // Prepare and execute the second SQL statement to insert the rest of the data into the customer table
    $stmt = mysqli_prepare($conn, "INSERT INTO customer (account_id, fname, mname, lname, phone, address) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "isssss", $account_id, $fname, $mname, $lname, $phone, $address);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if($stmt){

        sendEmail_verify("$fname", "$email", "$verify_token");
        $_SESSION['msg'] = "Registration successful. Please verify your Email Address";
        $_SESSION['signup_success'] = true;
        header("Location: signup.php");

    }else{

    }
    exit();
    }
}

?>