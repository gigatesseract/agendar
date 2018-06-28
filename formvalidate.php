<?php
include 'sqlconnect.php';

$flag = FALSE;
$str = $_GET['name'];

$conn = mysqli_connect("localhost", "username","password", "deltadb");
$query = "SELECT NAME FROM deltadb.logintable";

if($stmt = mysqli_prepare($conn, $query))
{

  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $name);
  while(mysqli_stmt_fetch($stmt))
  {
    if($name==$str && !$flag){
      $flag = TRUE;
  }

  }
}

  if($flag) echo 'Name is already taken';
  else echo 'You can use that name';

 ?>
