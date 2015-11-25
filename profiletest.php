<?php

require_once 'connect.php';
session_start();

$pictureURL = "";
$name = "";
$major = "";
$university = "";

echo 'hi';
//if(isset($_POST['loadpic'])) {
    echo 'goes inside isset(profpic)    ';
    connect();
    global $conn;

    $userEmail = $_SESSION['userEmail'];
    //echo 'user Email:   ';
    //echo $userEmail;
    //echo '   ';

    $query = "SELECT pictureURL, userName, major, university.name from user inner JOIN university on user.universityID=university.iduniversity where email = ? ";

    $statment = mysqli_prepare($conn, $query);
    if ( !$statment ) {
        die('mysqli error: '.mysqli_error($conn));
    }

    //echo '    user email: ';
    //echo $userEmail;

    mysqli_stmt_bind_param($statment, 's', $userEmail);
    mysqli_stmt_execute($statment);
    mysqli_stmt_bind_result($statment, $pictureURL, $name, $major, $university);


    if(mysqli_stmt_fetch($statment)){
       // echo 'PICTURE URLLLL';

        echo 'pictureurl is '.$pictureURL.' name is '.$name.' major is '.$major.' uni is '.$university;
        mysqli_stmt_close($statment);
        close();
        //return true;
    }else{
      //  $load = $pictureURL;
      //  echo 'PICTURE URL ';
      //  echo $pictureURL;
        mysqli_stmt_close($statment);
        close();
        //return false;
    }
//}
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

<!-- AJAX is Asynchronous JavaScript and XML
// loading data in the background and display it
// on the web page without reloading the whole page -->

<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
        </div>
        <div>
            <ul class="nav navbar-nav" >
                <li><a href="hometest.php">Home</a></li>
                <li><a href="aboutpage.html">About</a><li>
                <li class="active"><a href="profiletest.php">Profile</a></li>
                <li><a href="myresume.php">My Resumes</a></li>
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

            <form id="changePassForm" role="form" method="post" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> >
                <div class="form-group">
                    <label for="oldPass">Old password:</label>
                    <input type="password" class="form-control" name="oldPass" id="oldPass" placeholder="Old password">
                </div>

                <div class="form-group">
                    <label for="newPass">New password:</label>
                    <input type="password" class="form-control" name="newPass" id="newPass" placeholder="New password">
                </div>
            </form>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-default" data-dismiss="modal" id="changePass">Change</button>
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

            window.location.replace("profile.php/?changepic=" + picurl);
            $("#profpic").attr("src", blob.url);

        });

    });

    $( "#changePass" ).click(function() {
        var values = $("#changePassForm").serialize();
        window.location.replace("profile.php/?" + values);
    });
</script>

</body>
</html>