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
draw_calendar($month, $year);


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


   function print_meeting(){

     echo '<h4> Schedule a meeting with your friend </h4>';
     echo '<div class = "meeting">';
     echo '<form class="invform" method="post" action = "welcome.php" id = "iform" >';
     echo '<input type = "text" name = "invitee" placeholder = "INvite your friend">';
     echo '<span> From </span>';
     echo '<input type = "time" name = "stime"/>';
     echo '<span> To </span>';
     echo '<input type = "time" name = "etime"/>';
     echo '<span> On </span>';
     echo '<input type = "date" name = "dmy" id = "idate" required/>';
     echo '<input type = "hidden" name = "invite" value = "inviteset" />';
     echo '<input type = "submit" name = "submit" value = "Invite"/>';
     echo '</form></div>';

   }

// if(!function_exists('create_appointment')){
    function create_appointment(){
      echo '<h3> Create an appointment for '.$_POST['date'].'/'.$_POST['month'].'/'.$_POST['year'].'</h3>';

    echo   '<form class="" action="welcome.php" method="post">';
    echo '    <input type="text" name="title" value="" placeholder="Title"/>';
    echo '    <input type="textarea" name="description" value="" placeholder="Descri"/>';
    echo '    <input type="time" name="start" value="" placeholder="start time">';
    echo    '<input type="time" name="end" value="" placeholder="end time">';
    echo    '<input type="submit" name="submit" value="Create Appointment">';
    echo  '   <input type="hidden" name="processappointment" value="newappset">';

         // if(isset($_POST['processapp'])){
         echo '<input type = "hidden" name = "date" value = "'.$_POST['date'].'">';
         echo '<input type = "hidden" name = "month" value = "'.$_POST['month'].'">';
         echo '<input type = "hidden" name = "year" value = "'.$_POST['year'].'">';






    }


function process_meeting(){
  $conn = mysqli_connect("localhost","username", "password", "deltadb");
$dmy = $_POST['dmy'];
$stime = $_POST['stime'];
$etime = $_POST['etime'];
$date = (int)(substr($dmy, 8,9));
$month = (int)(substr($dmy, 5, 6));
$year = (int)(substr($dmy, 0, 4));
$name = $_SESSION['name'];
$query = "INSERT INTO deltadb.meetingtable VALUES ('".$date."', '".$month."', '".$year."','".$stime."', '".$etime."', '".$name."', '".$_POST['invitee']."', 'Not confirmed')";
if(!mysqli_query($conn, $query)) echo mysqli_error($conn);

}

    function process_appointment(){
      $flag = FALSE;
      $conn = mysqli_connect("localhost","username", "password", "deltadb");
    $title = $_POST['title'];
    $descri = $_POST['description'];
    $stime = $_POST['start'];
    $etime = $_POST['end'];
    $date = $_POST['date'];
    $month = $_POST['month'];
    $year = $_POST['year'];

    $query = "SELECT STARTTIME, ENDTIME FROM deltadb.appointtable WHERE DAT = '".$date."' AND MONTH = '".$month."' AND YEAR = '".$year."'";
    if($stime=='') {
      echo 'Start-time can\'t be empty';
      create_appointment();
    }
    else if($etime=='') {
      echo 'ENd time can\t be empty';
      create_appointment();
    }

    else if($stmt = mysqli_prepare($conn, $query)){
     mysqli_stmt_execute($stmt);
     mysqli_stmt_bind_result($stmt, $starttime, $endtime);
     while(mysqli_stmt_fetch($stmt)){

       if($starttime==$stime && $endtime==$etime)
       echo '<p> You already have an appointment during that time.</p>';
       create_appointment();
       $flag = TRUE;

     }
   }
    if(!$flag){$query = "INSERT INTO deltadb.appointtable VALUES ('".$date."', '".$month."', '".$year."', '".$title."', '".$descri."', '".$stime."', '".$etime."')";
    if(!mysqli_query($conn, $query)) echo 'Time cannot be left empty';
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
    echo $bywhom;
    if($_POST['submitinvite']=="Decline")
    {
      $query = "UPDATE deltadb.meetingtable SET STATUS = 'Declined' WHERE DAT = '".$invdate."' AND MONTH = '".$invmonth."' AND YEAR = '".$invyear."' AND STIME = '".$invstime."' AND ETIME = '".$invetime."' AND INVITED_BY = '".$bywhom."'";
      if(!mysqli_query($conn, $query)) echo mysqli_error($conn);
    }
    else if($_POST['submitinvite']=="Accept")
    {
      $query = "UPDATE deltadb.meetingtable SET STATUS = 'Accepted' WHERE DAT = '".$invdate."' AND MONTH = '".$invmonth."' AND YEAR = '".$invyear."' AND STIME = '".$invstime."' AND ETIME = '".$invetime."' AND INVITED_BY = '".$bywhom."'";
      if(!mysqli_query($conn, $query)) echo mysqli_error($conn);
      $query = "INSERT INTO deltadb.appointtable VALUES ('".$_SESSION['name']."', '".$invdate."', '".$invmonth."', '".$invyear."', 'MEETING', 'Meetup with '"."'".$bywhom."', '".$invstime."', '".$invetime."')";
      if(!mysqli_query($conn, $query)) echo mysqli_error($conn, $query);
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
  var iform = document.getElementById('iform');
  iform.onsubmit = function(){


  }
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
