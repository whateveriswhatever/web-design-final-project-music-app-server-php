<?php
    include ("../connect-to-db.php");
    header("Access-Control-Allow-Origins: *");
    header("Access-Control-Allow-Methods: POST");
    header(
        "Access-Control-Allow-Headers: Content-Type, Authorization"
    );

    $log_registered_acc_file = "request_log_registered_form.txt";
    $log_data = "Method: {$_SERVER['REQUEST_METHOD']}";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $log_data .= "Recevied POST registered data: " . print_r($_POST, true). "\n";
        file_put_contents($log_registered_acc_file, $log_data, FILE_APPEND);

        if (isset($_POST["email"]) && isset($_POST["password"])) {
            $email = $_POST["email"];
            $password = $_POST["password"];


            // Prepare the SQL statement to avert SQL injection
            // Check if email already exists
            $stmt = $connector->prepare("SELECT `email` FROM `music-app-user-acc` WHERE `email` = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $outcome = $stmt->get_result();

            if ($outcome->num_rows > 0) {
                echo json_encode([
                    "status" => "error",
                    "message" => "This email is already existed!" 
                ]);
            } else {
                // Insert new account inner the database
                $stmt = $connector->prepare("INSERT INTO `music-app-user-acc`(`username`, `email`, `password`) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $email, $email, $password);

                if ($stmt->execute()) {
                    echo json_encode([
                        "status" => "success",
                        "message" => "User reigstered successfully!"
                    ]);
                } else {
                    $log_data .= "Invalid request method \n";
                    $file_put_contents($log_registered_acc_file, $log_data, FILE_APPEND);
                    
                    echo json_encode([
                        "status" => "error",
                        "message" => "Failed when registering new user account!!!"
                    ]);
                }
            }


        }
    }

 ?>