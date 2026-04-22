<?php
function importCSV($file,$conn){
 $h=fopen($file,'r');
 while(($d=fgetcsv($h))!==false){
  $conn->query("INSERT INTO students(ms_no,name,class,phone)
  VALUES('$d[0]','$d[1]','$d[2]','$d[3]')");
 }
}
?>