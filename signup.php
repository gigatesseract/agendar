<?php

include 'sqlconnect.php';

   ?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Sign up!!</title>
    <link rel="stylesheet" href="/loginstyle.css">
  </head>
  <body>
<p class = "heading"> Sign UP!!!</p>
<?php

function print_form(){
  echo '<div class = "loginform">';
  echo '<form class="form" action="/signup.php" method="post" id = "signup">';
  echo '<p class = "username"> Enter your username here:- </p>';
  echo  '<input type="text" name="name" placeholder="enter your name here" id = "userinputname" required/> <span class = "error" id = "wrong"></span><span class = "correct" id = "correct"></span><br>';
  echo    '<p class = "username"> Enter your nickname:-</p>';
  echo '<input type="text" name="nick" required > <br>';
  echo '<p class = "username"> Enter your password                          :-      </p>';
  echo    '<input type="password" name="password" value="" required> <br><br>';
  echo '<img id = "captcha" src = "/captcha.php" alt = "captcha">';

  echo '   <input type="text" name="captcha" placeholder = "Enter captcha text here" required>';
  echo '<input type = "button" onclick = "reloadcaptcha()" Value = "Reload"/><br><br>';
  echo '<input type="hidden" name="stage" value="process">';
  echo    '<input type="submit" name="submit" value="submit" id = "submit">';
  echo    '</form>';
  echo '</div>';

}
// echo $_SESSION['code'];
if(isset($_POST['stage']) && $_POST['stage']=='process')
process_form();
else print_form();

function process_form(){
  $conn = mysqli_connect("localhost", "username","password", "deltadb");
  if($_POST['captcha'] != $_SESSION['code']) {
    echo 'Invalid captcha';
    print_form();
  }
  else{
  $name = $_POST['name'];
  $nick = $_POST['nick'];
  $password = crypt($_POST['password'], '34');
  $stmt = mysqli_prepare($conn, "INSERT INTO deltadb.logintable VALUES (?, ?, ?)");
  mysqli_stmt_bind_param($stmt, 'sss', $name, $password, $nick);
  mysqli_execute($stmt);

    echo '<script type="text/javascript">';
  echo 'document.location.replace("/login.php")';
  echo '</script>';

}
}
 ?>
 <p class = "log">Already have an account? click <a href="login.php">  here </a> to log in! </p>
  </body>


  <script type="text/javascript">

function reloadcaptcha(){

document.getElementById('captcha').src = "/captcha.php?" + new Date().getTime();

}

var input = document.getElementById('userinputname');
var str;
  var xhr = new XMLHttpRequest();
var form = document.getElementById('signup');


function sendUsername(){

str = input.value;
console.log(str);
xhr.open("GET","formvalidate.php?name=" + str);
xhr.send();
}
xhr.onreadystatechange = function () {
  if(xhr.readyState === 4 && xhr.status === 200) {
    if(xhr.responseText == "Name is already taken")
    {
      document.getElementById('wrong').innerHTML = xhr.responseText;
     document.getElementById('correct').innerHTML = "";
     document.getElementById('submit').disabled = true;

  }
  else{
    document.getElementById('wrong').innerHTML = "";
   document.getElementById('correct').innerHTML = xhr.responseText;
   document.getElementById('submit').disabled = false;


  }
}
};

input.addEventListener('input', sendUsername);

  </script>
</html>
