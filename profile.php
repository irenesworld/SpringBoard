// this is needed for profiletest.php
<?php

require_once 'connect.php';
session_start();
echo 'goes into php file';
ob_start();

$pictureURL = "";
$pictureError = "";

if(isset($_GET['loadpic'])) {
    echo 'goes inside isset(profpic)    ';
    connect();
    global $conn;

    $userEmail = $_SESSION['userEmail'];
    echo $userEmail;

    $query = "SELECT pictureURL from user where email = ?";

    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    //echo '    user email: ';
    //echo $userEmail;

    mysqli_stmt_bind_param($statment, 's', $userEmail);
    mysqli_stmt_execute($statment);
    mysqli_stmt_bind_result($statment, $pictureURL);

    if(mysqli_stmt_fetch($statment)){
        // echo 'PICTURE URLLLL';
        // echo $pictureURL;
        mysqli_stmt_close($statment);
        close();
        return true;
    }else{
        //  $load = $pictureURL;
        //  echo 'PICTURE URL ';
        //  echo $pictureURL;
        mysqli_stmt_close($statment);
        close();
        return false;
    }
}

function changePicture($pictureURL){
    echo 'pic url ';
    echo $pictureURL;
    connect();
    global $conn;

    $userEmail = $_SESSION['userEmail'];

    $query = "UPDATE user SET pictureURL = ? where email = ?";

    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    echo '    user email: ';
    echo $userEmail;

    mysqli_stmt_bind_param($statment, 'ss', $pictureURL, $userEmail);
    mysqli_stmt_execute($statment);

    if(mysqli_stmt_fetch($statment)){
        mysqli_stmt_close($statment);
        header("Location: ../profiletest.php");
        close();
        return true;
    }else{
        mysqli_stmt_close($statment);
        header("Location: ../profiletest.php");
        close();
        return false;
    }
}

if(isset($_GET['changepic'])) {
    $pictureURL = htmlspecialchars($_GET['changepic']);

    if(changePicture($pictureURL)){
        //it changed it
        echo $pictureURL;
    }else {
        $pictureError = "Could not change picture";
    }
}

$oldPwd = "";
$newPass = "";
$passwordError = "";
if(isset($_GET['oldPass'])) {
    connect();
    global $conn;
    $oldPass = strip_tags($_GET['oldPass']);
    $newPass = strip_tags($_GET['newPass']);

    $query = "SELECT * from user where email = ? and password = ?";

    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    $result = "";
    mysqli_stmt_bind_param($statment, 'ss', $_SESSION['userEmail'], $oldPass);
    mysqli_stmt_execute($statment);

    if(mysqli_stmt_fetch($statment)){
        mysqli_stmt_close($statment);

        $query2 = "UPDATE user SET password = ? WHERE email = ? and password = ?";

        $statment2 = mysqli_prepare($conn, $query2);
        if ( !$statment2 ) {
            die('mysqli error: '.mysqli_error($conn));
        }

        mysqli_stmt_bind_param($statment2, 'sss', $newPass, $_SESSION['userEmail'], $oldPass);
        mysqli_stmt_execute($statment2);
        $result = "";
        mysqli_stmt_bind_result($statment2, $result);

        if(mysqli_stmt_fetch($statment2)){
            mysqli_stmt_close($statment2);
            close();
            redirect('../profiletest.php');
            //changed password
        }else{
            $passwordError = "Could not change password";
            close();
            redirect('../profiletest.php/?passError='.$passwordError);
        }
    }else{
        mysqli_stmt_close($statment);
        $passwordError = "Incorrect password";
        close();
        redirect('../profiletest.php/?passError='.$passwordError);
    }
    close();

    //header("Location: profiletest.php");
    //ob_end_flush();

}

function redirect($url)
{
    if (!headers_sent())
    {
        header('Location: '.$url);
        exit;
    }
    else
    {
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>'; exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <style>

        img.img-circle {
            border: 2px;
            border-color: white;
        }
        div.border {
            text-align: center;
            border-radius: 5px;
            background-color: #99CCFF;
            margin-top: 10px;
            padding: 15px;
            margin-right: 5px;
        }
        div.centered {
            text-align: center;
        }

    </style>
</head>
<body>

<div class="header">
    <table align="center" width=100%>
        <tr>
            <td>
                <h1>&nbsp;SpringBoard</h1>
            </td>
            <td>
                <br>
                <p align="right">Log in  |  Sign up &nbsp;</p>
            </td>
        </tr>
    </table>
</div>

<!-- AJAX is Asynchronous JavaScript and XML
// loading data in the background and display it
// on the web page without reloading the whole page -->

<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
        </div>
        <div>
            <ul class="nav navbar-nav" >
                <li><a href="#">Home</a></li>
                <li><a href="#">About</a><li>
                <li class="active"><a href="#">Profile</a></li>
                <li><a href="#">My Resumes</a></li>
                <li><a href="#">Review Resumes</a><li>
                <li><a href="#">Resources</a></li>
            </ul>
        </div>
    </div>
</nav>


<div class="container">
    <h1>Profile</h1>

    <div class="row">
        <div class="col-md-3">
            <div class="border">
                <p> </p>
                <div class="centered">
                    <img src="<?php echo $pictureURL ?>" id="profpic" alt="Irene Lau" width="100" >
                </div>
                <br>
                <a href="#"> <div id="pickbutton">
                        Edit photo
                    </div></a>
                <p> </p>
                <p> </p>
                <p><b><?php echo $name ?></b></p>
                <p><b>Votes: </b>11</p>

            </div>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-8">
            <br><br>
            <table class="profile" width=60%>
                <tr>
                    <td> <p><b>Name: </b></p> </td>
                    <td> <p><?php echo $name ?> </p> </td>
                </tr>

                <tr>
                    <td> <p><b>School: </b></p></td>
                    <td> <p>University?</p> </td>
                </tr>

                <tr>
                    <td><p><b>Major: </b> </p> </td>
                    <td><p><?php echo $major ?></p> </td>
                </tr>
                <tr>
                    <td><p> </p></td>
                </tr>

                <tr>
                    <td><p> </p></td>
                </tr>

                <tr>
                    <td><p><b>E-mail: </b></p></td>
                    <td> <p><?php echo $userEmail ?></p> </td>
                </tr>

                <tr>
                    <td><p><b>Password: </b></p></td>
                    <td><p>***********</p></td>
                    <td> <button type="button" class="btn btn-link" data-toggle="modal" data-target="#changePwd">edit</button> </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="http://api.filepicker.io/v2/filepicker.js"></script>

</body>
</html>

