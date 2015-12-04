<?php

require_once 'connect.php';
require_once 'login.php';
//echo 'reached here';
if(isset($_GET['addingComment'])) {
    //echo 'got into adding comment';
    $commentStr = htmlspecialchars($_GET['commentStr']);
    //echo $commentStr;
    $idresume = htmlspecialchars($_GET['idresume']);
    //echo $idresume;
    $resumeURL = htmlspecialchars($_GET['resumeURL']);

    //$idresume = "";

    connect();
    global $conn;
    $query = "insert into comment (author_id, resume_id, commentString) values(?,?,?);";
    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    mysqli_stmt_bind_param($statment, 'iis', $_SESSION['userid'], $idresume, $commentStr);
    mysqli_stmt_execute($statment);

    $newCommentID = mysqli_stmt_insert_id($statment);

    mysqli_stmt_close($statment);


    // makes a positive vote for youself. Reddit style
    $positiveVote = 1;
    $query2 = "insert into vote (idu, idc, positive) values(?,?,?)";
    $statment2 = mysqli_prepare($conn, $query2);
    if ( !$statment2 ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    mysqli_stmt_bind_param($statment2, 'iii', $_SESSION['userid'], $newCommentID, $positiveVote);
    mysqli_stmt_execute($statment2);

    mysqli_stmt_close($statment2);

    close();
   // $str = "viewresume.php?idresume=" . $idresume . "&resumeURL=" . $resumeURL;
  //  echo $str;
    redirect("viewresume.php?idresume=" . $idresume . "&resumeURL=" . $resumeURL);
}

function addVote($commentID, $boolean){
    connect();
    global $conn;
    $query = "select positive from vote where idu = ? and idc = ?);";
    $statment = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($statment, 'ii', $_SESSION['userid'], $commentID);
    mysqli_stmt_execute($statment);

    $positive = "";
    mysqli_stmt_bind_result($statment, $positive);
    mysqli_stmt_fetch($statment);

    if (!$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }
    mysqli_stmt_close($statment);

    //if you havent voted yet
    if (empty($positive)) {

        if($boolean){
            $vote = 1;
        }else{
            $vote = 0;
        }

        $query2 = "insert into vote (idu, idc, positive) values(?,?,?)";
        $statment2 = mysqli_prepare($conn, $query2);
        if ( !$statment2 ) {
            die('mysqli error: '.mysqli_error($conn));
        }

        mysqli_stmt_bind_param($statment2, 'iii', $_SESSION['userid'], $commentID, $vote);
        mysqli_stmt_execute($statment2);

        mysqli_stmt_close($statment2);
    }

    close();
}

?>