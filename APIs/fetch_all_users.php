<?php
    include ("../connect-to-db.php");

    $fetchAllUsersQuery = "SELECT * FROM `music-app-user-acc`";

    $res = @mysqli_query($connector, $fetchAllUsersQuery);
    
    $response = [];

    if ($res) {
        // echo "ALL USERS";
        $allUsers = mysqli_fetch_all($res, MYSQLI_ASSOC);
        
        $i = 0;

        foreach ($allUsers as $user) {
            // // echo $user["username"];
            // $response[$i]["username"] = $user["username"];
            // $response[$i]["email"] = $user["email"];
            // $response[$i]["password"] = $user["password"];
            array_push($response, 
                ["username" => $user["username"],
                "email" => $user["email"],
                "password" => $user["password"]]
            );
            $i++;
        }

        

        echo json_encode($response, JSON_PRETTY_PRINT);


        // // free memory
        // @mysqli_free_result($result);
    }

    // free memory
    @mysqli_free_result($result);
 ?>