<?php
session_start();
include 'sqlconnect.php';

 ?>


<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="/megastyles.css">
  </head>
  <body>
    <div class = "side-heading"> <p>Welcome to your own calendar</p>
      <ul class = "list" >
        You can.....
        <li>Create an account</li>
        <li>Create your own appointments. </li>
        <li>Manage them, schedule invites with other people</li>
      </ul>
    </div>
<p class = "heading"> Log in</p>
    <?php
  if($_SESSION['signup'] == "success") echo '<p class = "success"> Registered Successfully</p>';
    function print_form(){
    echo '<div class = "loginform">';
    echo  '<form class="login" action="login.php" method="post">';
    echo '<span class =  "username"> Enter your username:-    </span>';
    echo   '<input class = "username" type="text" name="username" value="" placeholder="Enter your username" required><br>';
    echo '<span class =  "username"> Enter your password:- </span>';
    echo '<input class = "username" type="password" name="password" value="" required>';
    echo '<input type="hidden" name="stage" value="loginprocess"><br>';
    echo '<input type = "submit" name = "submit" value = "Log In" class = "logsubmit">';
    echo '</form>';
    echo '</div>';
    }

    function process_form(){

       $conn = mysqli_connect("localhost", "username","password", "deltadb");
       $username = $_POST['username'];
      $query = "SELECT NAME, PASSWORD FROM deltadb.logintable where NAME = ?";
      if($stmt = mysqli_prepare($conn, $query)){
        mysqli_stmt_bind_param($stmt, 's', $username);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt, $name, $password);
      while(mysqli_stmt_fetch($stmt));

      if($_POST['username']==$name && crypt($_POST['password'],'34')== $password)
      { $query = "SELECT NICKNAME FROM deltadb.logintable where NAME = ?";
        if($stmt = mysqli_prepare($conn, $query))
        {
          mysqli_stmt_bind_param($stmt, 's', $username);

          mysqli_stmt_execute($stmt);
          mysqli_stmt_bind_result($stmt, $nick);
          mysqli_stmt_fetch($stmt);
          $_SESSION['nick'] = $nick;
          $_SESSION['name'] = $_POST['username'];
        }



        echo '<script type="text/javascript">';
      echo 'document.location.replace("/welcome.php")';
      echo '</script>';
      }
      else {
        echo '<h1 class = "error">Username or password is incorrect. </h1>';
        print_form();
      }

    }
  }

    if(isset($_POST['stage']) && $_POST['stage']=='loginprocess')
    process_form();
    else print_form();

     ?>
<p class = "log">Don't have an account? <br>Click <a href="signup.php"> here </a> to sign up.</p>
  </body>
</html>
