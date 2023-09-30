<?php
require_once("dbconnect_mysqli.php");
require_once("functions.php");

$logAccessToken = '/var/log/tekdial/dms_api_log/'.date('Y-m-d').'-generateAccessToken.log';
$logDisposeCall = '/var/log/tekdial/dms_api_log/'.date('Y-m-d').'-submitAndDisposeCall.log';
$log_file_name2 = '/var/log/tekdial/dms_api_log/'.date('Y-m-d').'-setCallReShedule.log';
$logPushData = '/var/log/tekdial/dms_api_log/'.date('Y-m-d').'-pushTodayReatilerList.log';
$logNotConCall = '/var/log/tekdial/dms_api_log/'.date('Y-m-d').'-getNotConnectNumber.log';
$todayListUploadLog = '/var/log/tekdial/dms_api_log/'.date('Y-m-d').'-todayListUpload.log';
$state_api = '/var/log/tekdial/dms_api_log/'.date('Y-m-d').'-state_api.log';

writeErroIntoLogFile('****************************file start submit and dispose****************************',$logDisposeCall);

/**
* load token, marchent and scecret key
*/
$stmtA = "SELECT merchant_key,secret_key,token_generated FROM dms_client_token";
$rsltA = mysql_to_mysqli($stmtA,$link);
$rowA = mysqli_fetch_row($rsltA);
$db_merchant_key = $rowA[0];
$db_secret_key = $rowA[1];
$db_token = $rowA[2];


/**
* generate tocken
*/
if(isset($_GET['generateAccessToken_temp_stop'])){
	writeErroIntoLogFile('***********************generateAccessToken API Initiated***********************',$logAccessToken);
	$json1 = file_get_contents('php://input');
	$json_array = json_decode($json1);
	$client_merchant_key = $json_array->merchant_key;
	$client_secret_key = $json_array->secret_key;
	writeErroIntoLogFile('Input: client_merchant_key=>'.$client_merchant_key.' and client_secret_key=>'.$client_secret_key,$logAccessToken);
	
	if(($client_merchant_key==$db_merchant_key) && ($client_secret_key==$db_secret_key)){
		$token = rand();
		writeErroIntoLogFile('Generated tocken=>'.$token,$logAccessToken);
		$update_stmt = "UPDATE dms_client_token SET token_generated='$token' WHERE merchant_key='$client_merchant_key' AND secret_key='$client_secret_key'";
		$update_rslt = mysql_to_mysqli($update_stmt,$link);
		writeErroIntoLogFile('Update tocken query: '.$update_stmt,$logAccessToken);

		echo $jsonResponse = json_encode(array('success'=>true,'message'=>'Token generated successfully!','token'=>$token));
		writeErroIntoLogFile('Response sent: '.$jsonResponse,$logAccessToken);
	}
	else{
		echo $jsonResponse = json_encode(array('success'=>false,'message'=>'Invalid marchent=>'.$client_merchant_key.' and secret key=>'.$client_secret_key));
		writeErroIntoLogFile('Response sent: '.$jsonResponse,$logAccessToken);
	}

	writeErroIntoLogFile('***********************generateAccessToken API END***********************',$logAccessToken);
}

/**
* push todays calling retailers data
*/
if(isset($_GET['pushTodayReatilerList'])){
	writeErroIntoLogFile('****************************pushTodayReatilerList API Initiated****************************',$logPushData);
	$header = apache_request_headers(); 
	$token = $header['Authorization'];
	writeErroIntoLogFile('API(Header) Token=>'.$token,$logPushData);
	writeErroIntoLogFile('Database Token=>'.$db_token,$logPushData);

	if($token == $db_token){
		writeErroIntoLogFile('Token Matched',$logPushData);
		$json1 = file_get_contents('php://input');
		$json_array = json_decode($json1);
		writeErroIntoLogFile('Input Data'.$json1,$logPushData);
		$retailer_code = array();
		$retailer_err_message = array();
		$retailers_array = $json_array->data->element;
		$data_to_insert = count($retailers_array);
		$list_id = $json_array->data->list_id;
		$date = $json_array->data->date;
		$campaign_id = $json_array->data->campaign_id;
		$success_count=0;	
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
			$tech_dial_list_id = $json_array->data->tech_dial_list_id;
			$stmt_check_date = "SELECT id FROM dms_list_id WHERE `date`='$date' ";
			// echo $stmt_check_date;
			writeErroIntoLogFile('Check dms_list_id => '.$stmt_check_date,$logPushData);
			$rslt_check_date = mysql_to_mysqli($stmt_check_date,$link);
			$num_row = mysqli_num_rows($rslt_check_date);
	        writeErroIntoLogFile('Check dms_list_id Count => '.$num_row,$logPushData);

		}else{
			writeErroIntoLogFile('Incorrect date formate !',$logPushData);
			echo json_encode(array('success'=>false,'message'=>'Incorrect date formate !','failure_code'=>0));
		    return false;
		}
		//echo $num_row."num_row";die();
		if($num_row>0){
            

			if($tech_dial_list_id==0){
				writeErroIntoLogFile('Duplicate entry!',$logPushData);
				echo json_encode(array('success'=>false,'message'=>'Already Exist!','failure_code'=>0));
				return false;
			}else{
				$check_techDialListId = "SELECT id FROM dms_list_id WHERE id='$tech_dial_list_id'";
				writeErroIntoLogFile('check_techDialListId query !'.$check_techDialListId,$logPushData);

				$rslt_techDialListId = mysql_to_mysqli($check_techDialListId,$link);
				$num_row_techDialListId = mysqli_num_rows($rslt_techDialListId);
				if($num_row_techDialListId>0){
				    writeErroIntoLogFile('check_techDialListId query Row Count'.$num_row_techDialListId,$logPushData);
					for($i=0;$i<$data_to_insert;$i++){
							$stmt = "INSERT INTO `dms_retailers_data` (`tech_dial_list_id`,`list_id`,`retailer_code`,`retailer_name`,`owner_name`,`primary_mob_number`,`secondary_mob_number`,`time_slot`,`from_time`,`to_time`,`day_of_calling`,`language1`,`langauge2`,`Optional1`,`Optional2`,`Optional4`,`agent_id`) VALUES ('$tech_dial_list_id','$list_id','".$retailers_array[$i]->retailer_code."','".$retailers_array[$i]->retailer_name."','".$retailers_array[$i]->owner_name."','".$retailers_array[$i]->primary_mob_number."','".$retailers_array[$i]->secondary_mob_number."','".$retailers_array[$i]->time_slot."','".$retailers_array[$i]->from_time."','".$retailers_array[$i]->to_time."','".$retailers_array[$i]->day_of_calling."','".$retailers_array[$i]->language1."','".$retailers_array[$i]->langauge2."','".$date."','".$retailers_array[$i]->priority."','".$retailers_array[$i]->state_id."','".$retailers_array[$i]->agent_id."')";
								writeErroIntoLogFile('Insert DMS Retailer Data'.$stmt,$logPushData);
								// if(!empty($retailers_array[$i]->agent_id)){
        //                             $rslt = mysql_to_mysqli($stmt,$link);
								// }else{
								// 	$retailer_err_message[]['retailer_message']="Agent Id is Empty";
								// 	$rslt =false;

								// }
							
							 // if($rslt!='true'){
					   //     	   writeErroIntoLogFile('Empty Agent Id',$logPushData);
        //       				   writeErroIntoLogFile('Failed To Insert Into DMS Retailer Data for retailer_code => '.$retailers_array[$i]->retailer_code,$logPushData);
        //                        $retailer_code[]['retailer_code'] = $retailers_array[$i]->retailer_code;
					   //       }else{
					   //          writeErroIntoLogFile('Successfully Inserted Into DMS Retailer Data for retailer_code =>  '.$retailers_array[$i]->retailer_code,$logPushData);
				    //          }
								$flag=true;
								if(empty($retailers_array[$i]->agent_id)){
				                        $retailer_err_message['retailer_message'][]="Agent Id is Required ".$retailers_array[$i]->retailer_code;
								     	$flag =false;
								}
								if(empty($retailers_array[$i]->time_slot)){
									    $retailer_err_message['retailer_message'][]="Time Slot is Required ".$retailers_array[$i]->retailer_code;
								     	$flag =false;
								}
								if(empty($retailers_array[$i]->from_time)){
									    $retailer_err_message['retailer_message'][]="From Time is Required ".$retailers_array[$i]->retailer_code;
								     	$flag =false;
								}
								if(empty($retailers_array[$i]->to_time)){
									    $retailer_err_message['retailer_message'][]="To Time is Required ".$retailers_array[$i]->retailer_code;
								     	$flag =false;
								}
								if(empty($retailers_array[$i]->retailer_code)){
									    $retailer_err_message['retailer_message'][]="Retailer Code is Required ".$retailers_array[$i]->retailer_code;
								     	$flag =false;
								}
								if(empty($retailers_array[$i]->primary_mob_number)){
									    $retailer_err_message['retailer_message'][]="Primary Mobile Number is Required ".$retailers_array[$i]->retailer_code;
								     	$flag =false;
								}
								if($retailers_array[$i]->to_time == $retailers_array[$i]->from_time){
									    $retailer_err_message['retailer_message'][]="From Time Not Equal To Time ".$retailers_array[$i]->retailer_code;
								     	$flag =false;
								}
								if(empty($retailers_array[$i]->language1)){
									    $retailer_err_message['retailer_message'][]="Language 1 Is Required - ".$retailers_array[$i]->retailer_code;
								     	$flag =false;
								}
								writeErroIntoLogFile('Insert query Into DMS Retailer Data => '.$stmt,$logPushData);
								$rslt ='';
								// echo "yes";echo $flag;die();
								if($flag ==true){
									$rslt = mysql_to_mysqli($stmt,$link);
								}else{
									$rslt=false;
								}
								if($rslt!=true){
									//writeErroIntoLogFile('Empty Agent Id',$logPushData);
									writeErroIntoLogFile('Failed To Insert Into DMS Retailer Data for retailer_code => '.$retailers_array[$i]->retailer_code,$logPushData);
									$retailer_code[]['retailer_code'] = $retailers_array[$i]->retailer_code;
								}else{
									//$rslt = mysql_to_mysqli($stmt,$link);
									$success_count++;
									writeErroIntoLogFile('Successfully Inserted Into DMS Retailer Data for retailer_code =>  '.$retailers_array[$i]->retailer_code,$logPushData);
								}
							
					}
                   $inserted_id = $tech_dial_list_id;
				}else{
					writeErroIntoLogFile('Incorrect Tech dial Id!',$logPushData);
					echo json_encode(array('success'=>false,'message'=>'Incorrect Tech dial Id!','failure_code'=>0));
					return false;
				}
			}
		}else{
			writeErroIntoLogFile('No Duplicate entry => '.$stmt_add_list,$logPushData);
			$stmt_add_list = "INSERT INTO `dms_list_id` (`list_id`,`date`,`campaign_id`) VALUES ('$list_id','$date','$campaign_id')";
			writeErroIntoLogFile('Insert Into dms_list_id stmt_add_list query => '.$stmt_add_list,$logPushData);
			$rslt_add_list = mysql_to_mysqli($stmt_add_list,$link);
			$inserted_id = mysqli_insert_id($link);
			writeErroIntoLogFile('Generated dms_list_id tech_dial_list_id is => '.$inserted_id,$logPushData);
		// print_r($inserted_id);die();
			
			for($i=0;$i<$data_to_insert;$i++){
				$stmt = "INSERT INTO `dms_retailers_data` (`tech_dial_list_id`,`list_id`,`retailer_code`,`retailer_name`,`owner_name`,`primary_mob_number`,`secondary_mob_number`,`time_slot`,`from_time`,`to_time`,`day_of_calling`,`language1`,`langauge2`,`Optional1`,`Optional2`,`Optional4`,`agent_id`) VALUES ('$inserted_id','$list_id','".$retailers_array[$i]->retailer_code."','".$retailers_array[$i]->retailer_name."','".$retailers_array[$i]->owner_name."','".$retailers_array[$i]->primary_mob_number."','".$retailers_array[$i]->secondary_mob_number."','".$retailers_array[$i]->time_slot."','".$retailers_array[$i]->from_time."','".$retailers_array[$i]->to_time."','".$retailers_array[$i]->day_of_calling."','".$retailers_array[$i]->language1."','".$retailers_array[$i]->langauge2."','".$date."','".$retailers_array[$i]->priority."','".$retailers_array[$i]->state_id."','".$retailers_array[$i]->agent_id."')";
				// $rslt = mysql_to_mysqli($stmt,$link);
					$flag=true;
				if(empty($retailers_array[$i]->agent_id)){
                        $retailer_err_message['retailer_message'][]="Agent Id is Required - ".$retailers_array[$i]->retailer_code;
				     	$flag =false;
				}
				if(empty($retailers_array[$i]->time_slot)){
					    $retailer_err_message['retailer_message'][]="Time Slot is Required - ".$retailers_array[$i]->retailer_code;
				     	$flag =false;
				}
				if(empty($retailers_array[$i]->from_time)){
					    $retailer_err_message['retailer_message'][]="From Time is Required - ".$retailers_array[$i]->retailer_code;
				     	$flag =false;
				}
				if(empty($retailers_array[$i]->to_time)){
					    $retailer_err_message['retailer_message'][]="To Time is Required - ".$retailers_array[$i]->retailer_code;
				     	$flag =false;
				}
				if(empty($retailers_array[$i]->retailer_code)){
					    $retailer_err_message['retailer_message'][]="Retailer Code is Required - ".$retailers_array[$i]->retailer_code;
				     	$flag =false;
				}
				if(empty($retailers_array[$i]->primary_mob_number)){
					    $retailer_err_message['retailer_message'][]="Primary Mobile Number is Required - ".$retailers_array[$i]->retailer_code;
				     	$flag =false;
				}
				if($retailers_array[$i]->to_time == $retailers_array[$i]->from_time){
					    $retailer_err_message['retailer_message'][]="From Time Not Equal To Time - ".$retailers_array[$i]->retailer_code;
				     	$flag =false;
				}
				if(empty($retailers_array[$i]->language1)){
					    $retailer_err_message['retailer_message'][]="Language 1 Is Required - ".$retailers_array[$i]->retailer_code;
				     	$flag =false;
				}
				writeErroIntoLogFile('Insert query Into DMS Retailer Data => '.$stmt,$logPushData);
				writeErroIntoLogFile('Agent Id => '.$retailers_array[$i]->agent_id,$logPushData);
				$rslt ='';
				// echo "yes";echo $flag;die();
				if($flag ==true){
					$rslt = mysql_to_mysqli($stmt,$link);
				}else{
					$rslt=false;
				}
				if($rslt!=true){
					//writeErroIntoLogFile('Empty Agent Id',$logPushData);
					writeErroIntoLogFile('Failed To Insert Into DMS Retailer Data for retailer_code => '.$retailers_array[$i]->retailer_code,$logPushData);
					$retailer_code[]['retailer_code'] = $retailers_array[$i]->retailer_code;
				}else{
					//$rslt = mysql_to_mysqli($stmt,$link);
					$success_count++;
					writeErroIntoLogFile('Successfully Inserted Into DMS Retailer Data for retailer_code =>  '.$retailers_array[$i]->retailer_code,$logPushData);
				}
			}
		}
		$failed_query_count = count($retailer_code);
		if($failed_query_count==$data_to_insert){
			echo $jsonResponse = json_encode(array('success'=>false,'message'=>'Complete failure (Upload data again)','failure_message'=>$retailer_err_message,'failure_code'=>0,'success_count'=>$success_count));
			writeErroIntoLogFile('Complete failure Response (Upload data again) => '.$jsonResponse,$logPushData);
		}
		elseif($failed_query_count>0){
			echo $jsonResponse = json_encode(array('success'=>false,'message'=>'Partial Failure!','failure_code'=>1,'retailers_data'=>$retailer_code,'failure_message'=>$retailer_err_message,'tech_dial_list_id'=>$inserted_id,'failure_code'=>0,'success_count'=>$success_count));
			writeErroIntoLogFile('Partial Failure Response ! '.$jsonResponse,$logPushData);
		}
		else{
			echo $jsonResponse = json_encode(array('success'=>true,'message'=>'Data inserted successfully!','success_count'=>$success_count));
			writeErroIntoLogFile('Data inserted successfully! '.$jsonResponse,$logPushData);
		}
	}else{
		echo $jsonResponse = json_encode(array('success'=>false,'failure_code'=>2,'message'=>'Invalid token!'));
		writeErroIntoLogFile('Invalid token Response: '.$jsonResponse,$logPushData);
	}

	writeErroIntoLogFile('****************************pushTodayReatilerList API end****************************',$logPushData);
}

if(isset($_GET['submitAndDisposeCall'])){
	writeErroIntoLogFile('****************************submitAndDisposeCall API called ****************************',$logDisposeCall);
	$header = apache_request_headers(); 
	$token = $header['Authorization'];
	//$token = "123";
	if($token==$db_token){

	$json1 = file_get_contents('php://input');
	$json_array = json_decode($json1);
	$NOW_TIME 		= date("Y-m-d H:i:s");
	$date_only = date("Y-m-d",strtotime($NOW_TIME));
	$user	=	$json_array->user;
	$RETAILERCODE = $json_array->RETAILERCODE;
	$ORDERFLAG = $json_array->ORDERFLAG;
	$CallBackDatETimE = $json_array->callbackDateTime;
	if(isset($json_array->is_continued)){
	   $is_continued = $json_array->is_continued;	
	}else{
		$is_continued = 0;
	}
	writeErroIntoLogFile('Is Continued  '.$is_continued,$logDisposeCall);

	writeErroIntoLogFile('Agent Id: '.$user,$logDisposeCall);
	writeErroIntoLogFile('Retailer code: '.$RETAILERCODE,$logDisposeCall);
	writeErroIntoLogFile('Oder flag: '.$ORDERFLAG,$logDisposeCall);
	

	$user_query = "SELECT user FROM vicidial_list WHERE vendor_lead_code = '$RETAILERCODE' AND entry_date>='$date_only 00:00:00' AND entry_date<='$date_only 23:59:59'";

	writeErroIntoLogFile('Find User Query: '.$user_query,$logDisposeCall);
	$user_result = mysql_to_mysqli($user_query,$link);
	$row_user = mysqli_fetch_row($user_result);
	$check_user = $row_user[0];
		if($check_user != $user){
			writeErroIntoLogFile('Received Agent Id: '.$user,$logDisposeCall);
			writeErroIntoLogFile('Required Agent Id: '.$check_user,$logDisposeCall);
			//echo json_encode(array('success'=>false,'failure_code'=>0,'message'=>'Invalid Agent Id!'));
			
		}
		writeErroIntoLogFile('Received Agent Id: '.$user,$logDisposeCall);
		writeErroIntoLogFile('Required Agent Id: '.$check_user,$logDisposeCall);

		/**
		* Dispose call if agent if not blank
		*/
		$status = 'IN_SALE';
		$h_stmt = "DELETE FROM vicidial_hopper WHERE vendor_lead_code='$RETAILERCODE'";
		writeErroIntoLogFile('Delete from hopper query: '.$h_stmt,$logDisposeCall);
		$h_rslt = mysql_to_mysqli($h_stmt,$link);
		writeErroIntoLogFile('Delete from hopper result: '.$h_rslt,$logDisposeCall);
		
		if($user!=''){
			if($ORDERFLAG==4 && !empty($CallBackDatETimE)){
				writeErroIntoLogFile('CallBackDatETimE : '.$CallBackDatETimE,$logDisposeCall);
			    setCallReschedule($ORDERFLAG,$user,$CallBackDatETimE,$RETAILERCODE,$logDisposeCall,$date_only,$link,$status);
			}else{
				submitAndDisposeCall($ORDERFLAG,$user,$RETAILERCODE,$logDisposeCall,$date_only,$link,$status,$is_continued);
			}
		}else{
			writeErroIntoLogFile('User not defined!'.$user,$logDisposeCall);
			echo json_encode(array('success'=>false,'failure_code'=>0,'message'=>'User not defined!'.$user));
		}
		  
	}else{
			writeErroIntoLogFile('Invalid token!'.$user,$logDisposeCall);
			echo json_encode(array('success'=>false,'failure_code'=>2,'message'=>'Invalid token!'));
	}
	
	
}

function setCallReschedule($ORDERFLAG,$user,$CallBackDatETimE,$RETAILERCODE,$logDisposeCall,$date_only,$link,$status){
	 if($ORDERFLAG ==4 && !empty($CallBackDatETimE)){

        $NOW_TIME 	= date("Y-m-d H:i:s");
        $current_time =strtotime($NOW_TIME);
        $call_backTime =strtotime($CallBackDatETimE);
        if($current_time < $call_backTime){
            $date_only = date("Y-m-d",strtotime($NOW_TIME));
			writeErroIntoLogFile('****************************setCallReShedule API start ****************************',$logDisposeCall);

			$RETAILERCODE = $RETAILERCODE;
			$CallBackDatETimE = $CallBackDatETimE;
			$user = $user;
			writeErroIntoLogFile('Retailer code: '.$RETAILERCODE,$logDisposeCall);
			writeErroIntoLogFile('Oder flag: '.$ORDERFLAG,$logDisposeCall);
			writeErroIntoLogFile('Callback Time: '.$CallBackDatETimE,$logDisposeCall);
				if($user!='' && $CallBackDatETimE!=''){
					$order = "INSERT INTO orders (retailer_code,orderflage,api,user) VALUES ('$RETAILERCODE','$ORDERFLAG','CB','$user')";
					writeErroIntoLogFile('insert Order query: '.$order,$logDisposeCall);
					$order_rslt = mysql_to_mysqli($order,$link);
					writeErroIntoLogFile('Order query result : '.$order_rslt,$logDisposeCall);


					$retailer_list_update = "UPDATE dms_retailers_data SET Optional3 = 'callback' WHERE retailer_code = '$RETAILERCODE' AND Optional1 = '$date_only'";
					writeErroIntoLogFile('update dms_retailers_data query: '.$retailer_list_update,$logDisposeCall);
					$rslt_priority_update = mysql_to_mysqli($retailer_list_update,$link);
					writeErroIntoLogFile('update dms_retailers_data query result : '.$rslt_priority_update,$logDisposeCall);

					$get_phone = "SELECT primary_mob_number FROM dms_retailers_data WHERE retailer_code='$RETAILERCODE' AND dated>='$date_only 00:00:00' AND dated<='$date_only 23:59:59'";
					$get_phone_rslt = mysql_to_mysqli($get_phone,$link);
					$row_phone = mysqli_fetch_row($get_phone_rslt);
					$phone_number = $row_phone[0];
					writeErroIntoLogFile('select phone number : '.$phone_number,$logDisposeCall);
					$stmt_list = "SELECT last_number,acs.status,list_id FROM agent_current_status as acs left join vicidial_list as vl on vl.phone_number=acs.last_number AND vl.list_id!='999' WHERE acs.user='$user' order by vl.lead_id DESC";
					$rslt_list = mysql_to_mysqli($stmt_list,$link);
					$row_list = mysqli_fetch_row($rslt_list);
					$phoneNo = $row_list[0];
					$status = $row_list[1];
					$list_id = $row_list[2];
					writeErroIntoLogFile('select agent status : '.$status,$logDisposeCall);
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
						
						$stmtB = "SELECT lead_id,list_id FROM vicidial_list WHERE vendor_lead_code = '$RETAILERCODE' AND entry_date>='$date_only 00:00:00' AND entry_date<='$date_only 23:59:59'";
						$rsltB = mysql_to_mysqli($stmtB,$link);
						$lead_row = mysqli_fetch_row($rsltB);
						$lead_id = $lead_row[0];
						$list_idss = $lead_row[1];
						writeErroIntoLogFile('select callback lead id : '.$lead_id,$logDisposeCall);
						writeErroIntoLogFile('select callback list_idss : '.$list_idss,$logDisposeCall);
						$get_lead1 = "SELECT vl.list_id,vla.lead_id FROM vicidial_live_agents vla left join  vicidial_list vl on vla.lead_id = vl.lead_id WHERE  vl.entry_date>='$date_only 00:00:00' AND vl.entry_date<='$date_only 23:59:59' and vla.user ='$user'";
						$getLead_rslt1 = mysql_to_mysqli($get_lead1,$link);
						$getLead_row1 = mysqli_fetch_row($getLead_rslt1);
						$list_idss1 = $getLead_row1[0];
						$list_lead_id = $getLead_row1[1];
						writeErroIntoLogFile('select callback list_idsssssssss : '.$list_idss1,$logDisposeCall);
						writeErroIntoLogFile('select callback list_lead_id : '.$list_lead_id,$logDisposeCall);
						writeErroIntoLogFile('select callback list_idsssssssss query  : '.$get_lead1,$logDisposeCall);
						$stmt="INSERT INTO vicidial_callbacks (lead_id,list_id,campaign_id,status,entry_time,callback_time,user,recipient,comments,user_group,lead_status,customer_timezone,customer_timezone_diff,customer_time) values('$lead_id','$list_id','$campaign','ACTIVE','$NOW_TIME','$CallBackDatETimE','$user','ANYONE','','$user_group','CALLBK','$callback_timezone','$callback_gmt_offset','$NOW_TIME');";
						// if ($DB) {echo "$stmt\n";}
						$rslt=mysql_to_mysqli($stmt, $link);
						writeErroIntoLogFile('insert in vicidial_callbacks query : '.$stmt,$logDisposeCall);
						writeErroIntoLogFile('insert in vicidial_callbacks query result : '.$rslt,$logDisposeCall);
						
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
							    writeErroIntoLogFile('UPDATE_STMT: '.$stmt,$logDisposeCall);
							  }
							  else{
							    $stmt = "SELECT status FROM agent_current_status WHERE user='$user'";
							    $rslt = mysql_to_mysqli($stmt,$link);
							    $row = mysqli_fetch_row($rslt);
							    if($row[0]=='ACW'){
							      $stmt="UPDATE vicidial_live_agents set status='READY' where user='$user' and server_ip='$server_ip'";
							      $rslt=mysql_to_mysqli($stmt, $link);
							      writeErroIntoLogFile('Retailer code: '.$stmt,$logDisposeCall);
							    }
							  }
							$calltype='';
							if(isset($list_idss1) && !empty($list_idss1)){
								 if($list_idss1 == "999"){
								 	$calltype='INBOUND';
								 }else{
								 	$calltype ='OUTBOUND';
								 }
							}
							// $affected_rows = mysqli_affected_rows($link);
				 			  writeErroIntoLogFile('calltype'.$calltype,$logDisposeCall);
				 			  writeErroIntoLogFile('Successfully callback set!',$logDisposeCall);
				 			echo json_encode(array('success'=>true,'message'=>'Successfully callback set!','calltype'=>$calltype));
						} 
						else{
							writeErroIntoLogFile('Somthing went wrong',$logDisposeCall);
							echo json_encode(array('success'=>false,'message'=>'Somthing went wrong','user'=>$user));
						}
					} else{
						writeErroIntoLogFile('You cannot schedule callback after disposed!',$logDisposeCall);
						echo json_encode(array('success'=>false,'failure_code'=>0,'message'=>'You cannot schedule callback after disposed!','user'=>$user));
					}
				} else{
					writeErroIntoLogFile('Incomplete information provided!',$logDisposeCall);
					echo json_encode(array('success'=>false,'failure_code'=>0,'message'=>'Incomplete information provided!','user'=>$user));
				}   
	   	}else{
           writeErroIntoLogFile('CallBackTime Should Be Greater Than Current Date Time',$logDisposeCall);
           echo json_encode(array('success'=>false,'message'=>'CallBackTime Should Be Greater Than Current Date Time'));
	   	}	
	   		    
      }
}

function submitAndDisposeCall($ORDERFLAG,$user,$RETAILERCODE,$logDisposeCall,$date_only,$link,$status,$is_continued){
	writeErroIntoLogFile('submitAndDisposeCall start:'.$_SERVER['SERVER_PROTOCOL'],$logDisposeCall);
	$h_stmt = "DELETE FROM vicidial_hopper WHERE vendor_lead_code='$RETAILERCODE'";
	writeErroIntoLogFile('Delete from hopper query: '.$h_stmt,$log_file_name1);
	$h_rslt = mysql_to_mysqli($h_stmt,$link);
	writeErroIntoLogFile('Delete from hopper result: '.$h_rslt,$log_file_name1);
	writeErroIntoLogFile('submitAndDisposeCall start:'.$_SERVER['SERVER_PROTOCOL'],$logDisposeCall);
	$stmt="SELECT extension,dialplan_number,voicemail_id,phone_ip,computer_ip,server_ip,login,pass,status,active,phone_type,fullname,company from phones where login='$user' and active = 'Y';";

			$rslt=mysql_to_mysqli($stmt, $link);
			$row=mysqli_fetch_row($rslt);
			$server_ip=$row[5];
			// print_r($row);
			// echo $stmt;die();
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
			writeErroIntoLogFile('Update list SALE marked query: '.$make_insale,$logDisposeCall);
			writeErroIntoLogFile('Update list SALE marked result: '.$insale_rslt,$logDisposeCall);
 
			$get_lead = "SELECT lead_id,list_id FROM vicidial_list WHERE vendor_lead_code='$RETAILERCODE' AND entry_date>='$date_only 00:00:00' AND entry_date<='$date_only 23:59:59'";
			$getLead_rslt = mysql_to_mysqli($get_lead,$link);
			$getLead_row = mysqli_fetch_row($getLead_rslt);
			$lead_idss = $getLead_row[0];
			$list_idss = $getLead_row[1];
			writeErroIntoLogFile('select dispose lead id : '.$lead_idss,$logDisposeCall);
			writeErroIntoLogFile('select dispose list_idss : '.$list_idss,$logDisposeCall);

			$get_lead1 = "SELECT vl.list_id,vla.lead_id FROM vicidial_live_agents vla left join  vicidial_list vl on vla.lead_id = vl.lead_id WHERE  vl.entry_date>='$date_only 00:00:00' AND vl.entry_date<='$date_only 23:59:59' and vla.user ='$user'";
			$getLead_rslt1 = mysql_to_mysqli($get_lead1,$link);
			$getLead_row1 = mysqli_fetch_row($getLead_rslt1);
			$list_idss1 = $getLead_row1[0];
			$list_lead_id = $getLead_row1[1];
			writeErroIntoLogFile('select dispose list_idsssssssss query: '.$get_lead1,$logDisposeCall);
			writeErroIntoLogFile('select dispose list_idsssssssss : '.$list_idss1,$logDisposeCall);
			writeErroIntoLogFile('select dispose list_lead_id : '.$list_lead_id,$logDisposeCall);

			$disable_callbk = "UPDATE vicidial_callbacks SET status='INACTIVE' WHERE lead_id='$lead_id' AND entry_time>='$date_only 00:00:00' AND entry_time<='$date_only 23:59:59'";
			$disable_callbk_rslt = mysql_to_mysqli($disable_callbk,$link);
			writeErroIntoLogFile('Disabled callback if available query: '.$disable_callbk,$logDisposeCall);
			writeErroIntoLogFile('Disabled callback if available result: '.$disable_callbk_rslt,$logDisposeCall);
			writeErroIntoLogFile('user status '.$row_user_status[0],$logDisposeCall);

            
			if($row_user_status[0]=='INCALL' && $is_continued==0){
				$stmt="UPDATE vicidial_live_agents set status='READY' where user='$user' and server_ip='$server_ip'";
				//$stmt="UPDATE vicidial_live_agents set status='READY' where user='$user' and server_ip='172.16.0.6'";
				writeErroIntoLogFile('stmt: '.$stmt,$logDisposeCall);
				$rslt=mysql_to_mysqli($stmt, $link);
				$affected_row = mysqli_affected_rows($link);
				if($affected_row==1){
					$calltype='';
							if(isset($list_idss1) && !empty($list_idss1)){
								 if($list_idss1 == "999"){
								 	$calltype='INBOUND';
								 }else{
								 	$calltype ='OUTBOUND';
								 }
							}
					writeErroIntoLogFile('calltype'.$calltype,$logDisposeCall);
					writeErroIntoLogFile('Successfully Dispoased Call! protocol:'.$_SERVER['SERVER_PROTOCOL'],$logDisposeCall);
					echo json_encode(array('success'=>true,'message'=>"Successfully Dispoased Call!",'calltype'=>$calltype));
				}
				else{
					writeErroIntoLogFile('Agent status not updated, Somthing went wrong '.$user,$logDisposeCall);
					echo json_encode(array('success'=>false,'failure_code'=>0,'message'=>'Somthing went wrong!'.$user));
				}
			}
			else{
				$stmt = "SELECT status FROM agent_current_status WHERE user='$user'";
				$rslt = mysql_to_mysqli($stmt,$link);
				$row_2 = mysqli_fetch_row($rslt);
				writeErroIntoLogFile('Agent status query'.$stmt,$logDisposeCall);
				writeErroIntoLogFile('Agent status '.$row_2[0],$logDisposeCall);
				// echo $stmt;die();
				if($row_2[0]!='Idle' && $is_continued==0){

					writeErroIntoLogFile('Agent id idle:',$logDisposeCall);
					$stmt="UPDATE vicidial_live_agents set status='READY' where user='$user' and server_ip='$server_ip'";
					$rslt=mysql_to_mysqli($stmt, $link);
                    writeErroIntoLogFile('UPDate vicidial_live_agents query '.$stmt,$logDisposeCall);
                    writeErroIntoLogFile('UPDate vicidial_live_agents result '.$rslt,$logDisposeCall);
					$affected_row = mysqli_affected_rows($link);
					// echo $stmt;die();
					writeErroIntoLogFile('UPDate vicidial_live_agents affected rows '.$affected_row,$logDisposeCall);
					if($affected_row==1){
						    $calltype='';
							if(isset($list_idss1) && !empty($list_idss1)){
								 if($list_idss1 == "999"){
								 	$calltype='INBOUND';
								 }else{
								 	$calltype ='OUTBOUND';
								 }
							}
					    writeErroIntoLogFile('calltype'.$calltype,$logDisposeCall);
                       	writeErroIntoLogFile('Successfully Dispoased Call! protocol:'.$_SERVER['SERVER_PROTOCOL'],$logDisposeCall);
                       
			 			echo json_encode(array('success'=>true,'message'=>"Successfully Dispoased Call!",'calltype'=>$calltype));
                       
			            
					}
					else{
						writeErroIntoLogFile('update vicidial_live_agents missing :',$logDisposeCall);
						writeErroIntoLogFile('Somthing went wrong '.$user,$logDisposeCall);
						echo json_encode(array('success'=>false,'failure_code'=>0,'message'=>'Somthing went wrong! '.$user));
						//echo json_encode(array('success'=>false,'failure_code'=>0,'message'=>'Somthing went wrong! '.$stmt.' user= '.$user));
					}
				}
				else{
					if($is_continued==1){
                        writeErroIntoLogFile('Please Take Next Order => '.$user,$logDisposeCall);
					    echo json_encode(array('success'=>true,'failure_code'=>0,'message'=>'Please Take Next Order => '.$user)); 
					}else{
						writeErroIntoLogFile('Agent is not oncall! '.$user,$logDisposeCall);
					    echo json_encode(array('success'=>false,'failure_code'=>0,'message'=>'Agent is not oncall! '.$user));
					}
					
				}
			}
}

// if(isset($_GET['setCallReShedule'])){
// 	writeErroIntoLogFile('****************************setCallReShedule API Initiated****************************',$log_file_name2);
// 	$header = apache_request_headers(); 
// 	// $token = $header['Authorization'];
// 	$token = "123";
// 	if($token==$token_generated){

// 	$json1 = file_get_contents('php://input');
// 	$json_array = json_decode($json1);
// 	$NOW_TIME 		= date("Y-m-d H:i:s");
// 	$date_only = date("Y-m-d",strtotime($NOW_TIME));
// 	$user = $json_array->user;
// 	$RETAILERCODE = $json_array->RETAILERCODE;
// 	$ORDERFLAG = $json_array->ORDERFLAG;
// 	$CallBackDatETimE = $json_array->callbackDateTime;
// 	writeErroIntoLogFile('Retailer code: '.$RETAILERCODE,$log_file_name2);
// 	writeErroIntoLogFile('Oder flag: '.$ORDERFLAG,$log_file_name2);
// 		if($user!='' && $CallBackDatETimE!=''){
// 			$order = "INSERT INTO orders (retailer_code,orderflage,api,user) VALUES ('$RETAILERCODE','$ORDERFLAG','CB','$user')";
// 			$order_rslt = mysql_to_mysqli($order,$link);

// 			$retailer_list_update = "UPDATE dms_retailers_data SET Optional3 = 'callback' WHERE retailer_code = '$RETAILERCODE' AND Optional1 = '$date_only'";
// 			$rslt_priority_update = mysql_to_mysqli($retailer_list_update,$link);

// 			$get_phone = "SELECT primary_mob_number FROM dms_retailers_data WHERE retailer_code='$RETAILERCODE' AND dated>='$date_only 00:00:00' AND dated<='$date_only 23:59:59'";
// 			$get_phone_rslt = mysql_to_mysqli($get_phone,$link);
// 			$row_phone = mysqli_fetch_row($get_phone_rslt);
// 			$phone_number = $row_phone[0];
// 			$stmt_list = "SELECT last_number,acs.status,list_id FROM agent_current_status as acs left join vicidial_list as vl on vl.phone_number=acs.last_number AND vl.list_id!='999' WHERE acs.user='$user' order by vl.lead_id DESC";
// 			$rslt_list = mysql_to_mysqli($stmt_list,$link);
// 			$row_list = mysqli_fetch_row($rslt_list);
// 			$phoneNo = $row_list[0];
// 			$status = $row_list[1];
// 			$list_id = $row_list[2];
// 			if($status=='ACW' || $status=='Oncall' || $status=='Consult'){
// 				// $get_user_info = "SELECT vu.user_group,vc.campaign_id FROM vicidial_users as vu left join vicidial_campaigns as vc on vc.user_group=vu.user_group WHERE vu.user='$user'";
// 				$get_user_info = "SELECT vu.user_group,vla.campaign_id FROM vicidial_users as vu left join vicidial_live_agents as vla on vla.user=vu.user WHERE vu.user='$user'";
// 				$rslt_user_info = mysql_to_mysqli($get_user_info,$link);
// 				$row_user_info = mysqli_fetch_row($rslt_user_info);
// 				$user_group = $row_user_info[0];
// 				$campaign = $row_user_info[1];

				

// 				// $stmtA = "INSERT INTO vicidial_list (entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,called_since_last_reset,phone_code,phone_number,security_phrase,called_count,gmt_offset_now,comments$entry_list_idSQLa$INSERTstateSQLa$INSERTpostal_codeSQLa$populate_provinceA) values('$NOW_TIME','$NOW_TIME','CALLBK','VDAD','NHAI3','VDCL','$list_id','Y','1','$phoneNo','$ingroup_insert','0','-5.00','$VLcomments'$entry_list_idSQLb$INSERTstateSQLb$INSERTpostal_codeSQLb$populate_provinceB);";

// 				// $stmtD = "UPDATE vicidial_list SET status='CALLBK',called_since_last_reset='Y' WHERE phone_number='$phone_number' AND vendor_lead_code = '$RETAILERCODE' AND entry_date>='$date_only 00:00:00' AND entry_date<='$date_only 23:59:59'";
// 				// $rsltD = mysql_to_mysqli($stmtD,$link);
				
// 				$stmtB = "SELECT lead_id FROM vicidial_list WHERE vendor_lead_code = '$RETAILERCODE' AND entry_date>='$date_only 00:00:00' AND entry_date<='$date_only 23:59:59'";
// 				$rsltB = mysql_to_mysqli($stmtB,$link);
// 				$lead_row = mysqli_fetch_row($rsltB);
// 				$lead_id = $lead_row[0];
// 				$stmt="INSERT INTO vicidial_callbacks (lead_id,list_id,campaign_id,status,entry_time,callback_time,user,recipient,comments,user_group,lead_status,customer_timezone,customer_timezone_diff,customer_time) values('$lead_id','$list_id','$campaign','ACTIVE','$NOW_TIME','$CallBackDatETimE','$user','ANYONE','','$user_group','CALLBK','$callback_timezone','$callback_gmt_offset','$NOW_TIME');";
// 				// if ($DB) {echo "$stmt\n";}
// 				$rslt=mysql_to_mysqli($stmt, $link);
				
// 				if($rslt){
					
// 					$stmt_called_count = "SELECT called_count FROM vicidial_list WHERE vendor_lead_code = '$RETAILERCODE' AND `entry_date` >= '$date_only 00:00:00' AND `entry_date` <= '$date_only 23:59:59'";
// 					$rslt_called_count = mysql_to_mysqli($stmt_called_count,$link);
// 					$row_called_count = mysqli_fetch_row($rslt_called_count);
// 					$called_count = $row_called_count[0];
// 					if($called_count == 0){
// 						$stmtA = "UPDATE vicidial_list SET IN_CALL='CB',called_since_last_reset='Y',status='IN_SALE' WHERE phone_number='$phone_number' AND entry_date>='$date_only 00:00:00' AND entry_date<='$date_only 23:59:59'";
// 					} else{
// 						$stmtA = "UPDATE vicidial_log SET callback='Y' WHERE phone_number='$phone_number' AND user='$user' AND called_count='$called_count' AND call_date>='$date_only 00:00:00' AND call_date<='$date_only 23:59:59'";

// 						$stmtD = "UPDATE vicidial_list SET status='IN_SALE',CB='Y' WHERE phone_number='$phone_number' AND vendor_lead_code = '$RETAILERCODE' AND entry_date>='$date_only 00:00:00' AND entry_date<='$date_only 23:59:59'";
// 						$rsltD = mysql_to_mysqli($stmtD,$link);
// 					}
// 					$rslt=mysql_to_mysqli($stmtA, $link);
// 					// echo dispoaseCall($user,$link,$stmtA);
// 					$stmt="SELECT extension,dialplan_number,voicemail_id,phone_ip,computer_ip,server_ip,login,pass,status,active,phone_type,fullname,company from phones where login='$user' and active = 'Y';";
// 					  $rslt=mysql_to_mysqli($stmt, $link);
// 					  $row=mysqli_fetch_row($rslt);
// 					  $server_ip=$row[5];
// 					  // echo $server_ip;
// 					  $get_user_status = "SELECT status FROM vicidial_live_agents WHERE user='$user' and server_ip='$server_ip'";
// 					  $rslt_user_status = mysql_to_mysqli($get_user_status,$link);
// 					  $row_user_status = mysqli_fetch_row($rslt_user_status);
// 					    if($row_user_status[0]=='INCALL'){
// 					    $stmt="UPDATE vicidial_live_agents set status='READY' where user='$user' and server_ip='$server_ip'";
// 					    $rslt=mysql_to_mysqli($stmt, $link);
// 					    writeErroIntoLogFile('UPDATE_STMT: '.$stmt,$log_file_name2);
// 					  }
// 					  else{
// 					    $stmt = "SELECT status FROM agent_current_status WHERE user='$user'";
// 					    $rslt = mysql_to_mysqli($stmt,$link);
// 					    $row = mysqli_fetch_row($rslt);
// 					    if($row[0]=='ACW'){
// 					      $stmt="UPDATE vicidial_live_agents set status='READY' where user='$user' and server_ip='$server_ip'";
// 					      $rslt=mysql_to_mysqli($stmt, $link);
// 					      writeErroIntoLogFile('Retailer code: '.$stmt,$log_file_name2);
// 					    }
// 					  }
					
// 					// $affected_rows = mysqli_affected_rows($link);
// 					  writeErroIntoLogFile('Successfully callback set!',$log_file_name2);
// 					echo json_encode(array('success'=>true,'message'=>'Successfully callback set!'));
// 				} 
// 				else{
// 					writeErroIntoLogFile('Somthing went wrong',$log_file_name2);
// 					echo json_encode(array('success'=>false,'message'=>'Somthing went wrong','user'=>$user));
// 				}
// 			} else{
// 				writeErroIntoLogFile('You cannot schedule callback after disposed!',$log_file_name2);
// 				echo json_encode(array('success'=>false,'failure_code'=>0,'message'=>'You cannot schedule callback after disposed!','user'=>$user));
// 			}
// 		} else{
// 			writeErroIntoLogFile('Incomplete information provided!',$log_file_name2);
// 			echo json_encode(array('success'=>false,'failure_code'=>0,'message'=>'Incomplete information provided!','user'=>$user));
// 		}
// 	} else{
// 		writeErroIntoLogFile('Invalid token!',$log_file_name2);
// 		echo json_encode(array('success'=>false,'failure_code'=>2,'message'=>'Invalid token!'));
// 	}
// }




if(isset($_GET['getNotConnectNumber'])){
	writeErroIntoLogFile('****************************getNotConnectNumber API Initiated****************************',$logNotConCall);
	$header = apache_request_headers(); 
	$token = $header['Authorization'];
	//$token = "123";
	if($token==$db_token){

	$json1 = file_get_contents('php://input');
	$json_array = json_decode($json1);
	$date = $json_array->date;
	$enddate = $date." 23:59:59";
	writeErroIntoLogFile('Date: '.$date,$logNotConCall);
		if($date!=''){
			 $data=array();
			 $query = "SELECT drd.retailer_code,vl.phone_number,vl.called_count,vl.modify_date FROM `vicidial_list` as vl JOIN `dms_retailers_data` as drd on drd.retailer_code=vl.vendor_lead_code WHERE vl.called_count ='0' and entry_date>='$date' AND entry_date<='$enddate' order by vl.called_count ASC"; // or FIND_IN_SET ($msidn2,extensions)";
			$result = mysql_to_mysqli($query, $link);	
			$num_rows 	= mysqli_num_rows($result);
			if($num_rows>0){
				$i=0;
		    	while($row =mysqli_fetch_assoc($result)){
                    $dataToPush=array();
                    $dataToPush['phone_number']=$row['phone_number'];
                    $dataToPush['modify_date']=$row['modify_date'];
                    $i++;
                    $data[]=$dataToPush;
                    writeErroIntoLogFile('Retailers Code => '.$row['retailer_code'],$logNotConCall);
		    	}

		    	writeErroIntoLogFile('Total Retailers Count => '.$i,$logNotConCall);
			    echo json_encode(array('success'=>true,'message'=>'Total Records Found! => '.$i,'data'=>$data));
		    	

			}else{
				writeErroIntoLogFile('Date is required',$logNotConCall);
			    echo json_encode(array('success'=>false,'failure_code'=>0,'message'=>'No Data Found!'));
			} 

		}
		else{
			writeErroIntoLogFile('Date is required',$logNotConCall);
			echo json_encode(array('success'=>false,'failure_code'=>0,'message'=>'Date is required!'));
		}
	} else{
		writeErroIntoLogFile('Invalid token!'.$user,$logNotConCall);
		echo json_encode(array('success'=>false,'failure_code'=>2,'message'=>'Invalid token!'));
	}
}

if(isset($_GET['uploadTodaysList'])){
    $json1 = file_get_contents('php://input');
	$json_array = json_decode($json1);
	writeErroIntoLogFile('Input Data'.$json1,$todayListUploadLog);
	$retailer_code = array();
	$retailer_err_message = array();
	$retailers_array = $json_array->data->element;
	$data_to_insert = count($retailers_array);
	//$list_id = $json_array->data->list_id;
	$date = $json_array->data->date;
	$campaign_id = $json_array->data->campaign_id;
	$success_count=0;	
	if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
		
        $tech_dial_list_id = $json_array->data->tech_dial_list_id;
		$stmt_check_date = "SELECT id FROM dms_list_id WHERE `date`='$date' ";
		// echo $stmt_check_date;
		writeErroIntoLogFile('Check dms_list_id => '.$stmt_check_date,$todayListUploadLog);
		$rslt_check_date = mysql_to_mysqli($stmt_check_date,$link);
		$num_row = mysqli_num_rows($rslt_check_date);
	    writeErroIntoLogFile('Check dms_list_id Count => '.$num_row,$todayListUploadLog);

	    if($num_row > 0){

            $tech_dial_list_id = mysqli_fetch_row($rslt_check_date);

            $inserted_id = $tech_dial_list_id[0];

            writeErroIntoLogFile('Already select dms_list_id tech_dial_list_id is => '.$inserted_id,$todayListUploadLog);

	    }else{

	    	writeErroIntoLogFile('No Duplicate entry => ',$todayListUploadLog);
			$stmt_add_list = "INSERT INTO `dms_list_id` (`list_id`,`date`,`campaign_id`) VALUES ('0','$date','$campaign_id')";
			writeErroIntoLogFile('Insert Into dms_list_id stmt_add_list query => '.$stmt_add_list,$todayListUploadLog);
			$rslt_add_list = mysql_to_mysqli($stmt_add_list,$link);
			$inserted_id = mysqli_insert_id($link);
			writeErroIntoLogFile('Generated dms_list_id tech_dial_list_id is => '.$inserted_id,$todayListUploadLog);

	    }

	}else{
		writeErroIntoLogFile('Incorrect date formate !',$todayListUploadLog);
		echo json_encode(array('success'=>false,'message'=>'Incorrect date formate !','failure_code'=>0));
	    return false;
	}



     
   	 for($i=0;$i<$data_to_insert;$i++){
   	 	 $d = new DateTime();
		 $milliseconds = udate('u'); // 19:40:56.78128
		 $NOW_TIME = $d->format("Y-m-d H:i:s"); // v : Milliseconds 
		 $NOW_TIME1 = $d->format("Y-m-d").$milliseconds; // v : Milliseconds 
		 $list_id = str_replace(":", "", $NOW_TIME1);
		 $list_id = str_replace("-", "", $list_id);
	   	 $list_id = str_replace(" ", "", $list_id);
        $title = 'Test';
		$first_name = $retailers_array[$i]->retailer_name;
		$middle_initial = '';

		$last_name = '';
		$address1 = '';
		$address2 = '';
		$address3 = '';
		$city = '';
		$state = '';
		$province = '';
		$postal_code = '';
		$country_code = '';
		$phone_number = $retailers_array[$i]->primary_mob_number;
		$alt_phone = $retailers_array[$i]->secondary_mob_number;
		$phone_code = '1';
		$email = '';
		$security = '';
		$comments = $retailers_array[$i]->from_time.' TO '.$retailers_array[$i]->to_time;
		$rank = '';
		$owner = $retailers_array[$i]->agent_id;
		$vendor_id = $retailers_array[$i]->retailer_code;
		$source_id = $retailers_array[$i]->retailer_code;
		$status = 'NEW';
		$date = date('Y-m-d');
		$list_name = "Dms Today Calling List";
		$active = "Y";

		### Grab Server GMT value from the database
		$stmt="SELECT local_gmt FROM servers where active='Y' limit 1;";
		$rslt=mysql_to_mysqli($stmt, $link);
		$gmt_recs = mysqli_num_rows($rslt);
		if ($gmt_recs > 0)
			{
			$row=mysqli_fetch_row($rslt);
			$DBSERVER_GMT		=		$row[0];
			if (strlen($DBSERVER_GMT)>0)	{$SERVER_GMT = $DBSERVER_GMT;}
			if ($isdst) {$SERVER_GMT++;} 
			}
		else
			{
			$SERVER_GMT = date("O");
			$SERVER_GMT = preg_replace("/\+/i","",$SERVER_GMT);
			$SERVER_GMT = ($SERVER_GMT + 0);
			$SERVER_GMT = ($SERVER_GMT / 100);
			}

		$LOCAL_GMT_OFF = $SERVER_GMT;
		$LOCAL_GMT_OFF_STD = $SERVER_GMT;

		$USarea = substr($phone_number, 0, 3);
		$USprefix = 	substr($phone_number, 3, 1);
		$postalgmt='';

		$gmt_offset = lookup_gmt($phone_code,$USarea,$state,$LOCAL_GMT_OFF_STD,$Shour,$Smin,$Ssec,$Smon,$Smday,$Syear,$postalgmt,$postal_code,$owner,$USprefix);
		$comments = preg_replace("/\n/",'!N',$comments);
		$comments = preg_replace("/\r/",'',$comments); 


		$flag=true;
		if(empty($retailers_array[$i]->agent_id)){
                $retailer_err_message['retailer_message'][]="Agent Id is Required ".$retailers_array[$i]->retailer_code;
		     	$flag =false;
		}
		if(empty($retailers_array[$i]->time_slot)){
			    $retailer_err_message['retailer_message'][]="Time Slot is Required ".$retailers_array[$i]->retailer_code;
		     	$flag =false;
		}
		if(empty($retailers_array[$i]->from_time)){
			    $retailer_err_message['retailer_message'][]="From Time is Required ".$retailers_array[$i]->retailer_code;
		     	$flag =false;
		}
		if(empty($retailers_array[$i]->to_time)){
			    $retailer_err_message['retailer_message'][]="To Time is Required ".$retailers_array[$i]->retailer_code;
		     	$flag =false;
		}
		if(empty($retailers_array[$i]->retailer_code)){
			    $retailer_err_message['retailer_message'][]="Retailer Code is Required ".$retailers_array[$i]->retailer_code;
		     	$flag =false;
		}
		if(empty($retailers_array[$i]->primary_mob_number)){
			    $retailer_err_message['retailer_message'][]="Primary Mobile Number is Required ".$retailers_array[$i]->retailer_code;
		     	$flag =false;
		}
		if($retailers_array[$i]->to_time == $retailers_array[$i]->from_time){
			    $retailer_err_message['retailer_message'][]="From Time Not Equal To Time ".$retailers_array[$i]->retailer_code;
		     	$flag =false;
		}
		if(empty($retailers_array[$i]->language1)){
			    $retailer_err_message['retailer_message'][]="Language 1 Is Required - ".$retailers_array[$i]->retailer_code;
		     	$flag =false;
		}
		
		if($flag!=true){
			//writeErroIntoLogFile('Empty Agent Id',$logPushData);
			writeErroIntoLogFile('Failed To Insert Into DMS Retailer Data for retailer_code => '.$retailers_array[$i]->retailer_code,$todayListUploadLog);
			$retailer_code[]['retailer_code'] = $retailers_array[$i]->retailer_code;
		}else{
			//$rslt = mysql_to_mysqli($stmt,$link);
  
			$checkAgentListIdexist = "SELECT list_id FROM vicidial_lists WHERE (campaign_id ='$owner' OR list_id='$owner' ) AND list_description='$date'";
			writeErroIntoLogFile('check agent list id=>  '.$owner,$todayListUploadLog);
			$checkAgentListResult = mysql_to_mysqli($checkAgentListIdexist,$link);
			$checkAgentnum_row = mysqli_num_rows($checkAgentListResult);
			writeErroIntoLogFile('check agent list id checkAgentnum_row=>  '.$checkAgentnum_row,$todayListUploadLog);
			if($checkAgentnum_row>0){
				$checkAgentRow = mysqli_fetch_row($checkAgentListResult);
				if(!empty($checkAgentRow)){
					$list_id = $checkAgentRow[0];
					writeErroIntoLogFile(' agent list id =>  '.$list_id,$todayListUploadLog);
				}

			}else{
				$insertNewAgent = "INSERT INTO vicidial_lists (list_id,list_name,campaign_id,active,list_description,list_changedate) values('$list_id','$list_name','$owner','$active','$date','$NOW_TIME')";
                writeErroIntoLogFile(' insertNewAgent =>  '.$insertNewAgent,$todayListUploadLog);

                $agentResult = mysql_to_mysqli($insertNewAgent,$link);
                writeErroIntoLogFile(' insertNewAgent agentResult =>  '.$agentResult,$todayListUploadLog);

			}

			writeErroIntoLogFile('Successfully Inserted Into DMS Retailer Data for retailer_code =>  '.$retailers_array[$i]->retailer_code,$todayListUploadLog);
			$stmt="INSERT INTO vicidial_list set status='" . mysqli_real_escape_string($link, $status) . "',title='" . mysqli_real_escape_string($link, $title) . "',first_name='" . mysqli_real_escape_string($link, $first_name) . "',middle_initial='" . mysqli_real_escape_string($link, $middle_initial) . "',last_name='" . mysqli_real_escape_string($link, $last_name) . "',address1='" . mysqli_real_escape_string($link, $address1) . "',address2='" . mysqli_real_escape_string($link, $address2) . "',address3='" . mysqli_real_escape_string($link, $address3) . "',city='" . mysqli_real_escape_string($link, $city) . "',state='" . mysqli_real_escape_string($link, $state) . "',province='" . mysqli_real_escape_string($link, $province) . "',postal_code='" . mysqli_real_escape_string($link, $postal_code) . "',country_code='" . mysqli_real_escape_string($link, $country_code) . "',alt_phone='" . mysqli_real_escape_string($link, $alt_phone) . "',phone_number='$phone_number',phone_code='$phone_code',email='" . mysqli_real_escape_string($link, $email) . "',security_phrase='" . mysqli_real_escape_string($link, $security) . "',comments='" . mysqli_real_escape_string($link, $comments) . "',rank='" . mysqli_real_escape_string($link, $rank) . "',owner='" . mysqli_real_escape_string($link, $owner) . "',vendor_lead_code='" . mysqli_real_escape_string($link, $vendor_id) . "'$source_idSQL, list_id='" . mysqli_real_escape_string($link, $list_id) . "',date_of_birth='" . mysqli_real_escape_string($link, $date_of_birth) . "',gmt_offset_now='$gmt_offset',entry_date='$NOW_TIME'"; 

				writeErroIntoLogFile('Insert query Into DMS Retailer Data => '.$stmt,$todayListUploadLog);
				$rslt = mysql_to_mysqli($stmt,$link);

				// echo "yes";echo $flag;die();
				if($rslt ==true){

					$stmt_1 = "INSERT INTO `dms_retailers_data` (`tech_dial_list_id`,`list_id`,`retailer_code`,`retailer_name`,`owner_name`,`primary_mob_number`,`secondary_mob_number`,`time_slot`,`from_time`,`to_time`,`day_of_calling`,`language1`,`langauge2`,`Optional1`,`Optional2`,`Optional4`,`agent_id`) VALUES ('$inserted_id','','".$retailers_array[$i]->retailer_code."','".$retailers_array[$i]->retailer_name."','".$retailers_array[$i]->owner_name."','".$retailers_array[$i]->primary_mob_number."','".$retailers_array[$i]->secondary_mob_number."','".$retailers_array[$i]->time_slot."','".$retailers_array[$i]->from_time."','".$retailers_array[$i]->to_time."','".$retailers_array[$i]->day_of_calling."','".$retailers_array[$i]->language1."','".$retailers_array[$i]->langauge2."','".$date."','".$retailers_array[$i]->priority."','".$retailers_array[$i]->state_id."','".$retailers_array[$i]->agent_id."')";


					$rslt_1 = mysql_to_mysqli($stmt_1,$link);
						
                    $success_count++;
					writeErroIntoLogFile('Successfully Inserted Into DMS Retailer Data for retailer_code =>  '.$retailers_array[$i]->retailer_code,$todayListUploadLog);
					
				}else{
					  writeErroIntoLogFile('Failed To Insert Into DMS Retailer Data for retailer_code => '.$retailers_array[$i]->retailer_code,$todayListUploadLog);
					  $retailer_code[]['retailer_code'] = $retailers_array[$i]->retailer_code;
				}
		}

		 



   	 }


   	 $failed_query_count = count($retailer_code);

		if($failed_query_count==$data_to_insert){
			echo $jsonResponse = json_encode(array('success'=>false,'message'=>'Complete failure Today list (Upload data again)','failure_message'=>$retailer_err_message,'failure_code'=>0,'success_count'=>$success_count));
			writeErroIntoLogFile('Complete failure Response (Upload data again) => '.$jsonResponse,$todayListUploadLog);
		}
		elseif($failed_query_count>0){
			echo $jsonResponse = json_encode(array('success'=>false,'message'=>'Partial Failure today list!','failure_code'=>1,'retailers_data'=>$retailer_code,'failure_message'=>$retailer_err_message,'tech_dial_list_id'=>$inserted_id,'failure_code'=>0,'success_count'=>$success_count));
			writeErroIntoLogFile('Partial Failure Response ! '.$jsonResponse,$todayListUploadLog);
		}
		else{
			echo $jsonResponse = json_encode(array('success'=>true,'message'=>'Data inserted successfully today list!','success_count'=>$success_count));
			writeErroIntoLogFile('Data inserted successfully! '.$jsonResponse,$todayListUploadLog);
		}
   	            


}


writeErroIntoLogFile('****************************submit and dispose file end ****************************',$logDisposeCall);

if(isset($_GET['getCampaign'])){
	
	writeErroIntoLogFile('*****API CALLED******',$state_api);

	if(isset($_GET) && !empty($_GET['mobile_number'])){
	   writeErroIntoLogFile(' GET MOBILE NO =>'.$_GET['mobile_number'],$state_api);
	   $phone_number = substr($_GET['mobile_number'], -10);
       $getRetailer = "SELECT Optional4 FROM dms_retailers_data WHERE primary_mob_number ='$phone_number' ORDER BY id DESC LIMIT 1";
       writeErroIntoLogFile('GET STATE QUERY =>'.$getRetailer,$state_api);
          

       if($result = mysql_to_mysqli($getRetailer,$link)){
       	  $row = mysqli_fetch_row($result);
       	  if(!empty($row[0])){
              $state_id = $row[0];
		   	  writeErroIntoLogFile('STATE ID =>'.$row[0],$state_api);
		   	  echo $state_id;
       	  }else{
              echo "BLENDED";
       	      writeErroIntoLogFile('STATE ID IS EMPTY ELSE => BLENDED',$state_api);
       	  }
       	  
       }else{
       	  echo "BLENDED";
       	  writeErroIntoLogFile('STATE ID => BLENDED',$state_api);
       } 
	}else{
        echo "Retailer Not Found";
        writeErroIntoLogFile('RETAILR NOT FOUND',$state_api);
	}
    
    writeErroIntoLogFile('*****API ENDED******',$state_api);
}

function writeErroIntoLogFile($msg,$log_file_name)
    {
    $date = date('d.m.Y G:i:s');
    $logText =  $date.'     |       '.$msg.PHP_EOL;
    error_log( $logText, 3, $log_file_name);
    }
function udate($format, $utimestamp = null)
{
    if (is_null($utimestamp))
        $utimestamp = microtime(true);

    $timestamp = floor($utimestamp);
    $milliseconds = round(($utimestamp - $timestamp) * 1000000);

    return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
}


?>
