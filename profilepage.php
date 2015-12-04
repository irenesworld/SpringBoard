<?php

require_once 'connect.php';
require_once 'login.php';

if(!(isLoggedIn())){
    redirect("../springboard/homepage.php");
    return;
}

$pictureURL = "";
$name = "";
$major = "";
$university = "";

connect();
global $conn;

$userEmail = $_SESSION['userEmail'];

$query = "SELECT pictureURL, userName, major, university.name from user inner JOIN university on user.universityID=university.iduniversity where email = ? ";

$statment = mysqli_prepare($conn, $query);
if ( !$statment ) {
    die('mysqli error: '.mysqli_error($conn));
}

mysqli_stmt_bind_param($statment, 's', $userEmail);
mysqli_stmt_execute($statment);
mysqli_stmt_bind_result($statment, $pictureURL, $name, $major, $university);


if(mysqli_stmt_fetch($statment)){
    mysqli_stmt_close($statment);
    close();
}else{
    mysqli_stmt_close($statment);
    close();
}

$pwdError = "";
if(isset($_POST['changePass'])) {

    $oldPass = htmlspecialchars($_POST['oldPass']);
    $newPass = htmlspecialchars($_POST['newPass']);
    connect();
    global $conn;
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
        //$result = "";
        //mysqli_stmt_bind_result($statment2, $result);

       // if(mysqli_stmt_fetch($statment2)){
            mysqli_stmt_close($statment2);
            close();
            //changed password
       // }else{
        //    $pwdError = "Could not change password";
        //    close();
       // }
    }else{
        mysqli_stmt_close($statment);
        $pwdError = "Incorrect password";
        close();
    }
    close();

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profile</title>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="images/favicon-32x32.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="styling.css">
    <style>
        .body {
            color: #38baf2;
        }
        img.img-circle {
            border-radius: 5px;
            border-style: solid;
            border-color: white;
            border-width: 2px;
            box-shadow: 2px 2px 2px #888888;
        }
        div.border {
            text-align: center;
            border-radius: 5px;
            background-color: transparent;
            border-style: solid;
            border-color: #38baf2;
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
                    <li class="active"><a href="profilepage.php">Profile</a></li>
                    <li><a href="myresume.php">My Resumes</a></li>
                    <li><a href="reviewresumes.php">Review Resumes</a><li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="logout.php">Sign out</a></li>
                </ul>
            </div>
        </div>
    </nav>
</div>
<!-- AJAX is Asynchronous JavaScript and XML
// loading data in the background and display it
// on the web page without reloading the whole page -->
<div class="container">

    <div class="row">
        <div class="col-md-3">
            <div class="border">
                <p> </p>
                <div class="centered">
                    <img src="<?php echo $pictureURL ?>" id="profpic" class="img-circle" alt="Irene Lau" width="200" >
                </div>
                <br>
                <a href="#"> <div id="pickbutton">
                        Edit photo
                    </div></a>
                <p> </p>
                <p> </p>
                <p><b><?php echo $name ?></b></p>

            </div>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-8">
            <br>
            <h3 style="color:#38baf2;">Profile</h3>
            <br>
            <table class="profile" width=60%>
                <tr>
                    <td> <p><b>Name: </b></p> </td>
                    <td> <p><?php echo $name ?> </p> </td>
                </tr>

                <tr>
                    <td> <p><b>School: </b></p></td>
                    <td> <p><?php echo $university ?> </p> </td>
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

                <tr>
                    <?php echo "<p class='text-danger'>$pwdError</p>";?>
                </tr>
            </table>
        </div>
    </div>
</div>

<div id="changePwd" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm" style="background-color: white">
        <div class="modal-header">
            <h2 class="modal-title">Password Change</h2>
        </div>

        <div class="modal-body">

            <form role="form" method="post" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> >
                <div class="form-group">
                    <label for="oldPass">Old password:</label>
                    <input type="password" class="form-control" name="oldPass" id="oldPass" placeholder="Old password">
                </div>

                <div class="form-group">
                    <label for="newPass">New password:</label>
                    <input type="password" class="form-control" name="newPass" id="newPass" placeholder="New password">
                </div>

                <button class="btn btn-lg btn-primary btn-block" type="submit" name="changePass" value="changePass">
                    Change Password
                </button>
            </form>
        </div>

    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="http://api.filepicker.io/v2/filepicker.js"></script>
<script type="text/javascript">
    console.log("hello");
    console.log(filepicker);
    filepicker.setKey("AtYeuZ9vR1ekh6P14vB5az");

    $('#pickbutton').click(function() {
        filepicker.pick({
            mimetype: 'image/*', /* Images only */
            maxSize: 1024 * 1024 * 5, /* 5mb */
            imageMax: [1500, 1500], /* 1500x1500px */
            cropRatio: 1/1, /* Perfect squares */
            services: ['*'] /* From anywhere */
        }, function(blob) {

            // Returned stuff for example
            var filename = blob.filename;
            var picurl = blob.url;
            var id = blob.id;
            var isWriteable = blob.isWriteable;
            var mimetype = blob.mimetype;
            var size = blob.size;

            // Save to a database somewhere
            // Alternatively you can have filepicker do it for you: https://www.filepicker.com/documentation/storage/

            window.location.replace("profileinter.php/?changepic=" + picurl);
            $("#profpic").attr("src", blob.url);

        });

    });

</script>

</body>
</html>
