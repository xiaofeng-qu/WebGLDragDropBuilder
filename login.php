<?php
    // Start session
    session_start();
    // Connect to the database
    include('connection.php');
    // Check user inputs
    // Define error messages
    $missingEmail = "<p>Please enter your email address.</p>";
    $missingPassword = "<p>Please enter your password.</p>";
    $errors = "";
    // Get email and password
    $email = $_POST['loginEmail'];
    if(empty($email)){
        $errors .= $missingEmail;
    }
    else{
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    $password = $_POST['loginPassword'];
    if(empty($password)){
        $errors .= $missingPassword;
    }
    else{
        $password = filter_var($password, FILTER_SANITIZE_STRING);
    }
    // If there are any errors
    if($errors){
        $resultMessage = '<div class="alert alert-danger">' . $errors . '</div>';
        echo $resultMessage;
    }
    else{
         // Prepare variables for the queries
        $email = mysqli_real_escape_string($link, $email);
        $password = mysqli_real_escape_string($link, $password);
        $password = hash('sha256', $password);
        // Query
        $sql = "SELECT * from users WHERE email = '$email' AND password = '$password'";
        $result = mysqli_query($link, $sql);
        if(!$result){
            echo '<div class="alert alert-danger">Error running the query!</div>';
            exit;
        }
        // If email & password don't match print error
        $count = mysqli_num_rows($result);
        if($count !== 1){
            echo '<div class="alert alert-danger">Wrong username or password</div>';
        }
        else{
            $row = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            echo "success";
        }
    }
?>