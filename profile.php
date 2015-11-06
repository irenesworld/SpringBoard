<?php

require_once 'connect.php';

$pictureURL = "";
$pictureError = "";

function changePicture($pictureURL){
    connect();
    global $conn;

    $userID = $_SESSION['userid'];

    $query = "UPDATE user SET pictureURL = ? where iduser = ?";

    $statment = mysqli_prepare($conn,$query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    mysqli_stmt_bind_param($statment, 'si', $pictureURL, $userID);
    mysqli_stmt_execute($statment);

    if(mysqli_stmt_fetch($statment)){
        mysqli_stmt_close($statment);
        return true;
    }else{
        mysqli_stmt_close($statment);
        return false;
    }

    close();
}

if(isset($_POST['signin'])) {
    if(changePicture($pictureURL)){
        //it changed it
    }else{
        $pictureError = "Could not change picture";
    }
}

?>