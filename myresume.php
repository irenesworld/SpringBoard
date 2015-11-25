<?php
// hi kramer

require_once 'connect.php';
require_once 'login.php';

$resumeArray = array();
viewByTimeStamp();

function viewByTimeStamp(){
    connect();
    global $conn;
    $query = "SELECT ts, name, resumeURL from resume where user_id = ? order by ts DESC";
    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    mysqli_stmt_bind_param($statment, 's', $_SESSION['userid']);
    mysqli_stmt_execute($statment);


    $timeStamp = "";
    $name = "";
    $resumeURL = "";
    mysqli_stmt_bind_result($statment, $timeStamp, $name, $resumeURL);

    while($row = mysqli_stmt_fetch($statment)){
        $resumeArray[] = array($timeStamp, $name, $resumeURL);
    }

    foreach($resumeArray as &$row2){
        foreach($row2 as &$str){
            echo ' '.$str;
        }
    }

    mysqli_stmt_close($statment);
    close();
}

function addResume($name, $url){
    connect();
    global $conn;

    $query = "insert into resume (user_id, name, resumeURL) values(?, ?, ?)";
    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    mysqli_stmt_bind_param($statment, 'iss', $_SESSION['userid'], $name, $url);
    mysqli_stmt_execute($statment);
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

    <script type = "text/javascript">
        count = 0;
        imgs = ["pic1.jpg", "pic2.jpg"];

        function changeFirstPic()
        {
            count++;
            if (count > 2)
                count = 1;
            img1.src = "images/" + imgs[count - 1];
        }
    </script>

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
                <li><a href="#">Review Resumes</a><li>
                <li><a href="#">Resources</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <input type="filepicker" data-fp-apikey="AtYeuZ9vR1ekh6P14vB5az" onchange="alert(event.fpfile.url)">
    <br>
    <br>
    <div class="list-group">
        <a href="#" class="list-group-item">
            <!--<span class="badge">4</span>-->
            <table>
                <tr> <!-- ROW -->
                    <td style="padding-right: 50px;"> <!-- COLUMN -->
                        November 16, 2015
                    </td>
                    <td>
                        IreneLaus_Resume3
                    </td>
                </tr>
            </table>
        </a>
        <a href="#" class="list-group-item">
            <!--<span class="badge">8</span>-->
            <table>
                <tr> <!-- ROW -->
                    <td style="padding-right: 50px;"> <!-- COLUMN -->
                        November 12, 2015
                    </td>
                    <td>
                        IreneLaus_Resume2
                    </td>
                </tr>
            </table>
        </a>
        <a href="#" class="list-group-item">
            <!--<span class="badge">12</span>-->
            <table>
                <tr> <!-- ROW -->
                    <td style="padding-right: 50px;"> <!-- COLUMN -->
                        November 11, 2015
                    </td>
                    <td>
                        IreneLaus_Resume_1
                    </td>
                </tr>
            </table>
        </a>
    </div>
</div>

<script type="text/javascript" src="http://api.filepicker.io/v2/filepicker.js"></script>
<script type="text/javascript">
    filepicker.setKey("AtYeuZ9vR1ekh6P14vB5az");
    filepicker.pick( {
        extension: '.pdf'
    }
    function(upload){
        console.log(upload.url);
        var filename = upload.filename;
        var url = upload.url;
        var id = upload.id;
        var isWriteable = upload.isWriteable;
        var mimetype = upload.mimetype;
        var size = upload.size;
    }
    );
</script>

</body>
</html>
