<?php
// hi kramer
//hi irene

require_once 'connect.php';
require_once 'login.php';

$resumeArray = array();
viewByTimeStamp();

function viewByTimeStamp(){
    connect();
    global $conn;
    $query = "SELECT ts, user.username, resume.name, resumeURL from user natural join resume order by ts DESC";
    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    mysqli_stmt_execute($statment);


    $timeStamp = "";
    $userName = "";
    $resumeName = "";
    $resumeURL = "";
    mysqli_stmt_bind_result($statment, $timeStamp, $userName, $resumeName, $resumeURL);

    while($row = mysqli_stmt_fetch($statment)){
        $resumeArray[] = array($timeStamp, $userName, $resumeName, $resumeURL);
        foreach($resumeArray as &$row2) {
            foreach ($row2 as &$str) {
                echo "<a href='#' class='list-group-item'>";
                echo "<table><tr><td style='padding-right:50px'>";
                echo $row2[0];
                echo "</td>";
                echo "<td>" . $row2[1] . "</td>";
                echo "<td>" . $row2[2] . "</td>";
                echo "</tr></table>";
            }
        }
    }

    mysqli_stmt_close($statment);
    close();
}

function viewByMajor($major){
    connect();
    global $conn;
    $query = "SELECT ts, user.username, resume.name, resumeURL from user natural join resume where major = ? order by ts DESC";
    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }
    mysqli_stmt_bind_param($statment, 's', $major);
    mysqli_stmt_execute($statment);

    $timeStamp = "";
    $userName = "";
    $resumeName = "";
    $resumeURL = "";
    mysqli_stmt_bind_result($statment, $timeStamp, $userName, $resumeName, $resumeURL);

    while($row = mysqli_stmt_fetch($statment)){
        $resumeArray[] = array($timeStamp, $userName, $resumeName, $resumeURL);
        foreach($resumeArray as &$row2) {
            foreach ($row2 as &$str) {
                echo "<a href='#' class='list-group-item'>";
                echo "<table><tr><td style='padding-right:50px'>";
                echo $row2[0];
                echo "</td>";
                echo "<td>" . $row2[1] . "</td>";
                echo "<td>" . $row2[2] . "</td>";
                echo "</tr></table>";
            }
        }
    }

    mysqli_stmt_close($statment);
    close();
}


?>