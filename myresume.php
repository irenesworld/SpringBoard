<?php
// hi kramer
//hi irene

// what about deleting resumes?

require_once 'connect.php';
require_once 'login.php';

$resumeArray = array();
//viewByTimeStamp();

function viewByTimeStamp(){
    connect();
    global $conn;
    $query = "SELECT ts, name, resumeURL from resume where user_id = ? order by ts DESC";
    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    mysqli_stmt_bind_param($statment, 'i', $_SESSION['userid']);
    mysqli_stmt_execute($statment);


    $timeStamp = "";
    $name = "";
    $resumeURL = "";
    mysqli_stmt_bind_result($statment, $timeStamp, $name, $resumeURL);

    while ($row = mysqli_stmt_fetch($statment)) {
        $resumeArray[] = array($timeStamp, $name, $resumeURL);
    }
    foreach($resumeArray as &$row2) {
        echo "<a href='viewresume.php?resumeURL=". $row2[2] . "' class='list-group-item'>";
        echo "<table><tr><td style='padding-right:50px'>";
        echo $row2[0];
        echo "</td>";
        echo "<td>" . $row2[1] . "</td>";
        echo "</tr></table></a>";
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
                <li><a href="homepage.php">Home</a></li>
                <li><a href="aboutpage.html">About</a><li>
                <li><a href="profilepage.php">Profile</a></li>
                <li class="active"><a href="#">My Resumes</a></li>
                <li><a href="reviewresumes.php">Review Resumes</a><li>
                <li><a href="#">Resources</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <button class="btn btn-default dropdown-toggle" type="button" id="uploadResume" name="uploadResume"
            aria-haspopup="true" aria-expanded="true">
        <span class="glyphicon glyphicon-cloud-upload"></span>
    </button>
    <br>
    <br>
    <div class="list-group">
            <?php
                viewByTimeStamp();
            ?>
    </div>
</div>

<script type="text/javascript" src="http://api.filepicker.io/v2/filepicker.js"></script>
<script type="text/javascript">
    filepicker.setKey("AtYeuZ9vR1ekh6P14vB5az");

    $('#uploadResume').click(function() {
        filepicker.pick( {
            extension: '.pdf'
        }, function(upload){
            console.log(upload.url);
            var filename = upload.filename;
            var url = upload.url;
            var id = upload.id;
            var isWriteable = upload.isWriteable;
            var mimetype = upload.mimetype;
            var size = upload.size;

            window.location.replace("myresumeinter.php/?filename=" + filename + "&resumeURL=" + url);
        });
    });
</script>

</body>
</html>
