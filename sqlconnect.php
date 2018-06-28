<?php

    if(!isset($_SESSION))
    {
        session_start();
    }
$conn = mysqli_connect("localhost","username", "password");

//if(!$conn) die("Connection failed".mysqli_connect_error());
$query = "CREATE DATABASE deltadb";
mysqli_query($conn, $query);
//if(!mysqli_query($conn, $query)) die("COnnection failed". mysqli_error($conn));
$query = "CREATE TABLE deltadb.logintable (NAME char(20), PASSWORD char(20), NICKNAME char(20))";
mysqli_query($conn, $query);
//if(!mysqli_query($conn, $query)) die("COnnection failed". mysqli_error($conn));


$query = "CREATE TABLE deltadb.appointtable (NAME char(25), DAT char(5), MONTH char(25), YEAR char(25), TITLE char(20), DESCRIPTION char(200), STARTTIME char(50), ENDTIME char(50))";
mysqli_query($conn, $query);

$query = "CREATE TABLE deltadb.meetingtable (DAT char(5), MONTH char(25), YEAR char(25), STIME char(20), ETIME char(20), INVITED_BY char(25), INVITE_TO char(25), STATUS char(20))";
mysqli_query($conn, $query);










 ?>
