<?php
    require 'connect.php';

    function createUser($name, $email, $password, $major, $univeristyID){
        connect();
        global $conn;

        $query = "INSERT INTO user (email, password, userName, total_votes, major, pictureURL) VALUES(?, ?, ?, ?, ?, ?)";

        $statment = mysqli_prepare($conn,$query);
        if ( !$statment ) {
            die('mysqli error: '.mysqli_error($conn));
        }
        $votes = 0;
        mysqli_stmt_bind_param($statment, 'sssisi', $email, $password, $name, $votes, $major, $univeristyID);
        mysqli_stmt_execute($statment);
        
        mysqli_stmt_close($statment);
        close();
    }

?>