<?php
    include 'connect.php';

    echo 'hello';
    connect();

    function createUser($name, $email, $password, $major, $univeristyID){
        $idStatment = "insert into user (iduser, email, password, name, total_votes, major, pictureURL, universityID) VALUES(DEFAULT, $email, $password, $name, 0, $major, DEFAULT, $univeristyID)";
        $return = mysqli_query($idStatment);
    }

    close();
?>