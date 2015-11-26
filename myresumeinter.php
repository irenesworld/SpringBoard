<?php
// this page is the intermediary page
// it will add resume to database

require_once 'connect.php';
session_start();

if(isset($_GET['filename'])) {
    $name = strip_tags($_GET['filename']);
    $url = strip_tags($_GET['resumeURL']);

    echo '   ';
    echo $name;
    echo '   ';
    echo $url;
    echo '   ';
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

//header("Location: ../myresume.php");
redirect("../myresume.php");

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