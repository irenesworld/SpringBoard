<?php
// hi kramer
//hi irene

require_once 'connect.php';
require_once 'login.php';

$resumeArray = array();
//viewByTimeStamp();

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
    $name = "";
    $resumeURL = "";
    mysqli_stmt_bind_result($statment, $timeStamp, $userName, $fileName, $resumeURL);

    while ($row = mysqli_stmt_fetch($statment)) {
        $resumeArray[] = array($timeStamp, $userName, $fileName, $resumeURL);
    }
    // maybe change table
    foreach($resumeArray as &$row2) {
        echo "<a href='#' class='list-group-item'>";
        echo "<div class='row'>";
        echo "<div class='col-md-4'>";
        echo $row2[0] . "</div>";
        echo "<div class='col-md-4'>";
        echo $row2[1] . "</div>";
        echo "<div class='col-md-4'>";
        echo $row2[2] . "</div>";
        echo "</div></a>";
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

    while ($row = mysqli_stmt_fetch($statment)) {
        $resumeArray[] = array($timeStamp, $name, $resumeURL);
    }
    foreach($resumeArray as &$row2) {
        echo "<a href='#' class='list-group-item'>";
        echo "<table><tr><td style='padding-right:50px'>";
        echo $row2[0];
        echo "</td>";
        echo "<td>" . $row2[1] . "</td>";
        echo "</tr></table>";
    }

    mysqli_stmt_close($statment);
    close();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>About</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <style>
        @media (min-width: 1000px) {
            .container{
                max-width: 800px;
            }
        }
    </style>

</head>

<body>

<div class="header">
    <table align="center" width=100%>
        <tr> <!-- ROW -->
            <td> <!-- COLUMN -->
                <h1>&nbsp;SpringBoard</h1>
            </td>
            <td>
                <br>
                <p align="right">Log out &nbsp;</p>
            </td>
        </tr>
    </table>
</div>

<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
        </div>
        <div>
            <ul class="nav navbar-nav" >
                <li><a href="hometest.php">Home</a></li>
                <li><a href="aboutpage.html">About</a><li>
                <li><a href="profiletest.php">Profile</a></li>
                <li class="active"><a href="#">My Resumes</a></li>
                <li><a href="reviewresumepage.html">Review Resumes</a><li>
                <li><a href="#">Resources</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <br>
    <br>
    <div class="dropdown" align="right">
        <button class="btn btn-default dropdown-toggle" type="button" id="filter"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <span class="glyphicon glyphicon-menu-hamburger"></span>
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="filter">
            <li><a href="#">Name</a></li>
            <li><a href="#">Major</a></li>
            <li><a href="#">University</a></li>
            <li><a href="#">Date Posted</a></li>
        </ul>
    </div>
    <br>
    <br>
    <div class="list-group">
        <?php
            viewByTimeStamp();
        ?>
    </div>
</div>


</body>
</html>

