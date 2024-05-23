<?php
     header("Access-Control-Allow-Origin: *");
     header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
     header("Access-Control-Allow-Headers: Content-Type, Authorization");
 
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET["email"])) {
            $email = $_GET["email"];
    
            echo json_encode([
                "status" => "success",
                "email" => $email,
            ]);
        }
    }
 ?>