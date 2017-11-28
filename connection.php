<?php
    // Connect to the database
    $link = mysqli_connect("db1.cs.uakron.edu", "xq6", "ISP2017qxf", "ISP_xq6");
    if(mysqli_connect_error()){
        die("ERROR: Unable to connect：".mysqli_connect_error());
    }
?>