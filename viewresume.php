<?php
// hi kramer

require_once 'connect.php';
require_once 'login.php';

$resumeArray = array();


function addCommentToResume($resumeID, $commentStr){
    connect();
    global $conn;
    $query = "insert into comment (author_id, resume_id, commentString) values(?,?,?); SELECT LAST_INSERT_ID();";
    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    mysqli_stmt_bind_param($statment, 'iis', $_SESSION['userid'], $resumeID, $commentStr);
    mysqli_stmt_execute($statment);

    $newCommentID = "";
    mysqli_stmt_bind_result($statment, $newCommentID);

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

    $result2 = "";
    mysqli_stmt_bind_result($statment2, $result2);

    mysqli_stmt_close($statment2);

    close();
}

function addCommentToComment($resumeID, $commentID, $commentStr){
    connect();
    global $conn;
    $query = "insert into comment (author_id, resume_id, parent_id, commentString) values(?,?,?,?); SELECT LAST_INSERT_ID();";
    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    mysqli_stmt_bind_param($statment, 'iiis', $_SESSION['userid'], $resumeID, $commentID, $commentStr);
    mysqli_stmt_execute($statment);

    $newCommentID = "";
    mysqli_stmt_bind_result($statment, $newCommentID);

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

    $result2 = "";
    mysqli_stmt_bind_result($statment2, $result2);

    mysqli_stmt_close($statment2);

    close();
}

function getAllCommentsAndVotes($resumeID){
    connect();
    global $conn;
    $query = "SELECT * from comment where resume_id = ? order by (case when parent_id is null then 1 else 0 end) desc, parent_id desc";

    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    mysqli_stmt_bind_param($statment, 'i', $resumeID);
    mysqli_stmt_execute($statment);

    // to be continued..
}




?>