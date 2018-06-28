<?php

if(isset($_POST['month']))  $month = $_POST['month'];
else $month = 6;
if(isset($_POST['year'])) $year = $_POST['year'];
else $year = 2018;

if($month>12){
  $month = 1;
  $year+=1;

}
if($month<1){
  $month = 12;
  $year-=1;
}


function draw_calendar($month, $year)
{
  $monthname = date('F', mktime(0,0,0,$month,1,$year));
  $yearname = date('Y',  mktime(0,0,0,$month,1,$year));
  $calendar = '<table cellspacing = "0" border = "1">';
  $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday','Saturday');
  $calendar = $calendar."<tr class = 'row-heading'><th class = 'day-heading'>".implode("</th><th class = 'day-heading'>", $days).'</th></tr>';
  $weekday = date('w', mktime(0,0,0,$month,1,$year));
  $daysinmonth = date('t', mktime(0,0,0,$month, 1, $year));
  $calendar.='<tr>';
  for($i = 0;$i<$weekday;$i++) $calendar.='<td class = "data"></td>';
  for($i=1, $j=$weekday;$i<=$daysinmonth; $i++, $j++)
  {

      $calendar.='<td class = "number" onclick = "showPopup('.$i.','.$daysinmonth.')"><p>'.$i.'</p>'.new_appointment($i, $month, $year).display_appointments($i, $month, $year).'</td>';
      echo '</td>';
      if($j==6){
        $calendar.='</tr><tr class = "row">';
       $j=-1;
     }

  }

  if($j!=0){
  for(;$j<=6;$j++) $calendar.='<td class = "data"></td>';


}
  $calendar.='</tr></table>';
  echo '<p>'.$monthname.','.$yearname.'</p>';
  echo $calendar;
  $prevmonth = $month-1;
  $nextmonth = $month+1;

  echo '</br>';
  echo '<form method = "post" action = "'.$_SERVER['PHP_SELF'].'">';
  echo '<input type = "hidden" name = "month" value = "'.$prevmonth.'">';
  echo '<input type = "hidden" name = "year" value = "'.$year.'">';
  echo '<input type = "submit" name = "submit" value = "Previous Month">';
  echo '</form>';



  echo '<form method = "post" action = "'.$_SERVER['PHP_SELF'].'">';
  echo '<input type = "hidden" name = "month" value = "'.$nextmonth.'">';
  echo '<input type = "hidden" name = "year" value = "'.$year.'">';
  echo '<input type = "submit" name = "submit" value = "Next month">';
  echo '</form>';



}



// draw_calendar($month, $year);
// $nextmonth = $month+1;
// $prevmonth = $month-1;

function new_appointment($date, $month, $year){

 $monthname = date('n', mktime(0,0,0,$month,1,$year));   //number from 1 to 12
   $yearname = date('Y',  mktime(0,0,0,$month,1,$year));   //4-digit year number
 $str = '<form method = "post" action = "welcome.php">';
 $str .='<input type = "submit" name = "new" value = "+">';
 $str.='<input type = "hidden" name = "month" value = "'.$monthname.'">';
 $str.='<input type = "hidden" name = "year" value = "'.$yearname.'">';
 $str.='<input type = "hidden" name = "date" value = "'.$date.'">';
 $str.='<input type = "hidden" name = "newappointment" value = "processappset">';
 $str.='</form>';
 return $str;
}

function display_appointments($date, $month, $year){
  $str = '';
  $conn = mysqli_connect("localhost","username", "password");
  $query = "SELECT TITLE, STARTTIME, ENDTIME FROM deltadb.appointtable WHERE DAT = '".$date."' AND MONTH = '".$month."' AND YEAR = '".$year."' AND NAME = '".$_SESSION['name']."'";
  if(!mysqli_query($conn, $query)) $str.=mysqli_error($conn);
 if($stmt = mysqli_prepare($conn, $query)){
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $title, $stime, $etime);
  while(mysqli_stmt_fetch($stmt)){

    $str.='<p>'.$title.' from '.$stime.' to '.$etime;
  }
}
return $str;
}

// function display_pending(){
//   $str = '<div class = "pending">';
// $conn = mysqli_connect("localhost","username", "password");
// $query = "SELECT DAT, MONTH, YEAR, STIME, ETIME, INVITED_BY FROM deltadb.meetingtable WHERE INVITE_TO = '".$_SESSION['name']."' AND STATUS = 'Not confirmed'";
// if($stmt = mysqli_prepare($conn, $query)){
//   mysqli_stmt_execute($stmt);
//   mysqli_stmt_bind_result($stmt, $date, $month, $year, $stime, $etime, $by);
//   while(mysqli_stmt_fetch($stmt)){
//     $str.='<div>';
//     $str.='<p>With '.$by.' on '.$date.'/'.$month.'/'.$year.' from '.$stime.' to '.$etime.'</p>';
//     $str.= '<form action = "welcome.php" method = "POST">';
//     $str.='<input type = "submit" name = "submitinvite" value = "Accept"/>';
//     $str.= '<input type = "submit" name = "submitinvite" value = "Decline"/>';
//     $str.='<input type = "hidden" name = "bywhom" value = "'.$by.'">';
//     $str.='<input type = "hidden" name = "invdate" value = "'.$date.'">';
//     $str.='<input type = "hidden" name = "invmonth" value = "'.$month.'">';
//     $str.='<input type = "hidden" name = "invyear" value = "'.$year.'">';
//     $str.='<input type = "hidden" name = "invstime" value = "'.$stime.'">';
//     $str.='<input type = "hidden" name = "invetime" value = "'.$etime.'">';
//     $str.='<input type = "hidden" name = "pending" value = "pendingset">';
//
//     $str.='</form></div>';
//   }
//
//
//
//
// }
// $str.='</div>';
// return $str;
// }

function display_popup($date,$month, $year){
$daysinmonth = date('t', mktime(0,0,0,$month, 1, $year));

  $str = '<div class = "popup" style = "display:none" id = "'.$date.'"><table><th>Title</th><th>Description</th><th>Start time </th><th>End time </th>';
  $conn = mysqli_connect("localhost","username", "password");
  $query ="SELECT TITLE, DESCRIPTION, STARTTIME, ENDTIME FROM deltadb.appointtable WHERE DAT = '".$date."' AND MONTH = '".$month."' AND YEAR = '".$year."' AND NAME = '".$_SESSION['name']."'";
  if($stmt = mysqli_prepare($conn, $query)){
   mysqli_stmt_execute($stmt);
   mysqli_stmt_bind_result($stmt, $title, $descri, $stime, $etime);
  while(mysqli_stmt_fetch($stmt)){

     $str.='<tr><td>'.$title.'</td><td>'.$descri.'</td><td>'.$stime.'</td><td>'.$etime.'</td></tr>';
   }

 }

$str.='</table>';
$str.='<input type = "button" onclick = "hidePopup('.$date.','.$daysinmonth.')" name = "Close" value = "Close">';
$str.='</div>';
return $str;



}



 ?>
