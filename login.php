<?php

session_start();

require 'Membership.php';
$membership = new Membership();

function login($email, $password){
    global $membership;
    $response = $membership->login($email, $password);
}

function logout(){
    global $membership;
    $membership->logout();
}

if(isset($_POST['signin'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(!$_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $errorEmail = 'Please enter a valid email';
    }

    if(!$_POST['password']){
        $errorPassword = 'Please enter a passsword';
    }

    if(!$errorEmail && !$errorPassword){
        login($email,$password);
    }
}




?>