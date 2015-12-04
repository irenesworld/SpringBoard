<?php
// hi kramer
//hi irene 2
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
    $query = "SELECT ts, user.username, user.major, user.universityID, idresume, resume.name, resumeURL from user, resume where idresume != 24 and idresume != 23 and idresume != 22 and idresume != 19 and iduser = user_id order by ts DESC";
    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    mysqli_stmt_execute($statment);

    $timeStamp = "";
    $userName = "";
    $userMajor = "";
    $fileName = "";
    $idresume = "";
    $resumeURL = "";
    $userUni = "";
    mysqli_stmt_bind_result($statment, $timeStamp, $userName, $userMajor, $userUni, $idresume,  $fileName, $resumeURL);

    while ($row = mysqli_stmt_fetch($statment)) {
        $resumeArray[] = array($timeStamp, $userName, $userMajor, $userUni, $idresume, $fileName, $resumeURL);
    }

    // maybe change table
    $str = "";
    foreach($resumeArray as $row2) {
        //echo $row2[4]. "&resumeURL=" . $row2[6];
        $str .=
             "<a href='viewresume.php?idresume=". $row2[4]. "&resumeURL=" . $row2[6] . "' class='list-group-item'>"
             ."<div class='row'>"
              . "<div class='col-md-3'>"
                . $row2[0] . "</div>"
         . "<div class='col-md-2'>"
         . $row2[1] . "</div>"
         . "<div class='col-md-2'>"
         . $row2[2] . "</div>"
         //."<div class='col-md-2'>"
         //."Lehigh University"
         //. "<div class='col-md-3'>"
         //. $row2[4] . "</div>"
         . "</div></a>";
        /* echo $row2[0];
         echo $row2[1];
         echo $row2[2];
         echo $row2[4];
         echo $row2[5];*/
    }
    echo($str);
    mysqli_stmt_close($statment);
    close();
}

function viewByName() {
    connect();
    global $conn;
    $query = "SELECT ts, user.username, user.major, user.universityID, idresume, resume.name, resumeURL from user, resume where idresume != 24 and idresume != 23 and idresume != 22 idresume != 19 and and iduser = user_id order by user.username";
    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    mysqli_stmt_execute($statment);

    $timeStamp = "";
    $userName = "";
    $userMajor = "";
    $fileName = "";
    $idresume = "";
    $resumeURL = "";
    $userUni = "";
    mysqli_stmt_bind_result($statment, $timeStamp, $userName, $userMajor, $userUni, $idresume, $fileName, $resumeURL);

    while ($row = mysqli_stmt_fetch($statment)) {
        $resumeArray[] = array($timeStamp, $userName, $userMajor, $userUni, $idresume, $fileName, $resumeURL);
    }
    // maybe change table
    foreach($resumeArray as &$row2) {
        echo "<a href='viewresume.php?idresume=". $row2[4]. "&resumeURL=" . $row2[6] .  "' class='list-group-item'>";
        echo "<div class='row'>";
        echo "<div class='col-md-3'>";
        echo $row2[0] . "</div>";
        echo "<div class='col-md-3'>";
        echo $row2[1] . "</div>";
        echo "<div class='col-md-3'>";
        echo $row2[2] . "</div>";
       // echo "<div class='col-md-2'>";
       // echo "Lehigh University</div>";
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
    $query = "SELECT ts, user.username, user.major, user.universityID, idresume, resume.name, resumeURL from user, resume where idresume != 24 and idresume != 23 and idresume != 22 idresume != 19 and and user_id = iduser order by user.major";
    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    mysqli_stmt_execute($statment);

    $timeStamp = "";
    $userName = "";
    $userMajor = "";
    $fileName = "";
    $idresume = "";
    $resumeURL = "";
    $userUni = "";
    mysqli_stmt_bind_result($statment, $timeStamp, $userName, $userMajor, $userUni, $idresume, $fileName, $resumeURL);

    while ($row = mysqli_stmt_fetch($statment)) {
        $resumeArray[] = array($timeStamp, $userName, $userMajor, $userUni, $idresume, $fileName, $resumeURL);
    }
    // maybe change table
    foreach($resumeArray as &$row2) {
        echo "<a href='viewresume.php?idresume=". $row2[4]. "&resumeURL=" . $row2[6] . "'class='list-group-item'>";
        echo "<div class='row'>";
        echo "<div class='col-md-3'>";
        echo $row2[0] . "</div>";
        echo "<div class='col-md-2'>";
        echo $row2[1] . "</div>";
        echo "<div class='col-md-2'>";
        echo $row2[2] . "</div>";
       // echo "<div class='col-md-2'>";
       // echo "Lehigh University</div>";
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
    <title>Review Resumes</title>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="images/favicon-32x32.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="styling.css">

    <style>
       /* @media (min-width: 1000px) {
            .container{
                max-width: 800px;
            }
        }*/
    </style>

</head>

<body>

<div class="custom-navbar">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <img src="images/finallogo.png" width="250px">
            </div>
            <div>
                <ul class="nav navbar-nav" >
                    <li>&nbsp;&nbsp;&nbsp;</li>
                    <li><a href="homepage.php">Home</a></li>
                    <li><a href="aboutpage.html">About</a><li>
                    <li><a href="profilepage.php">Profile</a></li>
                    <li><a href="myresume.php">My Resumes</a></li>
                    <li class="active"><a href="reviewresumes.php">Review Resumes</a><li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="logout.php">Sign out</a></li>
                </ul>
            </div>
        </div>
    </nav>
</div>

<div class="container">
    <div class="dropdown" align="right">
        <button class="btn btn-primary dropdown-toggle" type="button" id="filter"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <span class="glyphicon glyphicon-menu-hamburger"></span>
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="filter">
            <li><a href="reviewresumes.php?filter=name">Name</a></li>
            <li><a href="reviewresumes.php?filter=major">Major</a></li>
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

