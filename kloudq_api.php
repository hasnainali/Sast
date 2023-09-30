<?php
require_once("dbconnect_mysqli.php");
require_once("functions.php");

$log_file_name1 = '/var/log/tekdial/kloudq_api_logs/'.date('Y-m-d').'-submitAndDisposeCall.log';
$log_file_name2 = '/var/log/tekdial/kloudq_api_logs/'.date('Y-m-d').'-setCallReShedule.log';
$log_file_name3 = '/var/log/tekdial/kloudq_api_logs/'.date('Y-m-d').'-pushTodayReatilerList.log';

$stmt = "SELECT merchant_key,secret_key,token_generated FROM kloudq_client_token";
$rslt = mysql_to_mysqli($stmt,$link);
$row = mysqli_fetch_row($rslt);
$merchant_key = $row[0];
$secret_key = $row[1];
// $token_generated = $row[2];
$token_generated = "123";

if(isset($_GET['generateAccessToken'])){
	$json1 = file_get_contents('php://input');
	$json_array = json_decode($json1);
	$client_merchant_key = $json_array->merchant_key;
	$client_secret_key = $json_array->secret_key;
	if(($client_merchant_key==$merchant_key) && ($client_secret_key==$secret_key)){
		$token = rand();
		$update_stmt = "UPDATE kloudq_client_token SET token_generated='$token' WHERE merchant_key='$client_merchant_key' AND secret_key='$client_secret_key'";
		$update_rslt = mysql_to_mysqli($update_stmt,$link);
		echo json_encode(array('success'=>true,'message'=>'Token generated successfully!','token'=>$token));
	}
	else{
		echo json_encode(array('success'=>false,'message'=>'Invalid key'));
	}
}

if(isset($_GET['pushTodayReatilerList'])){
	writeErroIntoLogFile('****************************pushTodayReatilerList API Initiated****************************',$log_file_name3);
	$header = apache_request_headers(); 
	// $token = $header['Authorization'];
	$token = "123";
	if($token == $token_generated){
		$json1 = file_get_contents('php://input');
		$json_array = json_decode($json1);
		$retailer_code = array();
		$retailers_array = $json_array->data->element;
		$data_to_insert = count($retailers_array);
		$list_id = $json_array->data->list_id;
		$date = $json_array->data->date;
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
			$tech_dial_list_id = $json_array->data->tech_dial_list_id;
			$stmt_check_date = "SELECT id FROM dms_list_id WHERE date='$date'";
			// echo $stmt_check_date;
			$rslt_check_date = mysql_to_mysqli($stmt_check_date,$link);
			$num_row = mysqli_num_rows($rslt_check_date);
		} else {
			writeErroIntoLogFile('Incorrect date formate !',$log_file_name3);
			echo json_encode(array('success'=>false,'message'=>'Incorrect date formate !','failure_code'=>0));
		    return false;
		}
		if($num_row>0){
			if($tech_dial_list_id==0){
				writeErroIntoLogFile('Duplicate entry!',$log_file_name3);
				echo json_encode(array('success'=>false,'message'=>'Duplicate entry!','failure_code'=>0));
				return false;
			}
			else{
				$check_techDialListId = "SELECT id FROM dms_list_id WHERE id='$tech_dial_list_id'";
				$rslt_techDialListId = mysql_to_mysqli($check_techDialListId,$link);
				$num_row_techDialListId = mysqli_num_rows($rslt_techDialListId);
				if($num_row_techDialListId>0){
				for($i=0;$i<$data_to_insert;$i++){
					$stmt = "INSERT INTO `dms_retailers_data` (`tech_dial_list_id`,`list_id`,`retailer_code`,`retailer_name`,`owner_name`,`primary_mob_number`,`secondary_mob_number`,`time_slot`,`from_time`,`to_time`,`day_of_calling`,`language1`,`langauge2`,`Optional1`,`Optional2`,`Optional3`,`Optional4`,`Optional5`) VALUES ('$tech_dial_list_id','$list_id','".$retailers_array[$i]->retailer_code."','".$retailers_array[$i]->retailer_name."','".$retailers_array[$i]->owner_name."','".$retailers_array[$i]->primary_mob_number."','".$retailers_array[$i]->secondary_mob_number."','".$retailers_array[$i]->time_slot."','".$retailers_array[$i]->from_time."','".$retailers_array[$i]->to_time."','".$retailers_array[$i]->day_of_calling."','".$retailers_array[$i]->language1."','".$retailers_array[$i]->langauge2."','".$date."','".$retailers_array[$i]->Optional2."','".$retailers_array[$i]->Optional3."','".$retailers_array[$i]->Optional4."','".$retailers_array[$i]->Optional5."')";
					$rslt = mysql_to_mysqli($stmt,$link);
					if($rslt!='true'){
						$retailer_code[]['retailer_code'] = $retailers_array[$i]->retailer_code;
					}
				}
				$inserted_id = $tech_dial_list_id;
				}
				else{
					writeErroIntoLogFile('Incorrect Tech dial Id!',$log_file_name3);
					echo json_encode(array('success'=>false,'message'=>'Incorrect Tech dial Id!','failure_code'=>0));
					return false;
				}
			}
		}
		else{
			$stmt_add_list = "INSERT INTO `dms_list_id` (`list_id`,`date`) VALUES ('$list_id','$date')";
			$rslt_add_list = mysql_to_mysqli($stmt_add_list,$link);
			$inserted_id = mysqli_insert_id($link);
		// print_r($inserted_id);die();
			

			for($i=0;$i<$data_to_insert;$i++){
				$stmt = "INSERT INTO `dms_retailers_data` (`tech_dial_list_id`,`list_id`,`retailer_code`,`retailer_name`,`owner_name`,`primary_mob_number`,`secondary_mob_number`,`time_slot`,`from_time`,`to_time`,`day_of_calling`,`language1`,`langauge2`,`Optional1`,`Optional2`,`Optional3`,`Optional4`,`Optional5`) VALUES ('$inserted_id','$list_id','".$retailers_array[$i]->retailer_code."','".$retailers_array[$i]->retailer_name."','".$retailers_array[$i]->owner_name."','".$retailers_array[$i]->primary_mob_number."','".$retailers_array[$i]->secondary_mob_number."','".$retailers_array[$i]->time_slot."','".$retailers_array[$i]->from_time."','".$retailers_array[$i]->to_time."','".$retailers_array[$i]->day_of_calling."','".$retailers_array[$i]->language1."','".$retailers_array[$i]->langauge2."','".$date."','".$retailers_array[$i]->Optional2."','".$retailers_array[$i]->Optional3."','".$retailers_array[$i]->Optional4."','".$retailers_array[$i]->Optional5."')";
				$rslt = mysql_to_mysqli($stmt,$link);
				if($rslt!='true'){
					$retailer_code[]['retailer_code'] = $retailers_array[$i]->retailer_code;
				}
			}
		}
		$failed_query_count = count($retailer_code);
		if($failed_query_count==$data_to_insert){
			writeErroIntoLogFile('Complete failure (Upload data again)',$log_file_name3);
			echo json_encode(array('success'=>false,'message'=>'Complete failure (Upload data again)','failure_code'=>0));
		} elseif($failed_query_count>0){
			writeErroIntoLogFile('Partial Failure!',$log_file_name3);
			echo json_encode(array('success'=>false,'message'=>'Partial Failure!','failure_code'=>1,'retailers_data'=>$retailer_code,'tech_dial_list_id'=>$inserted_id));
		} else{
			writeErroIntoLogFile('Data inserted successfully!',$log_file_name3);
			echo json_encode(array('success'=>true,'message'=>'Data inserted successfully!'));
		}
	}
	else{
		writeErroIntoLogFile('Invalid token!',$log_file_name3);
		echo json_encode(array('success'=>false,'failure_code'=>2,'message'=>'Invalid token!'));
	}
}

if(isset($_GET['submitAndDisposeCall'])){
	writeErroIntoLogFile('****************************submitAndDisposeCall API Initiated****************************',$log_file_name1);
	$header = apache_request_headers(); 
	// $token = $header['Authorization'];
	$token = "123";
	if($token==$token_generated){

	$json1 = file_get_contents('php://input');
	$json_array = json_decode($json1);
	$NOW_TIME 		= date("Y-m-d H:i:s");
	$date_only = date("Y-m-d",strtotime($NOW_TIME));
	$user	=	$json_array->user;
	$RETAILERCODE = $json_array->RETAILERCODE;
	$ORDERFLAG = $json_array->ORDERFLAG;
	$status = 'IN_SALE';
	writeErroIntoLogFile('Retailer code: '.$RETAILERCODE,$log_file_name1);
	writeErroIntoLogFile('Oder flag: '.$ORDERFLAG,$log_file_name1);
	$h_stmt = "DELETE FROM vicidial_hopper WHERE vendor_lead_code='$RETAILERCODE'";
	writeErroIntoLogFile('Delete from hopper query: '.$h_stmt,$log_file_name1);
	$h_rslt = mysql_to_mysqli($h_stmt,$link);
	writeErroIntoLogFile('Delete from hopper result: '.$h_rslt,$log_file_name1);
		if($user!=''){
			$stmt="SELECT extension,dialplan_number,voicemail_id,phone_ip,computer_ip,server_ip,login,pass,status,active,phone_type,fullname,company from phones where login='$user' and active = 'Y';";
			$rslt=mysql_to_mysqli($stmt, $link);
			$row=mysqli_fetch_row($rslt);
			$server_ip=$row[5];
			//writeErroIntoLogFile('user = '.$user.' server_ip=: '.$server_ip,$log_file_name1);
			$get_user_status = "SELECT status FROM vicidial_live_agents WHERE user='$user' and server_ip='$server_ip'";
			$rslt_user_status = mysql_to_mysqli($get_user_status,$link);
			$row_user_status = mysqli_fetch_row($rslt_user_status);
			$num_row = mysqli_num_rows($rslt_user_status);

			$order = "INSERT INTO orders (retailer_code,orderflage,api,user) VALUES ('$RETAILERCODE','$ORDERFLAG','DISPO','$user')";
			$order_rslt = mysql_to_mysqli($order,$link);

			$retailer_list_update = "UPDATE dms_retailers_data SET Optional3 = '$ORDERFLAG' WHERE retailer_code = '$RETAILERCODE' AND Optional1 = '$date_only'";
			$rslt_priority_update = mysql_to_mysqli($retailer_list_update,$link);

			//Disable another calls for retailer code
			$stmt_called_count = "SELECT called_count FROM vicidial_list WHERE vendor_lead_code = '$RETAILERCODE' AND `entry_date` >= '$date_only 00:00:00' AND `entry_date` <= '$date_only 23:59:59'";
			$rslt_called_count = mysql_to_mysqli($stmt_called_count,$link);
			$row_called_count = mysqli_fetch_row($rslt_called_count);
			$called_count = $row_called_count[0];
			if($called_count == 0){
				$make_insale = "UPDATE vicidial_list SET status = '$status', called_since_last_reset = 'Y' WHERE vendor_lead_code='$RETAILERCODE' AND entry_date>='$date_only 00:00:00' AND entry_date<='$date_only 23:59:59'";
			} else{
				$make_insale = "UPDATE vicidial_list SET status = '$status' WHERE vendor_lead_code='$RETAILERCODE' AND entry_date>='$date_only 00:00:00' AND entry_date<='$date_only 23:59:59'";
			}

			
			$insale_rslt = mysql_to_mysqli($make_insale,$link);
			writeErroIntoLogFile('Update list SALE marked query: '.$make_insale,$log_file_name1);
			writeErroIntoLogFile('Update list SALE marked result: '.$insale_rslt,$log_file_name1);
 
			$get_lead = "SELECT lead_id FROM vicidial_list WHERE vendor_lead_code='$RETAILERCODE' AND entry_date>='$date_only 00:00:00' AND entry_date<='$date_only 23:59:59'";
			$getLead_rslt = mysql_to_mysqli($get_lead,$link);
			$getLead_row = mysqli_fetch_row($getLead_rslt);
			$lead_id = $getLead_row[0];

			$disable_callbk = "UPDATE vicidial_callbacks SET status='INACTIVE' WHERE lead_id='$lead_id' AND entry_time>='$date_only 00:00:00' AND entry_time<='$date_only 23:59:59'";
			$disable_callbk_rslt = mysql_to_mysqli($disable_callbk,$link);
			writeErroIntoLogFile('Disabled callback if available query: '.$disable_callbk,$log_file_name1);
			writeErroIntoLogFile('Disabled callback if available result: '.$disable_callbk_rslt,$log_file_name1);


			if($row_user_status[0]=='INCALL'){
				$stmt="UPDATE vicidial_live_agents set status='READY' where user='$user' and server_ip='$server_ip'";
				//$stmt="UPDATE vicidial_live_agents set status='READY' where user='$user' and server_ip='172.16.0.6'";
				writeErroIntoLogFile('stmt: '.$stmt,$log_file_name1);
				$rslt=mysql_to_mysqli($stmt, $link);
				$affected_row = mysqli_affected_rows($link);
				if($affected_row==1){
					writeErroIntoLogFile('Successfully Dispoased Call! protocol:'.$_SERVER['SERVER_PROTOCOL'],$log_file_name1);
					echo json_encode(array('success'=>true,'message'=>"Successfully Dispoased Call!"));
				}
				else{
					writeErroIntoLogFile('Agent status not updated, Somthing went wrong '.$user,$log_file_name1);
					echo json_encode(array('success'=>false,'failure_code'=>0,'message'=>'Somthing went wrong!'.$user));
				}
			}
			else{
				$stmt = "SELECT status FROM agent_current_status WHERE user='$user'";
				$rslt = mysql_to_mysqli($stmt,$link);
				$row_2 = mysqli_fetch_row($rslt);
				if($row_2[0]!='Idle'){
					$stmt="UPDATE vicidial_live_agents set status='READY' where user='$user' and server_ip='$server_ip'";
					$rslt=mysql_to_mysqli($stmt, $link);
					$affected_row = mysqli_affected_rows($link);
					if($affected_row==1){
						writeErroIntoLogFile('Successfully Dispoased Call! protocol:'.$_SERVER['SERVER_PROTOCOL'],$log_file_name1);
						echo json_encode(array('success'=>true,'message'=>"Successfully Dispoased Call!"));
					}
					else{
						writeErroIntoLogFile('Somthing went wrong '.$user,$log_file_name1);
						echo json_encode(array('success'=>false,'failure_code'=>0,'message'=>'Somthing went wrong! '.$user));
						//echo json_encode(array('success'=>false,'failure_code'=>0,'message'=>'Somthing went wrong! '.$stmt.' user= '.$user));
					}
				}
				else{
					writeErroIntoLogFile('Agent is not oncall! '.$user,$log_file_name1);
					echo json_encode(array('success'=>false,'failure_code'=>0,'message'=>'Agent is not oncall! '.$user));
				}
			}
		}
		else{
			writeErroIntoLogFile('User not defined!'.$user,$log_file_name1);
			echo json_encode(array('success'=>false,'failure_code'=>0,'message'=>'User not defined!'.$user));
		}
	} else{
		writeErroIntoLogFile('Invalid token!'.$user,$log_file_name1);
		echo json_encode(array('success'=>false,'failure_code'=>2,'message'=>'Invalid token!'));
	}
}

if(isset($_GET['setCallReShedule'])){
	writeErroIntoLogFile('****************************setCallReShedule API Initiated****************************',$log_file_name2);
	$header = apache_request_headers(); 
	// $token = $header['Authorization'];
	$token = "123";
	if($token==$token_generated){

	$json1 = file_get_contents('php://input');
	$json_array = json_decode($json1);
	$NOW_TIME 		= date("Y-m-d H:i:s");
	$date_only = date("Y-m-d",strtotime($NOW_TIME));
	$user = $json_array->user;
	$RETAILERCODE = $json_array->RETAILERCODE;
	$ORDERFLAG = $json_array->ORDERFLAG;
	$CallBackDatETimE = $json_array->callbackDateTime;
	writeErroIntoLogFile('Retailer code: '.$RETAILERCODE,$log_file_name2);
	writeErroIntoLogFile('Oder flag: '.$ORDERFLAG,$log_file_name2);
		if($user!='' && $CallBackDatETimE!=''){
			$order = "INSERT INTO orders (retailer_code,orderflage,api,user) VALUES ('$RETAILERCODE','$ORDERFLAG','CB','$user')";
			$order_rslt = mysql_to_mysqli($order,$link);

			$retailer_list_update = "UPDATE dms_retailers_data SET Optional3 = 'callback' WHERE retailer_code = '$RETAILERCODE' AND Optional1 = '$date_only'";
			$rslt_priority_update = mysql_to_mysqli($retailer_list_update,$link);

			$get_phone = "SELECT primary_mob_number FROM dms_retailers_data WHERE retailer_code='$RETAILERCODE' AND dated>='$date_only 00:00:00' AND dated<='$date_only 23:59:59'";
			$get_phone_rslt = mysql_to_mysqli($get_phone,$link);
			$row_phone = mysqli_fetch_row($get_phone_rslt);
			$phone_number = $row_phone[0];
			$stmt_list = "SELECT last_number,acs.status,list_id FROM agent_current_status as acs left join vicidial_list as vl on vl.phone_number=acs.last_number AND vl.list_id!='999' WHERE acs.user='$user' order by vl.lead_id DESC";
			$rslt_list = mysql_to_mysqli($stmt_list,$link);
			$row_list = mysqli_fetch_row($rslt_list);
			$phoneNo = $row_list[0];
			$status = $row_list[1];
			$list_id = $row_list[2];
			if($status=='ACW' || $status=='Oncall' || $status=='Consult'){
				// $get_user_info = "SELECT vu.user_group,vc.campaign_id FROM vicidial_users as vu left join vicidial_campaigns as vc on vc.user_group=vu.user_group WHERE vu.user='$user'";
				$get_user_info = "SELECT vu.user_group,vla.campaign_id FROM vicidial_users as vu left join vicidial_live_agents as vla on vla.user=vu.user WHERE vu.user='$user'";
				$rslt_user_info = mysql_to_mysqli($get_user_info,$link);
				$row_user_info = mysqli_fetch_row($rslt_user_info);
				$user_group = $row_user_info[0];
				$campaign = $row_user_info[1];

				

				// $stmtA = "INSERT INTO vicidial_list (entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,called_since_last_reset,phone_code,phone_number,security_phrase,called_count,gmt_offset_now,comments$entry_list_idSQLa$INSERTstateSQLa$INSERTpostal_codeSQLa$populate_provinceA) values('$NOW_TIME','$NOW_TIME','CALLBK','VDAD','NHAI3','VDCL','$list_id','Y','1','$phoneNo','$ingroup_insert','0','-5.00','$VLcomments'$entry_list_idSQLb$INSERTstateSQLb$INSERTpostal_codeSQLb$populate_provinceB);";

				// $stmtD = "UPDATE vicidial_list SET status='CALLBK',called_since_last_reset='Y' WHERE phone_number='$phone_number' AND vendor_lead_code = '$RETAILERCODE' AND entry_date>='$date_only 00:00:00' AND entry_date<='$date_only 23:59:59'";
				// $rsltD = mysql_to_mysqli($stmtD,$link);
				
				$stmtB = "SELECT lead_id FROM vicidial_list WHERE vendor_lead_code = '$RETAILERCODE' AND entry_date>='$date_only 00:00:00' AND entry_date<='$date_only 23:59:59'";
				$rsltB = mysql_to_mysqli($stmtB,$link);
				$lead_row = mysqli_fetch_row($rsltB);
				$lead_id = $lead_row[0];
				$stmt="INSERT INTO vicidial_callbacks (lead_id,list_id,campaign_id,status,entry_time,callback_time,user,recipient,comments,user_group,lead_status,customer_timezone,customer_timezone_diff,customer_time) values('$lead_id','$list_id','$campaign','ACTIVE','$NOW_TIME','$CallBackDatETimE','$user','ANYONE','','$user_group','CALLBK','$callback_timezone','$callback_gmt_offset','$NOW_TIME');";
				// if ($DB) {echo "$stmt\n";}
				$rslt=mysql_to_mysqli($stmt, $link);
				
				if($rslt){
					
					$stmt_called_count = "SELECT called_count FROM vicidial_list WHERE vendor_lead_code = '$RETAILERCODE' AND `entry_date` >= '$date_only 00:00:00' AND `entry_date` <= '$date_only 23:59:59'";
					$rslt_called_count = mysql_to_mysqli($stmt_called_count,$link);
					$row_called_count = mysqli_fetch_row($rslt_called_count);
					$called_count = $row_called_count[0];
					if($called_count == 0){
						$stmtA = "UPDATE vicidial_list SET IN_CALL='CB',called_since_last_reset='Y',status='IN_SALE' WHERE phone_number='$phone_number' AND entry_date>='$date_only 00:00:00' AND entry_date<='$date_only 23:59:59'";
					} else{
						$stmtA = "UPDATE vicidial_log SET callback='Y' WHERE phone_number='$phone_number' AND user='$user' AND called_count='$called_count' AND call_date>='$date_only 00:00:00' AND call_date<='$date_only 23:59:59'";

						$stmtD = "UPDATE vicidial_list SET status='IN_SALE',CB='Y' WHERE phone_number='$phone_number' AND vendor_lead_code = '$RETAILERCODE' AND entry_date>='$date_only 00:00:00' AND entry_date<='$date_only 23:59:59'";
						$rsltD = mysql_to_mysqli($stmtD,$link);
					}
					$rslt=mysql_to_mysqli($stmtA, $link);
					// echo dispoaseCall($user,$link,$stmtA);
					$stmt="SELECT extension,dialplan_number,voicemail_id,phone_ip,computer_ip,server_ip,login,pass,status,active,phone_type,fullname,company from phones where login='$user' and active = 'Y';";
					  $rslt=mysql_to_mysqli($stmt, $link);
					  $row=mysqli_fetch_row($rslt);
					  $server_ip=$row[5];
					  // echo $server_ip;
					  $get_user_status = "SELECT status FROM vicidial_live_agents WHERE user='$user' and server_ip='$server_ip'";
					  $rslt_user_status = mysql_to_mysqli($get_user_status,$link);
					  $row_user_status = mysqli_fetch_row($rslt_user_status);
					    if($row_user_status[0]=='INCALL'){
					    $stmt="UPDATE vicidial_live_agents set status='READY' where user='$user' and server_ip='$server_ip'";
					    $rslt=mysql_to_mysqli($stmt, $link);
					    writeErroIntoLogFile('UPDATE_STMT: '.$stmt,$log_file_name2);
					  }
					  else{
					    $stmt = "SELECT status FROM agent_current_status WHERE user='$user'";
					    $rslt = mysql_to_mysqli($stmt,$link);
					    $row = mysqli_fetch_row($rslt);
					    if($row[0]=='ACW'){
					      $stmt="UPDATE vicidial_live_agents set status='READY' where user='$user' and server_ip='$server_ip'";
					      $rslt=mysql_to_mysqli($stmt, $link);
					      writeErroIntoLogFile('Retailer code: '.$stmt,$log_file_name2);
					    }
					  }
					
					// $affected_rows = mysqli_affected_rows($link);
					  writeErroIntoLogFile('Successfully callback set!',$log_file_name2);
					echo json_encode(array('success'=>true,'message'=>'Successfully callback set!'));
				} 
				else{
					writeErroIntoLogFile('Somthing went wrong',$log_file_name2);
					echo json_encode(array('success'=>false,'message'=>'Somthing went wrong','user'=>$user));
				}
			} else{
				writeErroIntoLogFile('You cannot schedule callback after disposed!',$log_file_name2);
				echo json_encode(array('success'=>false,'failure_code'=>0,'message'=>'You cannot schedule callback after disposed!','user'=>$user));
			}
		} else{
			writeErroIntoLogFile('Incomplete information provided!',$log_file_name2);
			echo json_encode(array('success'=>false,'failure_code'=>0,'message'=>'Incomplete information provided!','user'=>$user));
		}
	} else{
		writeErroIntoLogFile('Invalid token!',$log_file_name2);
		echo json_encode(array('success'=>false,'failure_code'=>2,'message'=>'Invalid token!'));
	}
}

function writeErroIntoLogFile($msg,$log_file_name)
    {
    $date = date('d.m.Y G:i:s');
    $logText =  $date.'     |       '.$msg.PHP_EOL;
    error_log( $logText, 3, $log_file_name);
    }
