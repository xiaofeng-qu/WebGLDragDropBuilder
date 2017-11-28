<?php
    // Start session
    session_start();
    // Connect to database
    include('connection.php');
    // Check user inputs
    // 1. Define error messages
    $missingUsername='<p>Please enter a username!</p>';
    $missingEmail='<p>Please enter your email!</p>';
    $invalidEmail='<p>Please enter a valid email address!</p>';
    $missingPassword='<p>Please enter a password!</p>';
    $invalidPassword='<p>Your password should be at least 6 characters long and include at least one capital letter and one number!</p>';
    $passwordNotMatch='<p>Passwords do not match!</p>';
    $missingPasswordConfirm='<p>Please confirm your password!</p>';
    // 2. Get username, email, password, passwordConfirm
    $errors="";
    // Get username
    if(empty($_POST["username"])){
        $errors .= $missingUsername;
    }
    else{
        $username = filter_var($_POST["username"],FILTER_SANITIZE_STRING);
    }
    // Get email
    if(empty($_POST["email"])){
        $errors .= $missingEmail;
    }
    else{
        $email = filter_var($_POST["email"],FILTER_SANITIZE_EMAIL);
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            $errors .= $invalidEmail;
        }
    }
    // Get password
    if(empty($_POST["password"])){
        $errors .= $missingPassword;
    }
    elseif(strlen($_POST["password"])<6 or !preg_match('/[A-Z]/', $_POST["password"]) or !preg_match('/[0-9]/', $_POST["password"])){
        $errors .= $invalidPassword;
    }
    else{
        $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
        // Get passwordConfirm and compare passwords
        if(empty($_POST["passwordConfirm"])){
            $errors .= $missingPasswordConfirm;
        }
        else{
            $passwordConfirm = filter_var($_POST["passwordConfirm"], FILTER_SANITIZE_STRING);
            if($password !== $passwordConfirm){
                $errors .= $passwordNotMatch;
            }
        }
    }
    // If there are any errors print error
    if($errors){
        $resultMessage = '<div class="alert alert-danger">'.$errors.'</div>';
        echo $resultMessage;
    }
    else{
        // Prepare variables for the queries
        $username = mysqli_real_escape_string($link, $username);
        $email = mysqli_real_escape_string($link, $email);
        $password = mysqli_real_escape_string($link, $password);
        $password = hash('sha256', $password);
        // If username exists in the users table print error
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($link, $sql);
        if(!$result){
            echo '<div class="alert alert-danger">Error running the query.</div>';
            echo mysqli_error($link);
            exit;
        }
        $numOfResult = mysqli_num_rows($result);
        if($numOfResult){
            echo '<div class="alert alert-danger">The username has been registered. Do you want to log in?</div>';
            exit;
        }
        // If email exists print error
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($link, $sql);
        if(!$result){
            echo '<div class="alert alert-danger">Error running the query.</div>';
            echo mysqli_error($link);
            exit;
        }
        $numOfResult = mysqli_num_rows($result);
        if($numOfResult){
            echo '<div class="alert alert-danger">The email has been registered. Do you want to log in?</div>';
            exit;
        }
        // Insert user details and activation code in the users table
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        $result = mysqli_query($link, $sql);
        if(!$result){
            echo '<div class="alert alert-danger">Error running the query.</div>';
            echo mysqli_error($link);
            exit;
        } else {
            echo '<div class="alert alert-success">You have registered, click <button type="button" class="btn btn-default" data-dismiss="modal" data-target="#loginModal" data-toggle="modal">Here</button> to login.</div>';
        }
    }
?>