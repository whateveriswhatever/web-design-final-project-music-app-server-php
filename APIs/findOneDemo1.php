<?php
    // include("../connect-to-db.php");
    // header("Access-Control-Allow-Origin: *");
    // header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    // header("Access-Control-Allow-Headers: Content-Type, Authorization");
    
    // if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //     if (isset($_POST["email"]) && isset($_POST["password"])){
    //         $email = $_POST["email"];
    //         $password = $_POST["password"];

    //         // $sql = "SELECT `email`, `password` FROM `music-app-user-acc` WHERE `email` = '{$email}';";

    //         // $outcome = @mysqli_query($connector, $sql);

    //         // if ($outcome) {
    //         //     // $user = mysqli_fetch_all($outcome, MYSQLI_ASSOC);
    //         //     // echo $user[0];
    //         //     echo $outcome;
    //         // } else {
    //         //     echo "The provided user ain't exist !!!";
    //         // }

         
    //         }
    //     }

    include("../connect-to-db.php");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    $log_file = "request_log.txt";
    $log_data = "Method: {$_SERVER['REQUEST_METHOD']} \n";

    // Handle OPTIONS request for CORS preflight
    if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
        http_response_code(201);
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $log_data .= "Received POST data: " . print_r($_POST, true). "\n";
        file_put_contents($log_file, $log_data, FILE_APPEND);

        if (isset($_POST["email"]) && isset($_POST["password"])) {
            $email = $_POST["email"];
            $password = $_POST["password"];

            // echo "<div>Email: {$email}</div><div>{$password}</div>";

            // Prepare the SQL statement to prevent SQL injection
            $stmt = $connector->prepare("SELECT * FROM `music-app-user-acc` WHERE `email` = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                // echo $user["password"];
                // Verify the password
                if ($user["password"] === $password) { // password_verify($password, $user['password'])
                    echo json_encode(['status' => 'success', 'message' => 'Login successful']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Invalid password']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'User does not exist']);
            }
            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Email and password are required']);
        }
    } else {
        $log_data .= "Invalid request method \n";
        file_put_contents($log_file, $log_data, FILE_APPEND);
        
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
 ?>