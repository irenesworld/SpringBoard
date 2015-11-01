<?php

session_start();

require 'Membership.php';
$membership = new Membership();

function login($email, $password){
    global $membership;
    if(!empty($email) && !empty($password)){
        $response = $membership->login($_POST['email'], $_POST['password']);
    }
}

function logout(){
    global $membership;
    $membership->logout();
}

?>