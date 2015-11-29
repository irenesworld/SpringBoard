<?php
// hi kramer
//hi irene

require_once 'connect.php';
require_once 'login.php';

$membership = new Membership();
if(!($membership->isLoggedIn())){
    redirect("../springboard/homepage.php");
    return;
}


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

    $idcomment = "";
    $authorID = "";
    $parent_ID = "";
    $ts = "";
    $commentString = "";
    mysqli_stmt_bind_result($statment, $idcomment, $authorID, $resumeID, $parent_ID, $ts, $commentString);

    while ($row = mysqli_stmt_fetch($statment)) {
        $resumeArray[] = array($idcomment, $authorID, $ts, $commentString, $parent_ID, $ts, $commentString);
    }

    if(!empty($resumeArray)) {
        foreach ($resumeArray as &$row2) {
            if(!$row2[4]){//parent is null

            }else{

            }
        }
    }else{
            echo 'There are currently 0 reumes on the site.';
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
        .thumbnail {
            padding:0px;
            border-style:none;
        }
        .thumbnail img {
            border-radius:50%;
            border-style:none;
        }
        .panel {
            position:relative;
        }
        .panel>.panel-heading:after,.panel>.panel-heading:before{
            position:absolute;
            top:11px;left:-16px;
            right:100%;
            width:0;
            height:0;
            display:block;
            content:" ";
            border-color:transparent;
            border-style:solid solid outset;
            pointer-events:none;
        }
        .panel>.panel-heading:after{
            border-width:7px;
            border-right-color:#f7f7f7;
            margin-top:1px;
            margin-left:2px;
        }
        .panel>.panel-heading:before{
            border-right-color:#ddd;
            border-width:8px;
        }
        .form-control{
            height: 142px;
        }
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

    <!-- populating the comments -->

    <!-- adding comments -->
    <div class="row">
        <div class="col-sm-12">
            <br>
            <h3>Comments</h3>
            <br>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2">
            <div class="thumbnail">
                <img class="img-responsive user-photo" src="https://www.filepicker.io/api/file/f107zsqaRgC9YQg3gYlG/convert?crop=0,280,718,718">
            </div>
        </div>
        <div class="col-sm-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Irene Lau&nbsp;</strong>
                    <span class="text-muted">&nbsp; Dec. 9, 2015&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                </div>
                <div class="panel-body">
                    Comment. Mac & Cheese. Pepperoni Pizza. This resume is awesomeeeeeeeee. Superb. Beautiful. Magnificant.
                </div>
                <div class="panel-footer" style="text-align:right">
                    <span class="glyphicon glyphicon-chevron-up"></span>
                    50
                    <span class="glyphicon glyphicon-chevron-down"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2">
            <div class="thumbnail">
                <img class="img-responsive user-photo" src="https://www.filepicker.io/api/file/Ru0Edq4QGqLhLGSGOv8y/convert?crop=210,0,540,540">
            </div>
        </div>
        <div class="col-sm-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                        <strong>Dan Kramer&nbsp;</strong>
                        <span class="text-muted">&nbsp; Dec. 9, 2015 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                </div>
                <div class="panel-body">
                    Helloooooooooooooooo. Hiiiiiiiiiiiiiiiiiiii. Longgggggggggggg comment. Longerrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr comment.
                    LongggggggggGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG comment. who lives under the pineapple under the sea? spongebob squarepaaaaaaants.
                    aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaabbbbbbbbbbbbbbbbbbbbbbbbb
                </div>
                <div class="panel-footer" style="text-align:right">
                    <span class="glyphicon glyphicon-chevron-up"></span>
                    150
                    <span class="glyphicon glyphicon-chevron-down"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2">
            <div class="thumbnail">
                <img class="img-responsive user-photo" src="https://www.filepicker.io/api/file/TovycatBT52d3SCCIO93/convert?crop=341,0,1366,1366">
            </div>
        </div>
        <div class="col-sm-10">
            <form class="form" role="form">
                <div class="form-group">
                    <textarea class="form-control" rows="3" placeholder="Comment"></textarea>
                </div>
                <div class="form-group" align="right">
                    <button class="btn btn-default">Add</button>
                </div>
            </form>
        </div>
    </div>


</div>

<script type="text/javascript" src="http://api.filepicker.io/v2/filepicker.js"></script>
<script type="text/javascript">
    filepicker.setKey("AtYeuZ9vR1ekh6P14vB5az");
</script>

</body>
</html>

