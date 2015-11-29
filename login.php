<?php

session_start();
//small change
require 'Membership.php';
$membership = new Membership();

function login($email, $password){
    global $membership;
    $response = $membership->login($email, $password);
    return $response; // true or false
}

function logout(){
    global $membership;
    $membership->logout();
}

?>