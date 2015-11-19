// this is needed for profiletest.php
<?php

require_once 'connect.php';
session_start();
echo 'goes into php file';
ob_start();

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
        die('mysqli error: '.mysqli_error($conn));
    }

    //echo '    user email: ';
    //echo $userEmail;

    mysqli_stmt_bind_param($statment, 's', $userEmail);
    mysqli_stmt_execute($statment);
    mysqli_stmt_bind_result($statment, $pictureURL);

    if(mysqli_stmt_fetch($statment)){
        // echo 'PICTURE URLLLL';
        // echo $pictureURL;
        mysqli_stmt_close($statment);
        close();
        return true;
    }else{
        //  $load = $pictureURL;
        //  echo 'PICTURE URL ';
        //  echo $pictureURL;
        mysqli_stmt_close($statment);
        close();
        return false;
    }
}

function changePicture($pictureURL){
    echo 'pic url ';
    echo $pictureURL;
    connect();
    global $conn;

    $userEmail = $_SESSION['userEmail'];

    $query = "UPDATE user SET pictureURL = ? where email = ?";

    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    echo '    user email: ';
    echo $userEmail;

    mysqli_stmt_bind_param($statment, 'ss', $pictureURL, $userEmail);
    mysqli_stmt_execute($statment);

    if(mysqli_stmt_fetch($statment)){
        mysqli_stmt_close($statment);
        header("Location: ../profiletest.php");
        close();
        return true;
    }else{
        mysqli_stmt_close($statment);
        header("Location: ../profiletest.php");
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

$oldPwd = "";
$newPass = "";
$passwordError = "";
if(isset($_GET['oldPass'])) {
    connect();
    global $conn;
    $oldPass = strip_tags($_GET['oldPass']);
    $newPass = strip_tags($_GET['newPass']);

    $query = "SELECT * from user where email = ? and password = ?";

    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    $result = "";
    mysqli_stmt_bind_param($statment, 'ss', $_SESSION['userEmail'], $oldPass);
    mysqli_stmt_execute($statment);

    if(mysqli_stmt_fetch($statment)){
        mysqli_stmt_close($statment);

        $query2 = "UPDATE user SET password = ? WHERE email = ? and password = ?";

        $statment2 = mysqli_prepare($conn, $query2);
        if ( !$statment2 ) {
            die('mysqli error: '.mysqli_error($conn));
        }

        mysqli_stmt_bind_param($statment2, 'sss', $newPass, $_SESSION['userEmail'], $oldPass);
        mysqli_stmt_execute($statment2);

        if(mysqli_stmt_fetch($statment2)){
            mysqli_stmt_close($statment2);
            //changed password
        }else{
            $passwordError = "Could not change password";
        }
    }else{
        mysqli_stmt_close($statment);
        $passwordError = "Incorrect password";
    }
    close();

    header("Location: profiletest.php");
    ob_end_flush();
    redirect('../profiletest.php');

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

