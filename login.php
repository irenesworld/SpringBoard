<?php

session_start();

require 'Membership.php';
$membership = new Membership();

function login($email, $password){
    echo 'LOGGING IN  ';
    echo "\r\n";
    global $membership;
    $response = $membership->login($email, $password);
    $_SESSION["userEmail"] = $email;
    echo '  email for session: ';
    echo $email;
    echo "\r\n";
}

function logout(){
    global $membership;
    $membership->logout();
}

?>