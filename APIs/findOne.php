<?php
    include("../connect-to-db.php");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
        // Returns 200 OK for preflight requests
        http_response_code(200);
        exit();
    }

    // // Get the raw POST data
    // $rawData = file_get_contents("php://input");
    // $data = json_decode($rawData, true);


    // // Check whether both the email and password are set
    // if (!isset($data["email"]) || !isset($data["password"])) {
    //     echo json_encode([
    //         "status" => "error",
    //         "message" => "Email and password are required",
    //     ]);

    //     exit();
    // }

    // check if the request method is POST
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Get the raw POST data
        $rawData = file_get_contents("php://input");
        $data = json_decode($rawData, true);


        // Check whether both the email and password are set
        if (!isset($data["email"]) || !isset($data["password"])) {
            echo json_encode([
                "status" => "error",
                "message" => "Email and password are required",
            ]);

            exit();
        }

        // $email = $_POST["email"];
        // $password = $_POST["password"];
        $email = $data["email"];
        $password = $data["password"];

        $query = "SELECT * FROM `music-app-server-acc` WHERE `email` = ?";

        $statement = $connector->prepare($query);

        if (!$statement) {
            echo json_encode([
                "status" => "error",
                "message" => "Failed to prepare statement"
            ]);

            exit();
        }

        $statement->bind_param('s', $email);
        if (!$statement->execute()) {
            echo json_encode([
                "status" => "error",
                "message" => "Failed to execute statement ----> {$statement->error}"
            ]);

            exit();
        }
        $statement->execute();

        $result = $statement->get_result();
        if ($result === false) {
            echo json_encode([
                "status" => "error",
                "message" => "Failed to get the rule set ----> {$statement->error}"
            ]);

            exit();
        }
        // $user = $statement->fetch_assoc();

        if ($user && password_verify($password, $user["password"])) {
            // password matches and user is found
            echo json_encode([
                "status" => "success",
                "message" => "Login successfully !",
                "user" => $user,
            ]);
        } else {
            // Invalid email or password
            echo json_encode([
                "status" => "error",
                "message" => "Cannot find the matched user !!!",
            ]);
        }
        $statement->close();
    }

    // interrupting the connection to the database
    $connector->close();
 ?>