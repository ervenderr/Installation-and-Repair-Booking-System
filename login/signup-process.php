<?php
session_start();
ob_start();
require_once '../homeIncludes/dbconfig.php';

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
    $verify_token = 1;
    $usertype = 'customer';
    $cust_type = 'online';

    // Verify the reCAPTCHA
    $secretkey = "6LckLd4kAAAAAJyeMoi-eP6s4qaD82K-1m3XURGA";
    $ip = $_SERVER['REMOTE_ADDR'];
    $response = $_POST['g-recaptcha-response'];
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretkey&response=$response&remoteip=$ip";
    $fire = file_get_contents($url);
    $data = json_decode($fire);

    if($data->success==false){
        // reCAPTCHA verification failed
        $_SESSION['msg'] = "Please verify that you are not a robot";
        header("Location: signup.php");
        exit();
    }

    // Check if the email already exists
    $checkEmail = "SELECT email FROM accounts WHERE email='$email' LIMIT 1";
    $checkEmailRun = mysqli_query($conn, $checkEmail);

    if(mysqli_num_rows($checkEmailRun) > 0){
        $_SESSION['msg'] = "Email already exists";
        header("Location: signup.php");
        exit();
    }

    // Prepare and execute the first SQL statement to insert email, password, and user type into the accounts table
    $stmt = mysqli_prepare($conn, "INSERT INTO accounts (email, password, user_type, verify_status) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $email, $password, $usertype, $verify_token);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Get the ID of the newly inserted account
    $account_id = mysqli_insert_id($conn);

    // Prepare and execute the second SQL statement to insert the rest of the data into the customer table
    $stmt = mysqli_prepare($conn, "INSERT INTO customer (account_id, fname, mname, lname, phone, address, cust_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "issssss", $account_id, $fname, $mname, $lname, $phone, $address, $cust_type);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if($stmt){
        $_SESSION['msg'] = "Sign up successful! Please log in.";
        $_SESSION['signup_success'] = true;
        header("Location: login.php");
        exit();
    }else{
        // Registration failed
        $_SESSION['msg'] = "Registration failed";
        header("Location: signup.php");
        exit();
    }
}

?>
