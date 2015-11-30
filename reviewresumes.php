<?php
// hi kramer
//hi irene
//change
// hmm... i think it populates kinda weird
// there are a lot of users that have IreneLau_Resume.pdf for some reason?

require_once 'connect.php';
require_once 'login.php';

$resumeArray = array();
//viewByTimeStamp();

if(!(isLoggedIn())){
    redirect("../springboard/homepage.php");
    return;
}

function viewByTimeStamp(){
    connect();
    global $conn;
    $query = "SELECT ts, user.username, user.major, user.universityID, resume.name, resumeURL from user, resume where iduser = user_id order by ts DESC";
    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    mysqli_stmt_execute($statment);

    $timeStamp = "";
    $userName = "";
    $userMajor = "";
    $fileName = "";
    $resumeURL = "";
    $userUni = "";
    mysqli_stmt_bind_result($statment, $timeStamp, $userName, $userMajor, $userUni, $fileName, $resumeURL);

    while ($row = mysqli_stmt_fetch($statment)) {
        $resumeArray[] = array($timeStamp, $userName, $userMajor, $userUni, $fileName, $resumeURL);
    }
    // maybe change table
    foreach($resumeArray as &$row2) {
        echo "<a href='viewresume.php?resumeURL=" . $row2[5] . "' class='list-group-item'>";
        echo "<div class='row'>";
        echo "<div class='col-md-3'>";
        echo $row2[0] . "</div>";
        echo "<div class='col-md-2'>";
        echo $row2[1] . "</div>";
        echo "<div class='col-md-2'>";
        echo $row2[2] . "</div>";
        echo "<div class='col-md-2'>";
        echo "Lehigh University</div>";
        echo "<div class='col-md-3'>";
        echo $row2[4] . "</div>";
        echo "</div></a>";
    }

    mysqli_stmt_close($statment);
    close();
}

function viewByName() {
    connect();
    global $conn;
    $query = "SELECT ts, user.username, user.major, user.universityID, resume.name, resumeURL from user, resume where iduser = user_id order by user.username";
    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    mysqli_stmt_execute($statment);

    $timeStamp = "";
    $userName = "";
    $userMajor = "";
    $fileName = "";
    $resumeURL = "";
    $userUni = "";
    mysqli_stmt_bind_result($statment, $timeStamp, $userName, $userMajor, $userUni, $fileName, $resumeURL);

    while ($row = mysqli_stmt_fetch($statment)) {
        $resumeArray[] = array($timeStamp, $userName, $userMajor, $userUni, $fileName, $resumeURL);
    }
    // maybe change table
    foreach($resumeArray as &$row2) {
        echo "<a href='viewresume.php?resumeURL=" . $row2[5] . "' class='list-group-item'>";
        echo "<div class='row'>";
        echo "<div class='col-md-3'>";
        echo $row2[0] . "</div>";
        echo "<div class='col-md-2'>";
        echo $row2[1] . "</div>";
        echo "<div class='col-md-2'>";
        echo $row2[2] . "</div>";
        echo "<div class='col-md-2'>";
        echo "Lehigh University</div>";
        echo "<div class='col-md-3'>";
        echo $row2[4] . "</div>";
        echo "</div></a>";
    }

    mysqli_stmt_close($statment);
    close();
}

function viewByMajor(){
    connect();
    global $conn;
    $query = "SELECT ts, user.username, user.major, user.universityID, resume.name, resumeURL from user, resume where user_id = iduser order by user.major";
    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    mysqli_stmt_execute($statment);

    $timeStamp = "";
    $userName = "";
    $userMajor = "";
    $fileName = "";
    $resumeURL = "";
    $userUni = "";
    mysqli_stmt_bind_result($statment, $timeStamp, $userName, $userMajor, $userUni, $fileName, $resumeURL);

    while ($row = mysqli_stmt_fetch($statment)) {
        $resumeArray[] = array($timeStamp, $userName, $userMajor, $userUni, $fileName, $resumeURL);
    }
    // maybe change table
    foreach($resumeArray as &$row2) {
        echo "<a href='viewresume.php?resumeURL=" . $row2[5] . "'class='list-group-item'>";
        echo "<div class='row'>";
        echo "<div class='col-md-3'>";
        echo $row2[0] . "</div>";
        echo "<div class='col-md-2'>";
        echo $row2[1] . "</div>";
        echo "<div class='col-md-2'>";
        echo $row2[2] . "</div>";
        echo "<div class='col-md-2'>";
        echo "Lehigh University</div>";
        echo "<div class='col-md-3'>";
        echo $row2[4] . "</div>";
        echo "</div></a>";
    }

    mysqli_stmt_close($statment);
    close();
}

// don't know how to sort by uni


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
       /* @media (min-width: 1000px) {
            .container{
                max-width: 800px;
            }
        }*/
    </style>

</head>

<body>

<div class="header">
    <table align="center" width=100%>
        <tr> <!-- ROW -->
            <td> <!-- COLUMN -->
                <img src="images/finallogo.png" width="300px" style="padding:5px"/>
                <br>
            </td>
            <td>
                <br>
                <p align="right"><a href="logout.php">Sign out</a>&nbsp;</p>
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
                <li><a href="homepage.php">Home</a></li>
                <li><a href="aboutpage.html">About</a><li>
                <li><a href="profilepage.php">Profile</a></li>
                <li><a href="myresume.php">My Resumes</a></li>
                <li class="active"><a href="#">Review Resumes</a><li>
                <li><a href="#">Resources</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="dropdown" align="right">
        <button class="btn btn-default dropdown-toggle" type="button" id="filter"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <span class="glyphicon glyphicon-menu-hamburger"></span>
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="filter">
            <li><a href="reviewresumes.php?filter=name">Name</a></li>
            <li><a href="reviewresumes.php?filter=major">Major</a></li>
            <li><a href="reviewresumes.php?filter=university">University</a></li>
            <li><a href="reviewresumes.php?filter=date">Date Posted</a></li>
        </ul>
    </div>
    <br>
    <br>
    <div class="list-group">
        <?php
            if(isset($_GET['filter'])) {
                $filt = htmlspecialchars($_GET['filter']);
                if(!strcmp($filt, "name")) {
                    viewByName();
                }
                else if (!strcmp($filt, "major")) {
                    viewByMajor();
                }
                else if (!strcmp($filt, "university")) {
                    echo 'view by uni';
                }
                else if(!strcmp($filt, "date")) {
                    viewByTimeStamp();
                }
                else {
                    viewByTimeStamp();
                }
            }
            else {
                viewByTimeStamp();
            }
        ?>
    </div>
</div>


</body>
</html>

