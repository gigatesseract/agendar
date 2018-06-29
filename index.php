<?php

   include 'sqlconnect.php';
 ?>

<html>
<head>
  <link rel="stylesheet" href="/loginstyle.css">
<title>Welcome!!!!!</title>
</head>
<body>
  <h1>
<p class = "heading">Welcome to your virtual Manager.</p>
<ul class = "list" >
  You can.....
  <li>Create an account</li>
  <li>Create your own appointments. </li>
  <li>Manage them, schedule invites with other people</li>
</ul>
</h1>
<p class = "sign">Click <a href="signup.php"> here </a> to sign up.</p>

<p class = "log">Already have an account? click <a href="login.php">  here </a> to log in! </p>
<?php


//if(!$conn) die('Connection failed '.mysqli_connect_error());

?>
</body>
</html> 
