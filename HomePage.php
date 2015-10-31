<?php
    require 'connect.php';
error_reporting(E_ALL);
ini_set('display_errors', '1');

    function createUser($name, $email, $password, $major, $univeristyID){
        connect();
        global $conn;

        $query = "INSERT INTO user (email, password, userName, total_votes, major, pictureURL) VALUES(?, ?, ?, ?, ?, ?)";
        echo ' before prepare';
        $statment = mysqli_prepare($conn,$query);
        if ( !$statment ) {
            die('mysqli error: '.mysqli_error($conn));
        }
        $votes = 0;
        mysqli_stmt_bind_param($statment, 'sssisi', $email, $password, $name, $votes, $major, $univeristyID);
        mysqli_stmt_execute($statment);

        printf("%d Row inserted.\n", mysqli_stmt_affected_rows($statment));
        mysqli_stmt_close($statment);
        close();
    }

    createUser('dk','djk316@lehigh.edu','password','Comp Sci',1);
?>