<?php
    include("env.php");

    $connector = mysqli_connect($dbhost, $dbuser, $db_password, $db_name);

    if (!$connector) {
        header("./Errors/Error500.php");
    }else {
        // echo "Connected to the database";
        header("index.php");
    }
 ?>