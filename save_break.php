<?php
require_once("dbconnect_mysqli.php");
require_once("functions.php");
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$agc_id = $_POST["agc_id"];
$break_id = $_POST["break_id"];
$break_code= "ABCD";
$start_time=date("Y-m-d h:i:s");

$select_break = "select break_name from break_management where id='$break_id'";
$rslt_break=mysql_to_mysqli($select_break, $link);
$rslt_row= mysqli_fetch_assoc($rslt_break);
$stmt="INSERT INTO agent_break (agc_id,break_id,break_code,start_time,break_status) values('$agc_id','$break_id','$break_code','$start_time','1')";

		if ($DB) {echo "$stmt\n";}
		$rslt=mysql_to_mysqli($stmt, $link);
		$affected_rows = mysqli_affected_rows($link);
		$webserver_id = mysqli_insert_id($link);
		echo $webserver_id.",".$rslt_row["break_name"];
