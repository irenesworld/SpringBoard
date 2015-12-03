<?php
// hi kramer
//hi irene 2

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'connect.php';
require_once 'login.php';

if(!(isLoggedIn())){
    redirect("../springboard/homepage.php");
    return;
}

$resumeArray = array();

//addCommentToResume(15, "Test comment. Do not upvote");
function addCommentToResume($resumeID, $commentStr){
    connect();
    global $conn;
    $query = "insert into comment (author_id, resume_id, commentString) values(?,?,?);";
    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    mysqli_stmt_bind_param($statment, 'iis', $_SESSION['userid'], $resumeID, $commentStr);
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
}

/*
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
*/

//getAllCommentsAndVotes();
function getAllCommentsAndVotes()
{
    try {
        connect();
        global $conn;

        $query = "SELECT idcomment, author_id, comment.ts, commentString, iduser, userName, major, pictureURL, university.name from comment, user, university, resume where idresume = ? and author_id = iduser and universityID = iduniversity and idresume = resume_id order by ts";

        $statment = mysqli_prepare($conn, $query);
        if (!$statment) {
            die('mysqli error: ' . mysqli_error($conn));
        }

        $idresume = "";
        if (isset($_GET['idresume'])) {
            $idresume = htmlspecialchars($_GET['idresume']);
        }

        mysqli_stmt_bind_param($statment, 'i', $idresume);
        mysqli_stmt_execute($statment);

        $idcomment = "";
        $authorID = "";
        $parent_ID = "";
        $ts = "";
        $commentString = "";
        $iduser = "";
        $userName = "";
        $major = "";
        $pictureURL = "";
        $universityName = "";

        mysqli_stmt_bind_result($statment, $idcomment, $authorID, $ts, $commentString, $iduser, $userName, $major, $pictureURL, $universityName);

        $rCounter = 0;
        while (mysqli_stmt_fetch($statment)) {
            $resumeArray[$rCounter] = array($idcomment, $authorID, $ts, $commentString, $iduser, $userName, $major, $pictureURL, $universityName);
            $rCounter++;
        }

        mysqli_stmt_close($statment);


        if (!empty($resumeArray)) {
            for ($i = 0; $i < $rCounter; $i++) {
                $query2 = "select * from vote where idc = ?";


                $statment2 = mysqli_prepare($conn, $query2);
                if (!$statment2) {
                    die('mysqli error: ' . mysqli_error($conn));
                }

                mysqli_stmt_bind_param($statment2, 'i', $resumeArray[$i][1]);
                mysqli_stmt_execute($statment2);

                $idc = "";
                $idu = "";
                $postive = "";
                mysqli_stmt_bind_result($statment2, $idc, $idu, $postive);

                $vCounter = 0;
                while (mysqli_stmt_fetch($statment2)) {
                    $voteArray[$vCounter] = array($idc, $idu, $postive);
                    $vCounter++;
                }
                mysqli_stmt_close($statment2);

                if (!empty($voteArray)) {
                    $totalUpVote = 0;
                    $totalDownVote = 0;

                    for ($j = 0; $j < $vCounter; $j++) {
                        if ($voteArray[$j][2] == 1) {
                            $totalUpVote++;
                        } else {
                            $totalDownVote++;
                        }
                    }

                    $totalVotes = $totalUpVote - $totalDownVote;
                } else {
                    $totalVotes = 0;
                }
                array_push($resumeArray[$i], $totalVotes);
            }

            // echo "<br> Resume: ".end($resumeArray)[0]." ".end($resumeArray)[1]." ".end($resumeArray)[2]." ".end($resumeArray)[3]." ".end($resumeArray)[4]." ".end($resumeArray)[5]." ".end($resumeArray)[6]." ".end($resumeArray)[7]." ".end($resumeArray)[8]." ".end($resumeArray)[9]. "<br>";

            ob_start();
            for ($i = 0; $i < $rCounter; $i++) {
                echo '<div class="row">
                        <div class="col-sm-2">
                            <div class="thumbnail">
                                <img class="img-responsive user-photo" src=' . $resumeArray[$i][7] . '>
                            </div>
                        </div>
                        <div class="col-sm-10">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <strong>' . $resumeArray[$i][5] . '&nbsp;</strong>
                                        <span class="text-muted">&nbsp; ' . $resumeArray[$i][2] . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                </div>
                                <div class="panel-body">
                                    ' . $resumeArray[$i][3] . '
                                </div>
                                <div class="panel-footer" style="text-align:right">
                                    <span class="glyphicon glyphicon-chevron-up"></span>
                                        ' . $resumeArray[$i][9] . '
                                    <span class="glyphicon glyphicon-chevron-down"></span>
                                </div>
                            </div>
                        </div>
                     </div>';
            }

            $value = ob_get_contents();
            ob_end_clean();
            echo $value;

            echo ' another line ';
        } else {
            echo 'There are no commments to display';
        }
    }catch (Exception $e){
        echo $e->getMessage();
    }
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
    <?php try{getAllCommentsAndVotes();}catch(Exception $e){echo $e->getMessage();} ?>
    <?php echo ' the next line in the html part '; ?>

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

    <?php echo ' after the nect comment part why doesnt this print'; ?>

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
