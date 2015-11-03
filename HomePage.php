<?php
    require 'connect.php';
    require 'login.php';

    function createUser($name, $email, $password, $major, $universityID){
        connect();
        global $conn;

        $query = "INSERT INTO user (email, password, userName, total_votes, major, pictureURL) VALUES(?, ?, ?, ?, ?, ?)";

        $statment = mysqli_prepare($conn,$query);
        if ( !$statment ) {
            die('mysqli error: '.mysqli_error($conn));
        }
        $votes = 0;
        mysqli_stmt_bind_param($statment, 'sssisi', $email, $password, $name, $votes, $major, $universityID);
        mysqli_stmt_execute($statment);

        if(mysqli_stmt_num_rows($statment) > 0){
            login($email, $password);
        }

        mysqli_stmt_close($statment);
        close();
    }

    if(isset($_POST['createUser'])){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $major = $_POST['major'];
        $universityID = $_POST['universityID'];

        if(!$_POST['name']){
            $errorName = 'Please enter your name';
        }

        if(!$_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $errorEmail = 'Please enter a valid email';
        }

        if(!$_POST['password']){
            $errorPassword = 'Please enter a password';
        }

        if(!$_POST['major']){
            $errorMajor = 'Please select a major';
        }

        if(!$_POST['universityID']){
            $errorPassword = 'Please select a university';
        }

        if(!$errorName && !$errorEmail && !$errorPassword && !$errorMajor && !$universityID){
            createUser($name, $email, $password, $major, $universityID);
        }
    }
?>