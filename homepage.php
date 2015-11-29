<?php

// $_POST: create an array (ex: array( key -> value, key2 -> value2, ...)
// holds key/value pairs, where keys are the names of the form controls
// and values are the input data from the user

// GET and POST are superglobals; they are always accessible regardless
// of scope. We use POST when info sent from a form is invisible to others

// Important things learned:
// - htmlspecialchars() function converts special characters to HTML entities
//   prevents hackers
// - $_SERVER["PHP_SELF"] is a super global variable that returns the filename
//   of the currently executing script

//CHECK FOR EMAILS

require_once 'connect.php';
require 'login.php';

$check_login = "";
$check_register = "";

$loginEmail = "";
$loginPwd = "";

$loginEmailError = "";
$loginPwdError = "";

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

if(isset($_POST['signin'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["loginEmail"]) || !filter_var($_POST['loginEmail'], FILTER_VALIDATE_EMAIL)) {
            $loginEmailError = "Name is required";
        } else {
            $loginEmail = htmlspecialchars($_POST["loginEmail"]);
        }

        if (empty($_POST["loginPwd"])) {
            $loginPwdError = "Password is required";
        } else {
            $loginPwd = htmlspecialchars($_POST["loginPwd"]);
        }

        $loggedIn = login($loginEmail, $loginPwd);
        if((!$loginEmailError && !$loginPwdError) && !$loggedIn){
            $loginPwdError = "Incorrect email or password";
        }

        if($loggedIn){
            redirect('../springboard/profilepage.php');
        }
    }
}


$name = "";         $email = "";        $password = "";  $major = "";       $universityID = "";
$nameError = "";    $emailError = "";   $pwdError = "";  $majorError = "";  $uniError = "";

if(isset($_POST['register']))
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["name"])) {
            $nameError = "Name is required";
        } else {
            $name = htmlspecialchars($_POST["name"]);
        }

        if (empty($_POST["email"])) {
            $emailError = "Email is required";
        } else {
            $email = htmlspecialchars($_POST["email"]);
        }

        if (empty($_POST["major"])) {
            $majorError = "Major is required";
        } else {
            $major = htmlspecialchars($_POST["major"]);
        }

        if (empty($_POST["pwd"])) {
            $pwdError = "Password is required is required";
        } else {
            $password = htmlspecialchars($_POST["pwd"]);
        }

        if (empty($_POST["university"])) {
            $uniError = "Password is required is required";
        } else {
            $universityID = htmlspecialchars($_POST["university"]);
        }

        if (!$nameError && !$emailError && !$pwdError && !$majorError && !$uniError) {
            createUser($name, $email, $password, $major, $universityID);
            redirect('../springboard/profilepage.php');
        }
    }
}

function createUser($name, $email, $password, $major, $universityID){
    connect();
    global $conn;

    $query = "INSERT INTO user (email, password, userName, total_votes, major, universityID) VALUES(?, ?, ?, ?, ?, ?)";

    $statment = mysqli_prepare($conn,$query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }
    $votes = 0;
    $uni = 1;
    mysqli_stmt_bind_param($statment, 'sssisi', $email, $password, $name, $votes, $major, $uni);
    mysqli_stmt_execute($statment);

    login($email, $password);

    mysqli_stmt_close($statment);
    close();
}

$enabled = "disabled";
if(isset($_SESSION)) {
    $enabled = "enabled";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>About</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

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
                <p align="right">Log in  |  Sign up &nbsp;</p>
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
                <li class="active"><a href="homepage.php" id="homelink">Home</a></li>
                <li><a href="aboutpage.html" id="aboutlink" class="disabled">About</a><li>
                <li class="<?php echo $enabled ?>"><a href="profilepage.php">Profile</a></li>
                <li class="<?php echo $enabled ?>"><a href="myresume.php">My Resumes</a></li>
                <li class="<?php echo $enabled ?>"><a href="reviewresumes.php">Review Resumes</a><li>
                <li class="<?php echo $enabled ?>"><a href="#">Resources</a></li>
            </ul>
        </div>
    </div>
</nav>


<div class="container">
    <center><img id = "img1" src="images/pic1.jpg" height="85%" width="85%"/></center>
    <br>
    <br>
</div>

<script type = "text/javascript">
img1 = document.getElementById("img1");
    setInterval("changeFirstPic()", 5000);
</script>

<div class="container">
    <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-5" style="padding-right:40px;">
            <br>
            <h1 class="text-center login-title">Sign in</h1>
            <br>
            <form class="form-horizontal" role="form" method="post" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> >
                <div class="form-group">
                    <label for="loginEmail">Email:</label>
                    <input type="text" class="form-control" name="loginEmail" id="loginEmail" placeholder="Email Address" >
                   <span class="error"> <?php echo $loginEmailError;?></span>
                </div>
                <div class="form-group">
                    <label for="loginPwd">Password:</label>
                    <input type="password" class="form-control" name="loginPwd" id="loginPwd" placeholder="Password">
                    <span class="error"> <?php echo $loginPwdError;?></span>
                </div>
               <button class="btn btn-lg btn-primary btn-block" name="signin" type="submit">
                     Sign in
                </button>
            </form>
        </div>

        <div class="col-md-5" style="padding-left:40px; border-left: 1px solid #ccc;">
            <br>
            <h1 class="text-center login-title">New User? Sign Up!</h1>
            <br>
            <form class="form-horizontal" role="form" method="post" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> >
                <div class="form-group">
                    <label for="name">Full name:</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Full name">
                    <span class="error"> <?php echo $nameError;?></span>
                </div>
                <div class="form-group">
                    <label for="emails">Email:</label>
                    <input type="text" class="form-control" name="email" id="email" placeholder="Email Address">
                    <span class="error"> <?php echo $emailError;?></span>
                </div>
                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input type="text" class="form-control" name="pwd" id="pwd" placeholder="Password">
                    <span class="error"> <?php echo $pwdError;?></span>
                </div>
                <div class="form-group">
                    <label for="university">School:</label>
                    <select class="form-control" name="university" id="university" placeholder="University">
                    <option>Lehigh University</option>
                        </select>
                        <span class="error"> <?php echo $uniError;?></span>
                </div>
                <div class="form-group">
                    <label for="major">Major:</label>
                    <input type="text" class="form-control" name="major" id="major" placeholder="Major">
                    <span class="error"> <?php echo $majorError;?></span>
                </div>
                <button class="btn btn-lg btn-primary btn-block" type="submit" name="register" value="register">
                    Register
                </button>
                <br>
                <br>
            </form>
        </div>
        <div class="col-md-1">
        </div>
    </div>
</div>

<br>
<br>

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

</body>
</html>