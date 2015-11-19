<?php

session_start();

require 'Membership.php';
$membership = new Membership();

function login($email, $password){
    echo ' in login of login php';
    global $membership;
    $response = $membership->login($email, $password);
    return $response; // true or false
}

function logout(){
    global $membership;
    $membership->logout();
}

?>