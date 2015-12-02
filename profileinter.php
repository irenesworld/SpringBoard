// this is needed for profiletest.php
<?php

require_once 'connect.php';
require_once 'login.php';

if(!(isLoggedIn())){
    redirect("../springboard/homepage.php");
    return;
}

connect();
global $conn;

$userEmail = $_SESSION['userEmail'];

$query = "SELECT pictureURL, userName, major from user where email = ?";

$statment = mysqli_prepare($conn, $query);
if ( !$statment ) {
    die('mysqli error: '.mysqli_error($conn));
}

mysqli_stmt_bind_param($statment, 's', $userEmail);
mysqli_stmt_execute($statment);
mysqli_stmt_bind_result($statment, $pictureURL, $name, $major);

if(mysqli_stmt_fetch($statment)){
    echo $pictureURL;
    echo $name;
    mysqli_stmt_close($statment);
    close();
}else{
    mysqli_stmt_close($statment);
    close();
}

$pictureURL = "";
$pictureError = "";

if(isset($_GET['loadpic'])) {
    echo 'goes inside isset(profpic)    ';
    connect();
    global $conn;

    $userEmail = $_SESSION['userEmail'];
    echo $userEmail;

    $query = "SELECT pictureURL from user where email = ?";

    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($statment, 's', $userEmail);
    mysqli_stmt_execute($statment);
    mysqli_stmt_bind_result($statment, $pictureURL);

    if(mysqli_stmt_fetch($statment)){
        mysqli_stmt_close($statment);
        close();
        return true;
    }else{
        mysqli_stmt_close($statment);
        close();
        return false;
    }
}

function changePicture($pictureURL){
    connect();
    global $conn;

    $userEmail = $_SESSION['userEmail'];

    $query = "UPDATE user SET pictureURL = ? where email = ?";

    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    echo $userEmail;

    mysqli_stmt_bind_param($statment, 'ss', $pictureURL, $userEmail);
    mysqli_stmt_execute($statment);

    if(mysqli_stmt_fetch($statment)){
        mysqli_stmt_close($statment);
        //header("Location: ../profiletest.php");
        redirect("../profilepage.php");
        close();
        return true;
    }else{
        mysqli_stmt_close($statment);
        //header("Location: ../profiletest.php");
        redirect("../profilepage.php");
        close();
        return false;
    }
}

if(isset($_GET['changepic'])) {
    $pictureURL = htmlspecialchars($_GET['changepic']);

    if(changePicture($pictureURL)){
        //it changed it
        echo $pictureURL;
    }else {
        $pictureError = "Could not change picture";
    }
}

/*function redirect($url)
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
}*/

?>
