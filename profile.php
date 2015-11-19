// this is needed for profiletest.php
<?php

require_once 'connect.php';
session_start();
echo 'goes into php file';

$pictureURL = "";
$pictureError = "";

if(isset($_GET['loadpic'])) {
    echo 'goes inside isset(profpic)    ';
    connect();
    global $conn;

    $userEmail = $_SESSION['userEmail'];
    //echo 'user Email:   ';
    //echo $userEmail;
    //echo '   ';

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

?>

