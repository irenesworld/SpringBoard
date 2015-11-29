<?php
// hi kramer
//hi irene

/*require_once 'connect.php';
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
}*/

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
                <li><a href="myresume.php">My Resumes</a></li>
                <li><a href="reviewresumes.php">Review Resumes</a><li>
                <li><a href="#">Resources</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <br>
    <br>
    <?php
        if(isset($_GET['resumeURL'])) {
            $resumeURL = htmlspecialchars($_GET['resumeURL']);
            //echo $resumeURL;
        }
    ?>
    <div type="filepicker-preview" data-fp-url="<?php echo $resumeURL ?>" style="text-align:center width:75%; height:500px"> </div>

</div>

<script type="text/javascript" src="http://api.filepicker.io/v2/filepicker.js"></script>
<script type="text/javascript">
    filepicker.setKey("AtYeuZ9vR1ekh6P14vB5az");
</script>

</body>
</html>

