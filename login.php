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

function isLoggedIn(){
    global $membership;
    $response = $membership->isLoggedIn();
    return $response; // true or false
}

function signout() {
    echo 'signing out';
    global $membership;
    $membership->logout();
    redirect('homepage.php');
}

function redirect($url)
{
    if (!headers_sent())
    {
        header('Location: '.$url);
        exit;
    }
    else
    {
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>'; exit;
    }
}
?>