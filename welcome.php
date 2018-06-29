<?php

include 'sqlconnect.php';
include 'calendar.php';


 ?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>My Manager</title>
  </head>
  <body>
    <div id = "blanket">
<p>WElcome, <?php echo $_SESSION['nick']; ?></p>

    <?php
    if(isset($_POST['jumpset']))
    {
      $month = $_POST['jmonth'];
      $year = $_POST['jyear'];
      unset($_POST['jumpset']);
    }
draw_calendar($month, $year);
print_jumptodate();


    if(isset($_POST['processappointment']))
    {
      process_appointment();
      unset($_POST['newapp']);
    }

   if(isset($_POST['newappointment']))
   {
     create_appointment();
     unset($_POST['newappointment']);

   }
   if(isset($_POST['pending']))
   {
     process_pending();
     unset($_POST['pending']);
   }
   if(isset($_POST['apphidden'])){
     delete_appointment();
     unset($_POST['apphidden']);

   }


   function print_meeting(){

     echo '<h4> Schedule a meeting with your friend </h4>';
     echo '<div class = "meeting"><form></form>';
     echo '<form class="invform" method="post" action = "welcome.php" id = "iform">';
     echo '<span> Enter username of friend </span><input type = "text" name = "invitee" placeholder = "Invite your friend" required><br>';
     echo '<span> From time:- </span>';
     echo '<input type = "time" name = "stime" required/><span class = white-space></span>';
     echo '<span> To:-  </span>';
     echo '<input type = "time" name = "etime" required/><br>';
     echo '<span> On </span>';
     echo '<input type = "date" name = "dmy" id = "idate" required/>';
     echo '<input type = "hidden" name = "invite" value = "inviteset" />';
     echo '<input type = "submit" name = "submit" value = "Invite"/>';
     echo '</form>';
     echo '</div>';

   }

// if(!function_exists('create_appointment')){
    function create_appointment(){
      echo '<h3> Create an appointment for '.$_POST['date'].'/'.$_POST['month'].'/'.$_POST['year'].'</h3>';

    echo   '<form class="" action="welcome.php" method="post">';
    echo '   <span> Enter title of the appointment</span> <input type="text" name="title" value="" placeholder="Title" required/><br>';
    echo '   <span> Optional Description:- </span> <input type="textarea" name="description" value="" placeholder="Descri"/><br>';
    echo '   <span> Start time of the appointment </span> <input type="time" name="start" value="" placeholder="start time" required><br>';
    echo '  <span> End time of the appointment </span><input type="time" name="end" value="" placeholder="end time" required><br>';
    echo    '<input type="submit" name="submit" value="Create Appointment">';
    echo  '   <input type="hidden" name="processappointment" value="newappset">';

         // if(isset($_POST['processapp'])){
         echo '<input type = "hidden" name = "date" value = "'.$_POST['date'].'">';
         echo '<input type = "hidden" name = "month" value = "'.$_POST['month'].'">';
         echo '<input type = "hidden" name = "year" value = "'.$_POST['year'].'">';






    }


function process_meeting(){
  $conn = mysqli_connect("localhost","username", "password", "deltadb");
  $flag = FALSE;
$dmy = $_POST['dmy'];
$stime = $_POST['stime'];
$etime = $_POST['etime'];
$date = (int)(substr($dmy, 8,9));
$month = (int)(substr($dmy, 5, 6));
$year = (int)(substr($dmy, 0, 4));
$name = $_SESSION['name'];
// $query = "SELECT NAME FROM deltadb.logintable WHERE NAME = '".$_POST['invitee']."'";
// if(!mysqli_query($conn, $query)) echo mysqli_error($conn);
if($stmt = mysqli_prepare($conn, "SELECT NAME FROM deltadb.logintable WHERE NAME = ?")){

  mysqli_stmt_bind_param($stmt, 's', $_POST['invitee']);
 mysqli_stmt_execute($stmt);
 mysqli_stmt_store_result($stmt);
 if(mysqli_stmt_num_rows($stmt)==0)
 {
   echo 'There is no user with that username';
   $flag =  TRUE;
 }

 }


if(!$flag){



$status = "Not confirmed";
$query = "INSERT INTO deltadb.meetingtable VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'iiisssss', $date, $month, $year, $stime, $etime, $name, $_POST['invitee'], $status);
mysqli_stmt_execute($stmt);


}
echo '<script type="text/javascript">';
echo 'document.location.replace("/welcome.php")';
echo '</script>';

}

function delete_appointment(){
    $conn = mysqli_connect("localhost","username", "password", "deltadb");
 $date = $_POST['appdate'];
 $month = $_POST['appmonth'];
 $year = $_POST['appyear'];
 $stime = $_POST['appstime'];
 $etime = $_POST['appetime'];
 if($stmt = mysqli_prepare($conn, "DELETE FROM deltadb.appointtable WHERE NAME = ? AND DAT = ? AND MONTH = ? AND YEAR = ? AND STARTTIME = ? AND ENDTIME = ?"))
{
  mysqli_stmt_bind_param($stmt, 'siiiss', $_SESSION['name'], $date, $month, $year, $stime, $etime);
  mysqli_execute($stmt);
  echo '<script type="text/javascript">';
echo 'document.location.replace("/welcome.php")';
echo '</script>';

}

}

    function process_appointment(){
      echo 'hihihihi';
      $flag = FALSE;
      $conn = mysqli_connect("localhost","username", "password", "deltadb");
    $title = $_POST['title'];
    $descri = $_POST['description'];
    $stime = $_POST['start'];
    $etime = $_POST['end'];
    $date = $_POST['date'];
    $month = $_POST['month'];
    $year = $_POST['year'];

    $query = "SELECT STARTTIME, ENDTIME FROM deltadb.appointtable WHERE DAT = ? AND MONTH = ? AND YEAR = ? AND NAME = ?";

  if($stmt = mysqli_prepare($conn, $query)){
      mysqli_stmt_bind_param($stmt, 'iiis', $date, $month, $year, $_SESSION['name']);
     mysqli_stmt_execute($stmt);
     mysqli_stmt_bind_result($stmt, $starttime, $endtime);
     while(mysqli_stmt_fetch($stmt)){


       if($starttime==$stime && $endtime==$etime)
       {echo '<p> You already have an appointment during that time.</p>';
       create_appointment();
       $flag = TRUE;
     }

     }

   }
    if(!$flag){


      if($stmt = mysqli_prepare($conn, "INSERT INTO deltadb.appointtable VALUES (?, ?, ?, ?, ?, ?, ?, ?)"))
      {
        mysqli_stmt_bind_param($stmt, 'siiissss', $_SESSION['name'], $date, $month, $year, $title, $descri, $stime, $etime);
        mysqli_execute($stmt);
        echo 'hi';
      }



    echo '<script type="text/javascript">';
  echo 'document.location.replace("/welcome.php")';
  echo '</script>';

  }





    }


  function process_pending(){

  $conn = mysqli_connect("localhost","username", "password", "deltadb");
    $bywhom = $_POST['bywhom'];
    $invdate = $_POST['invdate'];
    $invmonth = $_POST['invmonth'];
    $invyear = $_POST['invyear'];
    $invstime = $_POST['invstime'];
    $invetime = $_POST['invetime'];

    if($_POST['submitinvite']=="Decline")
    {
      $status = "Declined";
      $stmt = mysqli_prepare($conn, "UPDATE deltadb.meetingtable SET STATUS = ? WHERE DAT = ? AND MONTH = ? AND YEAR = ? AND STIME = ? AND ETIME = ? AND INVITED_BY = ?");
      mysqli_stmt_bind_param($stmt, 'siiisss', $status, $invdate, $invmonth, $invyear, $invstime, $invetime, $bywhom);
      mysqli_execute($stmt);
    }
    else if($_POST['submitinvite']=="Accept")
    {
      $status = 'Accepted';
      if($stmt = mysqli_prepare($conn, "UPDATE deltadb.meetingtable SET STATUS = ? WHERE DAT = ? AND MONTH = ? AND YEAR = ? AND STIME = ? AND ETIME = ? AND INVITED_BY = ?")){
      mysqli_stmt_bind_param($stmt, 'siiisss', $status, $invdate, $invmonth, $invyear, $invstime, $invetime, $bywhom);
      mysqli_execute($stmt);}
      $title = 'MEETING';
      $descri = 'Meeting with '.$bywhom;
      if($stmt = mysqli_prepare($conn, "INSERT INTO deltadb.appointtable VALUES (?, ?, ?, ?, ?, ?, ?, ?)")){
      mysqli_stmt_bind_param($stmt, 'siiissss', $_SESSION['name'], $invdate, $invmonth, $invyear, $title, $descri, $invstime, $invetime);
      mysqli_execute($stmt);}
        $descri = 'Meeting with '.$_SESSION['name'];
      if($stmt = mysqli_prepare($conn, "INSERT INTO deltadb.appointtable VALUES (?, ?, ?, ?, ?, ?, ?, ?)")){
      mysqli_stmt_bind_param($stmt, 'siiissss', $bywhom, $invdate, $invmonth, $invyear, $title, $descri, $invstime, $invetime);
      mysqli_execute($stmt);}

    }

  }

   echo '</div>';
   $daysinmonth = date('t', mktime(0,0,0,$month, 1, $year));
   echo '<div class = "display-popup">';
   for($i=1;$i<=$daysinmonth;$i++)
   echo display_popup($i, $month, $year);
   echo '</div>';
   print_meeting();

if(isset($_POST['dmy']))
process_meeting();


     ?>
<div class="pending" id = 'pending'>


</div>
  </body>
  <script type="text/javascript">


  function showPopup(id, days){
   var blanket = document.getElementById('blanket');
  blanket.style.opacity = "0.2";
   blanket.style.zIndex = "9001";
   //var string = toString(id);
   for(i=1;i<=days;i++)
   {
     if(i==id){
     document.getElementById(i).style.display = "block";

     document.getElementById(i).style.zIndex = "9002";
   }
     else
      document.getElementById(i).style.display = "none";
    }

  }
  function hidePopup(id, days){
    var blanket = document.getElementById('blanket');
    blanket.style.opacity = "1";

    for(i=1;i<=days;i++)
    document.getElementById(id).style.display = "none";
  }

  var idate = document.getElementById('idate');

  var xhr = new XMLHttpRequest();
  var div = document.getElementById('pending');
function showPending(){

  xhr.open("GET", "pendingdisplay.php");
  xhr.send();

}
xhr.onreadystatechange = function () {
  if(xhr.readyState === 4 && xhr.status === 200) {
    div.innerHTML = xhr.responseText;
  }

};

setInterval(showPending, 100);





  </script>
</html>
