<?php
    session_start();
    include('connection.php');

    // Get the id of the note
    $id = $_POST['id'];
    // Run a query to delete the note
//    $sql = "SELECT picture_url FROM pictures WHERE picture_id = '$id'";
//    $result = mysqli_query($link, $sql);
//    $row = mysqli_fetch_assoc($result);
//    unlink(dirname(__FILE__) . '/' . $row['picture_url']);
//    echo $row['picture_url'];
    $sql = "DELETE FROM pictures WHERE picture_id = '$id'";
    $result = mysqli_query($link, $sql);
?>