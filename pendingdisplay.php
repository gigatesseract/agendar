<?php
include 'sqlconnect.php';

function display_pending(){
  $str = '';
$conn = mysqli_connect("localhost","username", "password");
$query = "SELECT DAT, MONTH, YEAR, STIME, ETIME, INVITED_BY FROM deltadb.meetingtable WHERE INVITE_TO = '".$_SESSION['name']."' AND STATUS = 'Not confirmed'";
if($stmt = mysqli_prepare($conn, $query)){
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $date, $month, $year, $stime, $etime, $by);
  while(mysqli_stmt_fetch($stmt)){
    $str.='<div>';
    $str.='<p>With '.$by.' on '.$date.'/'.$month.'/'.$year.' from '.$stime.' to '.$etime.'</p>';
    $str.= '<form action = "welcome.php" method = "POST">';
    $str.='<input type = "submit" name = "submitinvite" value = "Accept"/>';
    $str.= '<input type = "submit" name = "submitinvite" value = "Decline"/>';
    $str.='<input type = "hidden" name = "bywhom" value = "'.$by.'">';
    $str.='<input type = "hidden" name = "invdate" value = "'.$date.'">';
    $str.='<input type = "hidden" name = "invmonth" value = "'.$month.'">';
    $str.='<input type = "hidden" name = "invyear" value = "'.$year.'">';
    $str.='<input type = "hidden" name = "invstime" value = "'.$stime.'">';
    $str.='<input type = "hidden" name = "invetime" value = "'.$etime.'">';
    $str.='<input type = "hidden" name = "pending" value = "pendingset">';

    $str.='</form></div>';
  }


  }

  return $str;
  }

  echo display_pending();

 ?>
