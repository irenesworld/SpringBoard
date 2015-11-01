<?php

require 'connect.php';

class Membership
{
    function login($email, $password){
      if($this->validateUser($email, $password)){
          $_SESSION['status'] = 'authorized';
          header("location: profile.html");
      }else{
          return "Please enter a correct email and password";
      }
    }

    private function validateUser($email, $password){
        connect();
        global $conn;

        $query = "SELECT * FROM user WHERE iduser = ? AND password = ? LIMIT 1";

        $statment = mysqli_prepare($conn,$query);
        if ( !$statment ) {
            die('membership prepare error: '.mysqli_error($conn));
        }

        mysqli_stmt_bind_param($statment, 'ss', $email, $password);
        mysqli_stmt_execute($statment);

        if(mysqli_stmt_fetch()){
            mysqli_stmt_close($statment);
            return true;
        }
        close();
    }

    function logout(){
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
}

