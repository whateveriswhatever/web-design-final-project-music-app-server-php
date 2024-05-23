<?php
    // include ("../connect-to-db.php");
    // header("Access-Control-Allow-Origin: *");
    // header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    // header("Access-Control-Allow-Headers: Content-Type, Authorization");

    // if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //     if (isset($_POST["email"]) && isset($_POST["password"])) {
    //         $email = $_POST["email"];
    //         $password = $_POST["password"];
    
    //         // echo "User email: {$email}\nUser password: {$password}";
    //         echo json_encode([
    //             "status" => "success",
    //             "user" => [
    //                 "email" => $email,
    //                 "password" => $password
    //             ],
    //         ]);
    //     }
    // }

    include ("../connect-to-db.php");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Connect to database (replace with your connection logic)

        // Fetch user data based on email
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $connector->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password (replace with your password hashing logic)
            if (password_verify($password, $user['password'])) {
                // Login successful
                echo json_encode([
                    "status" => "success",
                    "user" => [
                        "email" => $email,
                    ],
                ]);
            } else {
                // Password incorrect
                    echo json_encode([
                        "status" => "error",
                        "message" => "Invalid email or password",
                    ]);
            }
        } else {
            // User not found
            echo json_encode([
                "status" => "error",
                "message" => "Invalid email or password",
            ]);
        }

    $connector->close();    
    }

 ?>