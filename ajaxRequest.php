<?php 
$DB=0;
require_once("functions.php");
require_once("dbconnect_mysqli.php");

if(isset($_POST['action']) && $_POST['action']=='getCustomerDetails'){
	$mobile = $_POST['mobilenumber'];

	// $stmt="SELECT cd . * FROM `customer` LEFT JOIN customer_details AS cd ON cd.customer_id = customer.id WHERE mobile_number = '$mobile'";
	$stmt = "SELECT * FROM dms_retailers_data WHERE primary_mob_number = '$mobile' OR secondary_mob_number = '$mobile' ORDER BY id DESC LIMIT 1";
	$rslt = mysql_to_mysqli($stmt,$link);
	$is_exist = mysqli_num_rows($rslt);
	$customer_data = array();

	// Select skills from customer number 
	// $msidn2 	 = substr($mobile, 0,2);
	$msidn3 	 = substr($mobile, 0,5);
	$msidn4 	 = substr($mobile, 0,4);
	$msidn_skill = '-';
	$query 		 = "SELECT sge.skill_group_id, GROUP_CONCAT(DISTINCT skill.name) AS skills, extension_id, extensions FROM `skill_group_extension` as sge JOIN skill_group as sg on sge.skill_group_id=sg.skill_group_id INNER JOIN skill ON FIND_IN_SET(skill.id, sg.skill_ids)>0 where FIND_IN_SET ($msidn4,extensions) or FIND_IN_SET ($msidn3,extensions)"; // or FIND_IN_SET ($msidn2,extensions)";
	$skill_rslt = mysql_to_mysqli($query, $link);	
	$is_find 	= mysqli_num_rows($skill_rslt);
	if($is_find>0){
    	$row 		 = mysqli_fetch_row($skill_rslt);
    	if($row[1]!=null){
    		$msidn_skill = $row[1];    		
    	}

	}

	$vicidial_closer_log_stmt = "SELECT closecallid FROM vicidial_closer_log WHERE phone_number = '$mobile' ORDER BY closecallid DESC LIMIT 1";
	$vicidial_closer_log_rslt = mysql_to_mysqli($vicidial_closer_log_stmt,$link);
	$vicidial_closer_log_row = mysqli_fetch_row($vicidial_closer_log_rslt);

	if($is_exist>0){
		$customer_data = mysqli_fetch_row($rslt);
		echo json_encode(array('success'=>1,'data'=>$customer_data,'skill'=>$msidn_skill,'query'=>$query,'vicidial_closer_log_id'=>$vicidial_closer_log_row));
	}else{
		echo json_encode(array('success'=>0,'data'=>$customer_data,'skill'=>$msidn_skill,'query'=>$query));
	}
	
    // $rslt = mysql_to_mysqli($stmt, $link);
   	// while ($cust_data = mysqli_fetch_row($rslt)) {   		
   	// 	$customer_data[] = array('key'=>$cust_data[2],'value'=>$cust_data[3]);
   	// }
	
}



if(isset($_POST['action']) && $_POST['action']=='saveAgentRequest' && isset($_POST['phone_number']) && $_POST['phone_number']!=''){
	date_default_timezone_set('Asia/Kolkata');
	$customer_no   = $_POST['phone_number'];
	$alt_no 		= $_POST['alt_no'];	
	$name  			= $_POST['name'];
	$city 			= $_POST['city'];
	$state			= $_POST['state'];
	$caller_type    = $_POST['caller_type'];
	$assign_by    	= $_POST['assign_by'];
	$assign_agent   = $_POST['assign_agent'];
	$tickettype    	= $_POST['tickettype'];
	$parent    		= isset($_POST['parent'])?$_POST['parent']:0;
	$child    		= isset($_POST['child'])?$_POST['child']:0; 
	$clousertype    = $_POST['clousertype'];
	$ticketstatus   = $_POST['ticketstatus'];
	$comments   	= $_POST['comments'];
	$SecondS        = isset($_POST['SecondS'])?$_POST['SecondS']:0;
	$ticket_id 		= 0;
	$is_action		= 0;
	$ticket_tree	= isset($_POST['ticket_tree'])?$_POST['ticket_tree']:'';
	$vicidial_log_id= $_POST['vicidial_log_id'];
	$skill_set		= $_POST['skill_set'];
	$holdTime		= $_POST['holdTime'];
	$callStatus		= $_POST['callStatus'];
	$creat_at 		= $_POST['creat_at'];
	$action_no   	= 1;
	
	if(isset($_POST['ticket_id']) && $_POST['ticket_id']!=''){
		$ticket_id = $_POST['ticket_id'];
		$is_action		= 1;
		$update_callback_stmt = "UPDATE vicidial_callbacks SET status='INACTIVE' WHERE comments='$ticket_id'";
		$update_callback_rslt = mysql_to_mysqli($update_callback_stmt,$link);
		$ticket_count = "SELECT * FROM customer_tickets_details WHERE ticket_id='$ticket_id'";
		$result =  mysql_to_mysqli($ticket_count,$link);
		$last_action = mysqli_num_rows($result);
		$action_no = $last_action+1;
	}

	$check_customer = "SELECT * FROM customer WHERE mobile_number='$customer_no'";
	$rslt 			= mysql_to_mysqli($check_customer, $link);
	$is_exist       = mysqli_num_rows($rslt);
	$customer_id    = 0; 		
	if($is_exist>0){
		$customer_data = mysqli_fetch_row($rslt);
		$customer_id   = $customer_data[0];		
		$customer_details = "SELECT * FROM customer_details WHERE customer_id = '$customer_id'";
		$rslt1 			= mysql_to_mysqli($customer_details, $link);
		$is_exist_keys  = mysqli_num_rows($rslt1);
		if($is_exist_keys>0){

			$update_query = "UPDATE `customer_details` SET `customer_no`='$customer_no',`alt_no`='$alt_no',`name`='$name',`city`='$city',`state`='$state',`caller_type`='$caller_type' WHERE customer_id='$customer_id'";
			$rslt12		= mysql_to_mysqli($update_query, $link);

			// $update_key = "UPDATE customer_details SET value='$value' WHERE customer_id='$customer_id' and form_key= '$key'";
			// while ($records = mysqli_fetch_row($rslt1 )) {
			// 	$key 	= $records[2];
			// 	$value  = $$key;				
			// 	$update_key = "UPDATE customer_details SET customer_no='$customer_no',alt_no='$alt_no','name' WHERE customer_id='$customer_id' and form_key= '$key'";
			// 	$rslt12		= mysql_to_mysqli($update_key, $link);				
			// }
		}else{

			$save_customer = "INSERT INTO `customer_details`( `customer_id`, `customer_no`, `alt_no`, `name`, `city`, `state`, `caller_type`)  values ('$customer_id','$customer_no','$alt_no','$name','$city','$state','$caller_type')";
			$rslt 		   = mysql_to_mysqli($save_customer, $link);
		}
	}else{
		$save_contact = "INSERT INTO customer (mobile_number) values('$customer_no')";		
		$rslt 		   = mysql_to_mysqli($save_contact, $link);
		$customer_id   = mysqli_insert_id($link);

		$save_customer = "INSERT INTO `customer_details`( `customer_id`, `customer_no`, `alt_no`, `name`, `city`, `state`, `caller_type`)  values ('$customer_id','$customer_no','$alt_no','$name','$city','$state','$caller_type')";
		$rslt 		   = mysql_to_mysqli($save_customer, $link);
	}
	


	if($ticket_id==0){		
		$create_ticket 	= "INSERT INTO customer_ticket (`customer_id`) values($customer_id)";		
		$rslt 		  	= mysql_to_mysqli($create_ticket, $link);
		$ticket_id   	= mysqli_insert_id($link);	
	}	

	$flag=0;
	$ticket_form_keys = array();
	$ticket_form_values = array();
	
	foreach ($_POST as $key => $value) {

		if($value=='ticket_form'){
			$flag=1;
			$ticket_form_keys[] = 'SecondS';	
			$ticket_form_values[] = $SecondS;	
		}
		if($value=='customer_form'){
			$flag=0;			
		}
		if($flag==1 && $value!='ticket_form'){
			$ticket_form_keys[] = $key;	
			$ticket_form_values[] = trim($value);	
		}
	}
	$ticket_data = array_combine($ticket_form_keys,$ticket_form_values);	
	// print_r($ticket_data);die();
	// $ticket_data = array('tickettype'=>$tickettype,'parent'=>$parent,'child'=>$child,'clousertype'=>$clousertype,'ticketstatus'=>$ticketstatus,'SecondS'=>$SecondS,'comments'=>$comments);
	ksort($ticket_data);	
	$ticket_data = json_encode($ticket_data);
	if($callStatus=='INBND'){
		$source = 'INCALL';
	}
	else{
		$source = 'OUTCALL';
	}
	$save_ticket     = "INSERT INTO customer_tickets_details(customer_id,ticket_id,agent,ticket_status,source,ticket_data,	ticket_tree,remark,creat_at,action_no,vicidial_log_id,skill_set,holdTime) values ($customer_id,$ticket_id,$assign_agent,$ticketstatus,'$source','$ticket_data','$ticket_tree','$comments','$creat_at','$action_no','$vicidial_log_id','$skill_set','$holdTime')";


	$ticket_rslt 	= mysql_to_mysqli($save_ticket, $link);
	if($ticket_rslt){
		if($is_action==1){			
			echo json_encode(array('success'=>1,'message'=>'Ticket: '.$ticket_id.' action has been successfully saved','ticket_id'=>$ticket_id,'query'=>$result));
		}else{
			echo json_encode(array('success'=>1,'message'=>'Ticket: '.$ticket_id.' has been successfully saved','ticket_id'=>$ticket_id));
		}
	}else{
		echo json_encode(array('success'=>0,'message'=>'Sorry! Something went wrong. Please try again','query'=>$save_ticket));
	}
}

if(isset($_POST['action']) && $_POST['action']=='getAgentTicketDetails'){
	$agent_id = $_POST['agent_id'];
	$fromDate = $_POST['fromDate'];
	$toDate = $_POST['toDate'];
	$open_num_rows = 0;
	$close_num_rows = 0;
	$ecsalated_num_rows = 0;
	$others_num_rows = 0;

	$stmt = "SELECT ticket_id FROM `customer_tickets_details` WHERE agent = '$agent_id' AND creat_at >= '$fromDate' AND creat_at <= '$toDate' GROUP BY ticket_id";
	$rslt = mysql_to_mysqli($stmt, $link);
	while ($row = mysqli_fetch_assoc($rslt)) {
		$ticket_id = $row['ticket_id'];
		$stmt1 = "SELECT ticket_status FROM `customer_tickets_details` WHERE agent = '$agent_id' AND creat_at >= '$fromDate' AND creat_at <= '$toDate' AND ticket_id='$ticket_id' ORDER BY id DESC LIMIT 1";
		$rslt1 = mysql_to_mysqli($stmt1, $link);
		$row1 = mysqli_fetch_row($rslt1);
			if($row1[0] == "1"){
				$open_num_rows++;
			}
			if($row1[0] == "2"){
				$close_num_rows++;
			}
			if($row1[0] == "3"){
				$ecsalated_num_rows++;
			}
			if($row1[0] == "4"){
				$others_num_rows++;
			}
	}

	// $open="SELECT * FROM `customer_tickets_details` WHERE agent = '$agent_id' AND ticket_status = '1' AND creat_at >= '$fromDate' AND creat_at <= '$toDate' group by ticket_id";
	// $rsltopen = mysql_to_mysqli($open, $link);
	// $open_num_rows = mysqli_num_rows($rsltopen);

	// $close="SELECT * FROM `customer_tickets_details` WHERE agent = '$agent_id' AND ticket_status = '2' AND creat_at >= '$fromDate' AND creat_at <= '$toDate' group by ticket_id";
	// $rsltclose = mysql_to_mysqli($close, $link);
	// $close_num_rows = mysqli_num_rows($rsltclose);

	// $ecsalated="SELECT * FROM `customer_tickets_details` WHERE agent = '$agent_id' AND ticket_status = '3' AND creat_at >= '$fromDate' AND creat_at <= '$toDate' group by ticket_id";
	// $rsltecsalated = mysql_to_mysqli($ecsalated, $link);
	// $ecsalated_num_rows = mysqli_num_rows($rsltecsalated);

	// $others="SELECT * FROM `customer_tickets_details` WHERE agent = '$agent_id' AND ticket_status = '4' AND creat_at >= '$fromDate' AND creat_at <= '$toDate' group by ticket_id";
	// $rsltothers = mysql_to_mysqli($others, $link);
	// $others_num_rows = mysqli_num_rows($rsltothers);

	echo json_encode(array('success'=>1,'open'=>$open_num_rows, 'closed'=>$close_num_rows, 'escalated'=>$ecsalated_num_rows, 'others'=>$others_num_rows));
}

// if(isset($_POST['action']) && $_POST['action']=='getAgentLoginHour'){
	
// 	$agent_id = $_POST['agent_id'];
// 	$today_date = date('Y-m-d');
// 	$next_date = date('Y-m-d', strtotime(' +1 day'));

// 	// Total login hour count
// 	$stmt = "SELECT event_date,event FROM vicidial_user_log WHERE event_date > '$today_date 00:00:00' AND event_date < '$next_date 00:00:00' AND user = '$agent_id' ORDER BY `user_log_id` ASC";
// 	$rslt = mysql_to_mysqli($stmt, $link);
// 	$totalltime = '00:00:00';
// 	$flag = 0;
// 	$is_last_logout =0;
// 	while ($row=mysqli_fetch_assoc($rslt)){
// 		if($row['event']=='LOGIN' && $flag == '0'){
// 			$flag = 1;
// 			$is_last_logout =1;
// 			$loginTime = $row['event_date'];
// 		}
// 		elseif($row['event']=='LOGOUT' && $flag== '1'){
// 			$flag = 0;
// 			$is_last_logout =0;
// 			$logoutTime = $row['event_date'];
// 			$time = strtotime($logoutTime)-strtotime($loginTime);
// 			$totalltime = $totalltime+$time;
// 		}		
		
// 	}
// 	if($flag=='1' && $is_last_logout==1){		
// 		$currentTime = date("Y-m-d H:i:s");
// 		$logoutTime = $currentTime;
// 		$time = strtotime($logoutTime)-strtotime($loginTime);
// 		$totalltime = $totalltime+$time;
// 	}
// 	$totalLoginHour = gmdate("H:i", $totalltime);
// 	// Total Break hour count
// 	$breakstmt = "SELECT start_time,end_time FROM agent_break WHERE start_time > '$today_date 00:00:00' AND start_time < '$next_date 00:00:00' AND agc_id = '$agent_id' ORDER BY `id` ASC";
// 	$breakrslt = mysql_to_mysqli($breakstmt, $link);
// 	$TotalBreakTime = '00:00:00';
// 	while ($breakrow=mysqli_fetch_assoc($breakrslt)){
// 		if($breakrow['start_time']!=null && $breakrow['end_time']!=null && $breakrow['start_time']!='' && $breakrow['end_time']!=''){
			
// 		$breakTime = strtotime($breakrow['end_time'])-strtotime($breakrow['start_time']);
// 		$TotalBreakTime = $TotalBreakTime+$breakTime;
// 		}
// 	}
// 	$showTotalBreakTime = gmdate("H:i", $TotalBreakTime);
// 	// Final calculation(Loigin minus break)
// 	$totalCalculatedTime = $totalltime-$TotalBreakTime;
// 	$totalCalculatedTime = gmdate("H:i", $totalCalculatedTime);
// 	echo json_encode(array('success'=>1,'time'=>$totalCalculatedTime,'logintime'=>$totalLoginHour,'breaktime'=>$showTotalBreakTime,'flag'=>$flag,'is_last_logout'=>$is_last_logout));
// }

if(isset($_POST['action']) && $_POST['action']=='getAgentLoginHour'){
	
	$agent_id = $_POST['agent_id'];
	$today_date = date('Y-m-d');
	$next_date = date('Y-m-d', strtotime(' +1 day'));

	$totalLoginHour = '00:00:00';
	$TotalBreakTime = '00:00:00';

	// Total login hour count
	// $stmt = "SELECT event_date,event FROM vicidial_user_log WHERE event_date > '$today_date 00:00:00' AND event_date < '$next_date 00:00:00' AND user = '$agent_id' ORDER BY `user_log_id` ASC";
	// $rslt = mysql_to_mysqli($stmt, $link);
	// $totalltime = '00:00:00';
	// $flag = 0;
	// $is_last_logout =0;
	// while ($row=mysqli_fetch_assoc($rslt)){
	// 	if($row['event']=='LOGIN' && $flag == '0'){
	// 		$flag = 1;
	// 		$is_last_logout =1;
	// 		$loginTime = $row['event_date'];
	// 	}
	// 	elseif($row['event']=='LOGOUT' && $flag== '1'){
	// 		$flag = 0;
	// 		$is_last_logout =0;
	// 		$logoutTime = $row['event_date'];
	// 		$time = strtotime($logoutTime)-strtotime($loginTime);
	// 		$totalltime = $totalltime+$time;
	// 	}		
		
	// }
	// if($flag=='1' && $is_last_logout==1){		
	// 	$currentTime = date("Y-m-d H:i:s");
	// 	$logoutTime = $currentTime;
	// 	$time = strtotime($logoutTime)-strtotime($loginTime);
	// 	$totalltime = $totalltime+$time;
	// }
	// $totalLoginHour = gmdate("H:i:s", $totalltime);


	$stmt = "SELECT status_name,status_interval FROM agent_status_log WHERE agent_id='$agent_id' AND dated>= '$today_date 00:00:00' AND dated<= '$today_date 23:59:59'";
	$rslt = mysql_to_mysqli($stmt,$link);
	while ($row = mysqli_fetch_assoc($rslt)) {
		if($row['status_name']!=''){
			$loginHour = strtotime($totalLoginHour)-strtotime('00:00:00');
			$loginHour1 = $loginHour+strtotime($row['status_interval']);
			$totalLoginHour = date("H:i:s",$loginHour1);
			if($row['status_name']=='Tea Break' || $row['status_name']=='Lunch Break' || $row['status_name']=='Meeting' || $row['status_name']=='Bio Break'){
				$break_time = strtotime($TotalBreakTime)-strtotime('00:00:00');
				$break_time = $break_time+strtotime($row['status_interval']);
				$TotalBreakTime = date("H:i:s",$break_time);
			}
		}
	}




	// Total Break hour count
	// $breakstmt = "SELECT start_time,end_time FROM agent_break WHERE start_time > '$today_date 00:00:00' AND start_time < '$next_date 00:00:00' AND agc_id = '$agent_id' ORDER BY `id` ASC";
	// $breakrslt = mysql_to_mysqli($breakstmt, $link);
	// $TotalBreakTime = '00:00:00';
	// while ($breakrow=mysqli_fetch_assoc($breakrslt)){
	// 	if($breakrow['start_time']!=null && $breakrow['end_time']!=null && $breakrow['start_time']!='' && $breakrow['end_time']!=''){
			
	// 	$breakTime = strtotime($breakrow['end_time'])-strtotime($breakrow['start_time']);
	// 	$TotalBreakTime = strtotime($TotalBreakTime)+$breakTime;
	// 	$TotalBreakTime = gmdate("H:i:s", $TotalBreakTime);
	// 	}
	// }
	// $showTotalBreakTime = gmdate("H:i:s", $TotalBreakTime);
	// Final calculation(Loigin minus break)
	$totalCalculatedTime = strtotime($totalLoginHour)-strtotime('00:00:00');
	$netLoginHour = strtotime($totalLoginHour)-strtotime($TotalBreakTime);
	$netLoginHour1 = gmdate("H:i:s", $netLoginHour);
	echo json_encode(array('success'=>1,'time'=>$netLoginHour1,'logintime'=>$totalLoginHour,'breaktime'=>$TotalBreakTime));
}


if(isset($_POST['action']) && $_POST['action']=='getDynamicFields'){
	$ticketFields = $_POST['form_fields'];
	$ticketFields = implode("','", $ticketFields);
	$fieldstmt 	  = "SELECT * from form_fields where name IN ('$ticketFields') and status = '1';";
	$fieldrslt=mysql_to_mysqli($fieldstmt, $link);
	while ($fieldrows = mysqli_fetch_assoc($fieldrslt)){
		$fieldsData[] = $fieldrows;
	}
	if(!empty($clouserData) || !empty($fieldsData)){
		echo json_encode(array('success'=>1,'ticketFields'=>$fieldsData));
		
	}else{
		echo json_encode(array('success'=>0,'ticketFields'=>array(),'fields'=>$fieldstmt));
	}

}

if(isset($_POST['action']) && $_POST['action']=='saveAgentStatusTime'){
	$agent_id = $_POST['agent_id'];
	$status_name = $_POST['status_name'];
	$campaign_id = $_POST['campaign_id'];

	$get_last_record = "SELECT dated FROM agent_status_log WHERE agent_id='$agent_id' ORDER BY id DESC LIMIT 1";
	$rslt_last_record = mysql_to_mysqli($get_last_record,$link);
	$row_last_record = mysqli_fetch_row($rslt_last_record);
	$last_time = strtotime($row_last_record[0]);
	$current_time = date("Y-m-d H:i:s");
	$current_time_str = strtotime($current_time);
	$diff_string = $current_time_str-$last_time;
	$output = sprintf('%02d:%02d:%02d', ($diff_string/ 3600),($diff_string/ 60 % 60), $diff_string% 60);

	$status_interval = $_POST['status_interval'];
	$stmt = "INSERT INTO agent_status_log (agent_id,status_name,status_interval2,status_interval,campaign_id) values ('$agent_id','$status_name','$status_interval','$output','$campaign_id')";
	$rslt=mysql_to_mysqli($stmt, $link);
	if(!empty($rslt)){
		echo json_encode(array('success'=>1,'data'=>$rslt));
		
	}else{
		echo json_encode(array('success'=>0,'data'=>array()));
	}
}

if(isset($_POST['action']) && $_POST['action']=='getFollowupTime'){
	$child_id = $_POST['child_id'];
	$ticket_id = $_POST['ticket_id'];
	// $list_id = $_POST['list_id'];
	$phoneNo = $_POST['phoneNo'];
	$CBHOLDcount = $_POST['CBHOLDcount'];
	$NOW_TIME = date("Y-m-d H:i:s");
	$lead_row = 0;

	$lead_stmt = "SELECT lead_id FROM vicidial_list where phone_number='$phoneNo' ORDER BY lead_id DESC LIMIT 1";
	$lead_rslt=mysql_to_mysqli($lead_stmt, $link);
	$lead_row=mysqli_fetch_row($lead_rslt);
	//$lead_row = $row[0];
	
	$stmt = "SELECT tm.followup_id,ft.followup_time FROM ticket_mapping as tm left join followup_time as ft on ft.followup_id=tm.followup_id where tm.id='$child_id'";
	$rslt=mysql_to_mysqli($stmt, $link);
	$row=mysqli_fetch_row($rslt);
	if(!empty($row)){

		/* DJ - Start of multiple ticket handling */

		if($CBHOLDcount > 0) {

			$stmtA = "INSERT INTO vicidial_list (entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,called_since_last_reset,phone_code,phone_number,security_phrase,called_count,gmt_offset_now,comments$entry_list_idSQLa$INSERTstateSQLa$INSERTpostal_codeSQLa$populate_provinceA) values('$NOW_TIME','$NOW_TIME','CBHOLD','VDAD','NHAI3','VDCL','998','Y','1','$phoneNo','$ingroup_insert','0','-5.00','$VLcomments'$entry_list_idSQLb$INSERTstateSQLb$INSERTpostal_codeSQLb$populate_provinceB);";
			$rslt=mysql_to_mysqli($stmtA, $link);
			$affected_rows = mysqli_affected_rows($link);
			$lead_row = mysqli_insert_id($link);

		}
/*
		$fp = fopen ("callback_log.txt", "a");
		fwrite ($fp, "$NOW_TIME | $stmtA | $lead_row\n");
		fclose($fp);
*/
		/* End */

		echo json_encode(array('success'=>1,'data'=>$row,'ticket_id'=>$ticket_id,'lead_id'=>$lead_row));
	}else{
		echo json_encode(array('success'=>0,'data'=>array(),'ticket_id'=>$ticket_id));
	}
}

if(isset($_POST['action']) && $_POST['action']=='getCallStatusByLead'){
	$mobile_no = $_POST['mobile_no'];
	$lead_id = $_POST['lead_id'];

	$stmt = "SELECT auto_call_id FROM vicidial_auto_calls WHERE lead_id = $lead_id AND phone_number = '$mobile_no'";

	// $stmt = "SELECT closecallid FROM vicidial_closer_log WHERE lead_id = $lead_id AND end_epoch IS NOT NULL AND length_in_sec IS NOT NULL AND phone_number = '$mobile_no'";
	$rslt=mysql_to_mysqli($stmt, $link);
	$row = mysqli_num_rows($rslt);
	if(!empty($rslt)){
		echo json_encode(array('success'=>1,'row'=>$row,'stmt'=>$stmt));
	}else{
		echo json_encode(array('success'=>0,'row'=>array(),'stmt'=>$stmt));
	}
}

if(isset($_POST['action']) && $_POST['action']=='updateVicidiaLiveAgents'){
	$user = $_POST['user'];
	$status = $_POST['status'];
	// $lead_id = $_POST['lead_id'];

	$stmt = "UPDATE vicidial_live_agents SET status = '$status' WHERE user = '$user'";
	$rslt= mysql_to_mysqli($stmt, $link);
	echo json_encode(array('success'=>1,'stmt'=>$stmt));
}


if(isset($_POST['action']) && $_POST['action']=='updateCurrentStatus'){
	
	$user = $_POST['user'];
	$current_status = $_POST['status'];
	$last_number = $_POST['last_number'];
	$NOW_TIME = date("Y-m-d H:i:s");

	$user_exist = "SELECT user FROM agent_current_status WHERE user='$user'";
	$rslt_user  = mysql_to_mysqli($user_exist,$link);
	$num_row = mysqli_num_rows($rslt_user);
	if($num_row>0){
		if($current_status!=''){
			$stmt = "UPDATE agent_current_status SET status = '$current_status',last_number = '$last_number',updated_at = '$NOW_TIME' WHERE user = '$user'";
		} else{
			$stmt = "UPDATE agent_current_status SET last_number = '$last_number',updated_at = '$NOW_TIME' WHERE user = '$user'";
		}
		$rslt= mysql_to_mysqli($stmt, $link);	
	}
	else{
		$stmt = "INSERT INTO agent_current_status (user,status) VALUES ('$user','$current_status')";
		$rslt = mysql_to_mysqli($stmt,$link);
	}
	echo json_encode(array('success'=>1,'stmt'=>$stmt));
}

if(isset($_POST['action']) && $_POST['action']=='updateVicidialStatus'){
	$date = date("Y-m-d");
	$newstring = $_POST['phone'];
	$phone = substr($newstring, -10);
	// $stmt = "UPDATE vicidial_list SET status = 'IN_SALE' WHERE phone_number LIKE '%$phone%' AND status!='INCALL' AND entry_date>='$date 00:00:00' AND entry_date<='$date 23:59:59'";
	$stmt = "SELECT retailer_code FROM dms_retailers_data WHERE dated>='$date 00:00:00' AND dated<='$date 23:59:59' AND primary_mob_number='$phone'";
	$rslt = mysql_to_mysqli($stmt,$link);
	$row = mysqli_fetch_row($rslt);
	$retailer_code = $row[0];
	if($retailer_code){
		$stmtVL = "UPDATE vicidial_list SET IN_CALL = 'Y' WHERE phone_number = $phone AND vendor_lead_code='$retailer_code'";
		$rsltVL = mysql_to_mysqli($stmtVL,$link);
	}
}

if(isset($_POST['action']) && $_POST['action']=='save_ringTime'){
	$uniqueid = $_POST['uniqueid'];
	$ring_time = $_POST['ring_time'];
	$stmt = "UPDATE vicidial_log SET ring_time='$ring_time' WHERE uniqueid='$uniqueid'";
	$rslt = mysql_to_mysqli($stmt,$link);
	
	echo json_encode(array('success'=>1,'stmt'=>$stmt));
}

if(isset($_POST['action']) && $_POST['action']=='get_consult_status'){
	$agent = $_POST['agent'];
	$stmt = "SELECT * FROM tekdial_live_consult WHERE agent_id = '$agent' LIMIT 1";
	$rslt = mysql_to_mysqli($stmt,$link);	
	$row = mysqli_fetch_row($rslt);
	echo json_encode(array('success'=>1,'result'=>$row[2]));
}

if(isset($_POST['action']) && $_POST['action']=='consult_update'){
	$uniqueid = $_POST['uniqueid'];
	$clock_time = $_POST['clock_time'];
	$stmt = "UPDATE vicidial_log SET consult_time = '$clock_time' WHERE uniqueid = '$uniqueid' order by call_date desc limit 1";
	$rslt = mysql_to_mysqli($stmt,$link);
	$stmt1 = "UPDATE vicidial_closer_log SET consult_time = '$clock_time' WHERE uniqueid = '$uniqueid' order by call_date desc limit 1";
	$rslt = mysql_to_mysqli($stmt1,$link);
	echo json_encode(array('success'=>1,'result'=>$stmt));
}

if(isset($_POST['action']) && $_POST['action']=='consult_start'){
	$agent_id = $_POST['agent_id'];
	if($agent_id!=null && $agent_id!=''){
		$check_availability = "SELECT * FROM tekdial_live_consult WHERE agent_id = '$agent_id' LIMIT 1";
		$check_availability_rslt = mysql_to_mysqli($check_availability,$link);
		$check_availability_row = mysqli_fetch_row($check_availability_rslt);
		if($check_availability_row){
			$stmt = "UPDATE tekdial_live_consult SET consult_status = 1 where agent_id = '$agent_id'";
			$rslt = mysql_to_mysqli($stmt,$link);
		}else{
			$stmt = "INSERT tekdial_live_consult (agent_id,consult_status) VALUES ('$agent_id',1)";
			$rslt = mysql_to_mysqli($stmt,$link);
		}
		echo json_encode(array('success'=>1,'message'=>'Agent status changed successfully!'));
	}else{
		echo json_encode(array('success'=>0,'message'=>'agent id required!'));
	}
}

if(isset($_POST['action']) && $_POST['action']=='consult_end'){
	$agent_id = $_POST['agent_id'];
	if($agent_id!=null && $agent_id!=''){
		$check_availability = "SELECT * FROM tekdial_live_consult WHERE agent_id = '$agent_id' LIMIT 1";
		$check_availability_rslt = mysql_to_mysqli($check_availability,$link);
		$check_availability_row = mysqli_fetch_row($check_availability_rslt);
		if($check_availability_row){
			$stmt = "UPDATE tekdial_live_consult SET consult_status = 0 where agent_id = '$agent_id'";
			$rslt = mysql_to_mysqli($stmt,$link);
		}else{
			$stmt = "INSERT tekdial_live_consult (agent_id,consult_status) VALUES ('$agent_id',0)";
			$rslt = mysql_to_mysqli($stmt,$link);
		}
		echo json_encode(array('success'=>1,'message'=>'Agent status changed successfully!'));
	}else{
		echo json_encode(array('success'=>0,'message'=>'agent id required!'));
	}
}


if(isset($_POST['action']) && $_POST['action']=='update_ready'){
	$updateStatusLog = '/var/log/tekdial/dms_api_log/'.date('Y-m-d').'-currentStatusUpdate.log';

	$agent_id = $_POST['agent_id'];

	writeErroIntoLogFile('AGENT ID =>'.$agent_id,$updateStatusLog);
	
	if($agent_id!=null && $agent_id!=''){
		$check_availability = "SELECT user FROM vicidial_live_agents WHERE user = '$agent_id' AND status ='PAUSED' AND lead_id ='0' LIMIT 1";

		writeErroIntoLogFile('CHECK AGENT AVAILABLE FOR UPDATE =>'.$check_availability,$updateStatusLog);

		$check_availability_rslt = mysql_to_mysqli($check_availability,$link);
		
		if($check_availability_rslt){
            $check_availability_row = mysqli_fetch_row($check_availability_rslt);
			writeErroIntoLogFile('AGENT AVAILABLE FOR UPDATE =>',$updateStatusLog); 

			$stmt = "UPDATE vicidial_live_agents SET status = 'READY' where user = '$agent_id' AND status ='PAUSED' AND lead_id ='0'";

			writeErroIntoLogFile('-UPDATE QUERY FOR UPDATE AGENT STATUS =>'.$stmt,$updateStatusLog);

			$rslt = mysql_to_mysqli($stmt,$link);
			if($rslt){
				echo json_encode(array('success'=>1,'message'=>'Agent READY updated  successfully!'));

				writeErroIntoLogFile('AGENT UPDATED STATUS SUCCESSFULLY=>',$updateStatusLog);
			}else{
				echo json_encode(array('success'=>0,'message'=>'Failed To updated  successfully!'));

				writeErroIntoLogFile('FAILED TO UPDATE STATUS SUCCESSFULLY=>',$updateStatusLog);
			}
		}else{
			writeErroIntoLogFile('NOT AVAILABLE=>'.$agent_id,$updateStatusLog);
		}
		
	}else{
		echo json_encode(array('success'=>0,'message'=>'update ready agent id required!'));

		writeErroIntoLogFile('AGENT ID IS REQUIRED =>',$updateStatusLog);
	}
}

if(isset($_POST['action']) && $_POST['action']=='check_queue_calls'){
	$log_file_name = '/var/log/tekdial/AgentStatus/'.date('Y-m-d').'-AgentStatus.log';
   
	$agent_id = $_POST['user'];
	writeErroIntoLogFile('AGENT ID =>'.$agent_id,$log_file_name);
	if($agent_id!=null && $agent_id!=''){
		$check_availability = "SELECT lead_id,status FROM vicidial_live_agents WHERE user = '$agent_id' AND status ='QUEUE' LIMIT 1";
		$check_availability_rslt = mysql_to_mysqli($check_availability,$link);
		writeErroIntoLogFile('check_availability query =>'.$check_availability,$log_file_name); 
		if($check_availability_rslt){
			
            $check_availability_row = mysqli_fetch_row($check_availability_rslt);

			if($check_availability_row[0]!='' && $check_availability_row[1]=='QUEUE'){

				$check_drop = "SELECT status,phone_number FROM vicidial_list WHERE lead_id = ".$check_availability_row[0]." AND (status ='DROP' OR status='PU')";
                $check_drop_rslt = mysql_to_mysqli($check_drop,$link);
		        writeErroIntoLogFile('check_drop query =>'.$check_drop,$log_file_name); 

		        if($check_drop_rslt){
		        	$check_drop_row = mysqli_fetch_row($check_drop_rslt);
		        	writeErroIntoLogFile('vicidial_list data =>'.json_encode($check_drop_row),$log_file_name);
		        	if($check_drop_row[0]=="DROP" || $check_drop_row[0]=="PU" || empty($check_drop_row[0])){
		        		 $phone_number = $check_drop_row[1];

		        		 $check_availability1 = "SELECT lead_id,status FROM vicidial_live_agents WHERE user = '$agent_id' AND status ='QUEUE' LIMIT 1";
						 $check_availability_rslt1 = mysql_to_mysqli($check_availability1,$link);
						 writeErroIntoLogFile('check_availability1 query =>'.$check_availability1,$log_file_name); 

						 $new_status = $mo_no = '';
						 if($check_availability_rslt1){
						 	$check_availability_row1 = mysqli_fetch_row($check_availability_rslt1);
                            writeErroIntoLogFile('check_availability_row1 data =>'.json_encode($check_availability_row1),$log_file_name);

                            $check_drop1= "SELECT status,phone_number FROM vicidial_list WHERE lead_id = ".$check_availability_row1[0]." ";
			                $check_drop_rslt1 = mysql_to_mysqli($check_drop1,$link);
					        writeErroIntoLogFile('check_drop1 query =>'.$check_drop1,$log_file_name); 
					        if($check_drop_rslt1){
					        	$check_drop_row1 = mysqli_fetch_row($check_drop_rslt1);
					        	$new_status = $check_drop_row1[0];
					        	$mo_no = $check_drop_row1[1];
                                writeErroIntoLogFile('check_drop_row1 data =>'.json_encode($check_drop_row1),$log_file_name);
					        }
						 }

		        		 if($check_drop_row[0]=="DROP"){

		        		 	writeErroIntoLogFile('agent status =>'.$check_drop_row[0],$log_file_name);

		        		 	$updte_live_agent = "UPDATE vicidial_live_agents SET status = 'PAUSED' where user = '$agent_id'";
		        		 	writeErroIntoLogFile('updte_live_agent query =>'.$updte_live_agent,$log_file_name);
		        		 	$up_result = mysql_to_mysqli($updte_live_agent,$link);
		        		 	if($up_result){
                               writeErroIntoLogFile('update agent PAUSED success =>'.$agent_id,$log_file_name);
		        		 	}else{
                               writeErroIntoLogFile('update agent PAUSED failed =>'.$agent_id,$log_file_name);
		        		 	}
		        		 }

		        		 if($check_drop_row[0]=="PU"){

		        		 	writeErroIntoLogFile('agent status =>'.$check_drop_row[0],$log_file_name);

		        		 	$updte_live_agent = "UPDATE vicidial_live_agents SET status = 'INCALL' where user = '$agent_id'";
		        		 	writeErroIntoLogFile('updte_live_agent query =>'.$updte_live_agent,$log_file_name);
		        		 	$up_result = mysql_to_mysqli($updte_live_agent,$link);
		        		 	if($up_result){
                               writeErroIntoLogFile('update agent INCALL success =>'.$agent_id,$log_file_name);
		        		 	}else{
                               writeErroIntoLogFile('update agent INCALL failed =>'.$agent_id,$log_file_name);
		        		 	}
		        		 }

		        		 if($new_status=="CALLBK"){

		        		 	writeErroIntoLogFile('agent status =>'.$new_status,$log_file_name);

		        		 	$updte_live_agent = "UPDATE vicidial_live_agents SET status = 'INCALL' where user = '$agent_id'";
		        		 	writeErroIntoLogFile('updte_live_agent query =>'.$updte_live_agent,$log_file_name);
		        		 	$up_result = mysql_to_mysqli($updte_live_agent,$link);
		        		 	if($up_result){
                               writeErroIntoLogFile('update agent INCALL CALLBK success =>'.$agent_id,$log_file_name);
		        		 	}else{
                               writeErroIntoLogFile('update agent INCALL CALLBK failed =>'.$agent_id,$log_file_name);
		        		 	}
		        		 }elseif(empty($check_drop_row[0])){

		        		 	writeErroIntoLogFile('agent status empty  =>'.$check_drop_row[0],$log_file_name);

		        		 	$updte_live_agent = "UPDATE vicidial_live_agents SET status = 'INCALL' where user = '$agent_id'";
		        		 	writeErroIntoLogFile('updte_live_agent query =>'.$updte_live_agent,$log_file_name);
		        		 	$up_result = mysql_to_mysqli($updte_live_agent,$link);
		        		 	if($up_result){
                               writeErroIntoLogFile('update agent INCALL success =>'.$agent_id,$log_file_name);
		        		 	}else{
                               writeErroIntoLogFile('update agent INCALL failed =>'.$agent_id,$log_file_name);
		        		 	}
		        		 }

		        		 writeErroIntoLogFile('agent phone_number =>'.$mo_no,$log_file_name);
		        		 writeErroIntoLogFile('agent lead_id  =>'.$check_availability_row[0],$log_file_name);
		        		 writeErroIntoLogFile('open window with number =>'.$agent_id,$log_file_name);
                         echo json_encode(array('success'=>1,'message'=>$mo_no));
		        	}else{
		        		writeErroIntoLogFile('agent status =>'.$check_drop_row[0],$log_file_name);
		        		writeErroIntoLogFile('agent status in vicidial_list changed1 =>'.$agent_id,$log_file_name);
		        		echo json_encode(array('success'=>0,'message'=>'agent status in vicidial_list changed1'));
		        	}
		        }else{
		        	writeErroIntoLogFile('agent status in vicidial_list changed2 =>'.$agent_id,$log_file_name);
		        	echo json_encode(array('success'=>0,'message'=>'agent status in vicidial_list changed2'));
		        }


			}else{
			   writeErroIntoLogFile('agent status changed1 =>'.$agent_id,$log_file_name);	
               echo json_encode(array('success'=>0,'message'=>'agent status changed1'));
			}
			
		}else{
			writeErroIntoLogFile('agent status changed2 =>'.$agent_id,$log_file_name);
			echo json_encode(array('success'=>0,'message'=>'agent status changed2'));
		}
	
	}else{
		writeErroIntoLogFile('agent id required!',$log_file_name);
		echo json_encode(array('success'=>0,'message'=>'agent id required!'));
	}
}

if(isset($_POST['action']) && $_POST['action']=='update_paused'){
	$agent_id = $_POST['agent_id'];
	$log_file_name123 = '/var/log/tekdial/AgentStatus/'.date('Y-m-d').'-AgentPausedUpdate.log';
   
	// $agent_id = $_POST['user'];

	writeErroIntoLogFile('AGENT ID =>'.$agent_id,$log_file_name123);

	if($agent_id!=null && $agent_id!=''){

		$check_availability = "SELECT status FROM vicidial_live_agents WHERE user = '$agent_id' LIMIT 1";

		writeErroIntoLogFile('QUERY TO CHECK STATUS =>'.$check_availability,$log_file_name123);

		$check_availability_rslt = mysql_to_mysqli($check_availability,$link);

		$check_availability_row = mysqli_fetch_row($check_availability_rslt);

		writeErroIntoLogFile('AGENT DATA =>'.json_encode($check_availability_row),$log_file_name123);

		if($check_availability_row[0] == "INCALL"){

			writeErroIntoLogFile('AGENT STATUS =>'.$check_availability_row[0],$log_file_name123);

			$stmt = "UPDATE vicidial_live_agents SET status = 'PAUSED' where user = '$agent_id'";

			writeErroIntoLogFile('AGENT PAUED UPDATE =>'.$stmt,$log_file_name123);

			$rslt = mysql_to_mysqli($stmt,$link);

			writeErroIntoLogFile('AGENT PAUED UPDATE RESULT=>'.$rslt,$log_file_name123);

			echo json_encode(array('success'=>1,'message'=>'Agent status PAUSED successfully!'));

		}else{
            
            writeErroIntoLogFile('AGENT STATUS CHANGED=>'.$check_availability_row[0],$log_file_name123);

			echo json_encode(array('success'=>0,'message'=>'Agent status Changes =>'.$check_availability_row[0]));

		}
		
	}else{
		
		writeErroIntoLogFile('AGENT REQUIRED =>',$log_file_name123);

		echo json_encode(array('success'=>0,'message'=>'agent id required!'));
	}
}


if(isset($_GET['action']) && $_GET['action']=='make_call_check'){
	$agent_id = $_GET['agent_id'];
	$mobile_number = $_GET['mobile_number'];
	$log_file_name123 = '/var/log/tekdial/MakeCall/'.date('Y-m-d').'-MakeCall.log';
	$today_date = date('Y-m-d');
	$date_time = date('Y-m-d H:i:s');
    
	writeErroIntoLogFile('AGENT ID =>'.$agent_id,$log_file_name123);

	if($agent_id!=null && $agent_id!=''){

		$check_already_called = "SELECT id FROM todays_make_call WHERE mobile_number LIKE '%$mobile_number%' AND make_call_date = '".date("Y-m-d")."'";

		writeErroIntoLogFile('Check already called =>'.$check_already_called,$log_file_name123);

		$check_already_rslt = mysql_to_mysqli($check_already_called,$link);

		$check_already_row = mysqli_fetch_row($check_already_rslt);

		if(isset($check_already_row[0]) && !empty($check_already_row[0])){

			writeErroIntoLogFile('Already called this mobile number =>'.$check_already_row[0],$log_file_name123);

			echo json_encode(array('success'=>0,'message'=>'Only 1 makecall is allowed per retailer per day'));

		}else{

			$insert_make_call = "INSERT INTO todays_make_call (`mobile_number`,`make_call_date`,`make_call_by`,`created_at`) values('$mobile_number','$today_date','$agent_id','$date_time')";

			writeErroIntoLogFile('insert_make_call =>'.$insert_make_call,$log_file_name123);

			$make_call_result = mysql_to_mysqli($insert_make_call,$link);
            
            writeErroIntoLogFile('Make call permitt=>'.$mobile_number,$log_file_name123);

			echo json_encode(array('success'=>1,'message'=>'Make call permitt'));
		}
		
	}else{
		
		writeErroIntoLogFile('AGENT REQUIRED =>',$log_file_name123);

		echo json_encode(array('success'=>1,'message'=>'agent id required!'));
	}
}

function writeErroIntoLogFile($msg,$log_file_name)
    {
    $date = date('d.m.Y G:i:s');
    $logText =  $date.'     |       '.$msg.PHP_EOL;
    error_log( $logText, 3, $log_file_name);
    }

?>
