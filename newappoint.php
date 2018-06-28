<?php
include 'sqlconnect.php';
include 'calendar.php';


 ?>

 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>Create appointment</title>
   </head>
   <body>
<?php
if(isset($_POST['newapp']))
{
  process_appointment();
  unset($_POST['newapp']);
}

 ?>
     <form class="" action=<?php echo '"'.$_SERVER['PHP_SELF'].'"' ?> method="post">
       <input type="text" name="title" value="" placeholder="Title"/>
       <input type="textarea" name="description" value="" placeholder="Descri"/>
       <input type="time" name="start" value="" placeholder="start time">
       <input type="time" name="end" value="" placeholder="end time">
       <input type="submit" name="submit" value="Create Appointment">
       <input type="hidden" name="newapp" value="newappset">
       <?php
        if(isset($_POST['processapp'])){
        echo '<input type = "hidden" name = "date" value = "'.$_POST['datenew'].'">';
        echo '<input type = "hidden" name = "month" value = "'.$_POST['monthnew'].'">';
        echo '<input type = "hidden" name = "year" value = "'.$_POST['yearnew'].'">';

}

        ?>


     </form>
<?php

function process_appointment(){
  $conn = mysqli_connect("localhost","username", "password", "deltadb");
$title = $_POST['title'];
$descri = $_POST['description'];
$stime = $_POST['start'];
$etime = $_POST['end'];
$date = $_POST['date'];
$month = $_POST['month'];
$year = $_POST['year'];
if($stime=='') echo 'Start-time can\'t be empty';
if($etime=='') echo 'ENd time can\'t be empty';
else{$query = "INSERT INTO deltadb.appointtable VALUES ('".$date."', '".$month."', '".$year."', '".$title."', '".$descri."', '".$stime."', '".$etime."')";
mysqli_query($conn, $query);

}

}

 ?>
   </body>
 </html>
