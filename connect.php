<?php


$DB_USERNAME =  'root';
$DB_PASSWORD = 'springboard123';
$DB_HOSTNAME = 'springboard.c4psj6yhsvss.us-east-1.rds.amazonaws.com';
$DB_NAME = 'springboarddb';
$DB_PORT = '8008';
$conn = '';

function connect(){

    global $DB_USERNAME, $DB_PASSWORD, $DB_HOSTNAME, $DB_NAME, $DB_PORT, $conn;
    $conn = mysqli_connect($DB_HOSTNAME, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_PORT);

    if(!$conn){
        die('Error: Could not connect to database.');
    }
}

function close(){
    global $conn;
    $conn = null;

}

?>