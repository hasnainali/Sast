<?php
require_once("dbconnect_mysqli.php");
require_once("functions.php");
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$break_id = trim($_POST["break_id"]);
//$break_id = $_POST["break_id"];

$end_time=date("Y-m-d h:i:s");
$stmt="update agent_break set break_status=2,end_time='$end_time'  where id ='$break_id'";

		if ($DB) {echo "$stmt\n";}
		$rslt=mysql_to_mysqli($stmt, $link);
		//$affected_rows = mysqli_affected_rows($link);
		//$webserver_id = mysqli_insert_id($link);
		//echo $webserver_id;
