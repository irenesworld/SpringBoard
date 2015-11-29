<?php

require_once 'connect.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Membership
{


    private function validateUser($email, $password){
        connect();
        global $conn;

        $query = "SELECT iduser FROM user WHERE email = ? AND password = ? LIMIT 1";

        $statment = mysqli_prepare($conn,$query);
        if ( !$statment ) {
            die('membership prepare error: '.mysqli_error($conn));
        }

        mysqli_stmt_bind_param($statment, 'ss', $email, $password);
        mysqli_stmt_execute($statment);

        $id = 0;
        mysqli_stmt_bind_result($statment, $id);

        if(mysqli_stmt_fetch($statment)){
            mysqli_stmt_close($statment);
            return $id;
        }else{
            return false;
        }
        close();
    }

    function logout(){
        echo 'logging out';
        if(isset($_SESSION['status'])){
            unset($_SESSION['status']);

            if(isset($_COOKIE[session_name()])){
                setcookie(session_name(), '', time() - 10000);
                session_destroy();
            }
        }
    }

    function isLoggedIn(){
        session_start();
        if($_SESSION['status'] != 'authorized'){
            return false;
        }
        return true;
    }

    function login($email, $password){
        $response = $this->validateUser($email, $password);

        if($response){
            $_SESSION['status'] = 'authorized';
            $_SESSION['userid'] = $response;
            $_SESSION['userEmail'] = $email;
            return true;
        }else{
            return false;
        }
    }
}

