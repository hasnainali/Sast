<?php
require_once("dbconnect_mysqli.php");
require_once("functions.php");
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$ticket_id = $_POST["ticket_id"];
 //get status
if(isset($_POST["phone_number"])){
  $phone_number = $_POST["phone_number"];  
}
else{
 $phone_number ="";   
    
}

if(!empty($phone_number)){
	 $get_cust = "SELECT id FROM customer  where mobile_number =".$phone_number;
	$rslt_cust=mysql_to_mysqli($get_cust, $link);
	$rows_cust = mysqli_fetch_assoc($rslt_cust);
	$cust_id =$rows_cust["id"];

	$closed_ticket = "SELECT GROUP_CONCAT( ticket_id ) as ticket_id FROM customer_tickets_details WHERE customer_id ='$cust_id' AND ticket_status =2";
	$ticket_closed = mysql_to_mysqli($closed_ticket, $link);
	$closed = mysqli_fetch_row($ticket_closed);
	$closed_tickets = 0;
	if($closed[0]!=''){	
		$closed_tickets = $closed[0];
	}
	$get_ticket_last_action = "SELECT ticket_id,agent ,ticket_data,ticket_tree,ticket_status,creat_at FROM customer_tickets_details WHERE customer_id ='$cust_id' AND `ticket_id`  NOT IN ($closed_tickets) ORDER BY id desc limit 1";  
}
else{
  
 $get_ticket_last_action = "SELECT  ticket_id,agent ,ticket_data,ticket_tree,ticket_status,customer_tickets_details.creat_at,mobile_number from customer_tickets_details left join customer on customer_tickets_details.customer_id=customer.id where ticket_id='$ticket_id' ORDER BY customer_tickets_details.id desc limit 1";       
} 

 $ticket_action=mysql_to_mysqli($get_ticket_last_action, $link); 
 $rows_ticket_status = mysqli_fetch_assoc($ticket_action);  
 $rows_ticket_status['total_actions'] =0;
 if(mysqli_num_rows($ticket_action)>0){ 	
	$ticket_id = $rows_ticket_status['ticket_id'];	
	$actions = mysql_to_mysqli("SELECT count(id) from customer_tickets_details where ticket_id='$ticket_id'",$link);
	$total_actions = mysqli_fetch_row($actions);
	$rows_ticket_status['total_actions'] =$total_actions[0];
}
$json_data = preg_replace( "/\r|\n/", " ", $rows_ticket_status['ticket_data']);  
 $json_decode = json_decode($json_data,true);

 $clouse_id = $json_decode['clousertype'];
 $stmt = "SELECT name FROM ticket_clouser WHERE id='$clouse_id'";
 $rslt = mysql_to_mysqli($stmt,$link);
 $row = mysqli_fetch_row($rslt);

// $time = $json_decode['ticketCall_time'];
// $time2 = (!empty($json_decode['acw_time']) ? $json_decode['acw_time'] : "00:00:00" );
$time = $rows_ticket_status['creat_at'];
$time2 = $json_decode['ticketAt'];
$secs = strtotime($time2)-strtotime("00:00:00");
$result = date("H:i:s",strtotime($time)-$secs);
 
 $rows_ticket_status['comments']=$json_decode['comments'];
 $rows_ticket_status['tickettype']=$json_decode['tickettype'];
 $rows_ticket_status['parent']=$json_decode['parent'];
 $rows_ticket_status['child']=$json_decode['child'];
 $rows_ticket_status['ticketAt']=$json_decode['ticketAt'];
 $rows_ticket_status['ticket_tree'] = $rows_ticket_status['ticket_tree'];
 $rows_ticket_status['clousertype'] = $row[0];
 $rows_ticket_status['creat_at'] = $rows_ticket_status['creat_at'];
 $rows_ticket_status['total_duration'] = $result;


 //print_r($rows_ticket_status);die;
  $rowdata[] = $rows_ticket_status; 
 
 $array = array("status"=>1,"data"=>$rowdata,"ticketdata"=>$rows_ticket_status['ticket_data']);
 echo json_encode($array);
    