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

?>