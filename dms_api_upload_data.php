<?php
require_once("dbconnect_mysqli.php");
require_once("functions.php");

$uploadDataLog = '/var/log/tekdial/dms_api_log/'.date('Y-m-d').'-uploadDataNewList.log';


if(isset($_GET['createNewList'])){
	writeErroIntoLogFile('*************createNewList API Initiated**************',$uploadDataLog);
	/**
	* update vicidial_lead_recycle to call new lead 1st
	*/
	// $stmtA = "UPDATE vicidial_lead_recycle SET active= 'N' , attempt_maximum = 1  WHERE campaign_id = 'DIALER'";
	// $stmtA = "UPDATE vicidial_lead_recycle SET active= 'N' , attempt_maximum = 1  WHERE campaign_id = 'DIALER' AND status != 'NEW'";
	// $rslt = mysql_to_mysqli($stmtA,$link);
$todaydate = date("Y-m-d");
$get_all_campaign = "SELECT * FROM `dms_list_id` WHERE `date`='$todaydate'";

writeErroIntoLogFile('Get All Campaing Query => '.$get_all_campaign,$uploadDataLog);

$result = mysql_to_mysqli($get_all_campaign, $link);
$cam_num_rows = mysqli_num_rows($result);
writeErroIntoLogFile('Get All Campaing Query Num Row => '.$cam_num_rows,$uploadDataLog);
$failed_count = 0;
if($cam_num_rows>0){
	while ($camp_rows = mysqli_fetch_assoc($result)) {
		
        $tech_dial_list_id = $camp_rows['id'];
       
        $dated = new DateTime();
		$yesterday = $dated->modify("-1 days")->format('Y-m-d');
		$yesterdayS = $yesterday." 00:00:00";
		$yesterdayE = $yesterday." 23:59:59";
		// $stmt_old_list = "UPDATE vicidial_list SET status= 'YLN' WHERE status = 'NEW' AND entry_date >= '$yesterdayS' AND entry_date <= '$yesterdayE'";
		// echo $stmt_old_list; die();
		// $rslt_old_list = mysql_to_mysqli($stmt_old_list,$link);


		// $NOW_TIME = date("Y-m-d H:i:s.v");
		// $list_id = str_replace(":", "", $NOW_TIME);
		// $list_id = str_replace("-", "", $list_id);
		// $list_id = str_replace(" ", "", $list_id);
		 $d = new DateTime();
		 $milliseconds = udate('u'); // 19:40:56.78128
		 $NOW_TIME = $d->format("Y-m-d H:i:s"); // v : Milliseconds 
		 $NOW_TIME1 = $d->format("Y-m-d").$milliseconds; // v : Milliseconds 
	//die();
		 $list_id = str_replace(":", "", $NOW_TIME1);
		 $list_id = str_replace("-", "", $list_id);
	   	 $list_id = str_replace(" ", "", $list_id);
// $list_id;
	   	$list_name = "Dms Todays Calling List";
		writeErroIntoLogFile('***LIST ID => '.$list_id,$uploadDataLog);
		// $campaign_id = "DIALER";
		$campaign_id = $camp_rows['campaign_id'];
		writeErroIntoLogFile('Selected Campaign Start**** => '.$campaign_id,$uploadDataLog);

		// $campaign_id = "SKF_DIAL";
		$active = "Y";
		$list_description = date("Y-m-d");
		

		### LOG INSERTION Admin Log Table ###
		$SQL_log = "$stmt|";
		$SQL_log = preg_replace('/;/', '', $SQL_log);
		$SQL_log = addslashes($SQL_log);
		$stmt="INSERT INTO vicidial_admin_log set event_date='$NOW_TIME', user='$PHP_AUTH_USER', ip_address='$ip', event_section='LISTS', event_type='ADD', record_id='$list_id', event_code='ADMIN ADD LIST', event_sql=\"$SQL_log\", event_notes='';";
		if ($DB) {echo "|$stmt|\n";}
		$rslt=mysql_to_mysqli($stmt, $link);
		writeErroIntoLogFile('Insert In vicidial_admin_log query => '.$stmt,$uploadDataLog);


		### BEGIN - Add a new lead in the system ###

		$secX = date("U");
		$hour = date("H");
		$min = date("i");
		$sec = date("s");
		$mon = date("m");
		$mday = date("d");
		$year = date("Y");
		$isdst = date("I");
		$Shour = date("H");
		$Smin = date("i");
		$Ssec = date("s");
		$Smon = date("m");
		$Smday = date("d");
		$Syear = date("Y");

		## Get retailer list of the day
		$status = 'NEW';
		$date = date('Y-m-d');
		$final_array = array();
		$stmt_count_9_6 = "SELECT * FROM dms_retailers_data WHERE Optional1 = '$date' AND  from_time = '09:00' AND to_time = '18:00' AND tech_dial_list_id ='$tech_dial_list_id'";

        writeErroIntoLogFile('select between 9 to 18  query=> '.$stmt_count_9_6,$uploadDataLog);

		// echo $stmt_count_9_6;
		// die();
		// $stmt_count_9_6 = "SELECT * FROM dms_retailers_data WHERE tech_dial_list_id = '104' AND  from_time = '09:00' AND to_time = '18:00'";
		$rslt_count_9_6 = mysql_to_mysqli($stmt_count_9_6,$link);
		$rslt_count_9_6_num_row = mysqli_num_rows($rslt_count_9_6);
        writeErroIntoLogFile('select between 9 to 18  query count => '.$rslt_count_9_6_num_row,$uploadDataLog);
        $row_array_9To6=array();
		while ($row_count_9_6 = mysqli_fetch_assoc($rslt_count_9_6)) {
			$row_array_9To6[] = $row_count_9_6;
		}

		$count_9_6 = mysqli_num_rows($rslt_count_9_6);
		$limit = ceil($count_9_6/4);
		$array_9To6 = array_chunk($row_array_9To6, $limit);

		$stmt_count_9_11 = "SELECT * FROM dms_retailers_data WHERE Optional1 = '$date' AND  from_time = '09:00' AND to_time = '11:00' AND tech_dial_list_id ='$tech_dial_list_id'";
		writeErroIntoLogFile('select between 9 to 11  query=> '.$stmt_count_9_11,$uploadDataLog);

		// $stmt_count_9_11 = "SELECT * FROM dms_retailers_data WHERE tech_dial_list_id = '104' AND  from_time = '09:00' AND to_time = '11:00'";
		$rslt_count_9_11 = mysql_to_mysqli($stmt_count_9_11,$link);
		$rslt_count_9_11_num_row = mysqli_num_rows($rslt_count_9_11);
        writeErroIntoLogFile('select between 9 to 11  query count => '.$rslt_count_9_11_num_row,$uploadDataLog);

		while ($row_count_9_11 = mysqli_fetch_assoc($rslt_count_9_11)) {
			$final_array[] = $row_count_9_11;
		}

		foreach ($array_9To6[0] as $key => $value) {
			array_push($final_array, $value);
		}

		$stmt_count_11_13 = "SELECT * FROM dms_retailers_data WHERE Optional1 = '$date' AND  from_time = '11:00' AND to_time = '13:00' AND tech_dial_list_id ='$tech_dial_list_id'";
	    writeErroIntoLogFile('select between 11 to 13  query=> '.$stmt_count_11_13,$uploadDataLog);

		// $stmt_count_11_13 = "SELECT * FROM dms_retailers_data WHERE tech_dial_list_id = '104' AND  from_time = '11:00' AND to_time = '13:00'";
		$rslt_count_11_13 = mysql_to_mysqli($stmt_count_11_13,$link);
		$rslt_count_11_13_num_row = mysqli_num_rows($rslt_count_11_13);
        writeErroIntoLogFile('select between 11 to 13 query count => '.$rslt_count_11_13_num_row,$uploadDataLog);

		while ($row_count_11_13 = mysqli_fetch_assoc($rslt_count_11_13)) {
			array_push($final_array, $row_count_11_13);
		}

		foreach ($array_9To6[1] as $key => $value) {
			array_push($final_array, $value);
		}

		$stmt_count_13_15 = "SELECT * FROM dms_retailers_data WHERE Optional1 = '$date' AND  from_time = '13:00' AND to_time = '15:00' AND tech_dial_list_id ='$tech_dial_list_id'";
        writeErroIntoLogFile('select between 13 to 15  query=> '.$stmt_count_13_15,$uploadDataLog);

		// $stmt_count_13_15 = "SELECT * FROM dms_retailers_data WHERE tech_dial_list_id = '104' AND  from_time = '13:00' AND to_time = '15:00'";
		$rslt_count_13_15 = mysql_to_mysqli($stmt_count_13_15,$link);
		$rslt_count_13_15_num_row = mysqli_num_rows($rslt_count_13_15);
        writeErroIntoLogFile('select between 13 to 15 query count => '.$rslt_count_13_15_num_row,$uploadDataLog);
		while ($row_count_13_15 = mysqli_fetch_assoc($rslt_count_13_15)) {
			array_push($final_array, $row_count_13_15);
		}

		foreach ($array_9To6[2] as $key => $value) {
			array_push($final_array, $value);
		}

		$stmt_count_15_18 = "SELECT * FROM dms_retailers_data WHERE Optional1 = '$date' AND  from_time = '15:00' AND to_time = '18:00' AND tech_dial_list_id ='$tech_dial_list_id'";
		writeErroIntoLogFile('select between 15 to 18  query=> '.$stmt_count_15_18,$uploadDataLog);

		// $stmt_count_15_18 = "SELECT * FROM dms_retailers_data WHERE tech_dial_list_id = '104' AND  from_time = '15:00' AND to_time = '18:00'";
		$rslt_count_15_18 = mysql_to_mysqli($stmt_count_15_18,$link);
		$rslt_count_15_18_num_row = mysqli_num_rows($rslt_count_15_18);
        writeErroIntoLogFile('select between 15 to 18 query count => '.$rslt_count_15_18_num_row,$uploadDataLog);
		while ($row_count_15_18 = mysqli_fetch_assoc($rslt_count_15_18)) {
			array_push($final_array, $row_count_15_18);
		}

		foreach ($array_9To6[3] as $key => $value) {
			array_push($final_array, $value);
		}

		$final_array = array_reverse($final_array);
		// print_r($final_array);die();
		// $stmt_retailer_list = "SELECT * FROM dms_retailers_data WHERE dated > '$date 00:00:00' AND dated < '$date 23:59:59' ORDER BY time_slot ASC";
		// $rslt_retailer_list = mysql_to_mysqli($stmt_retailer_list,$link);
		// $num_retailer_list = mysqli_num_rows($rslt_retailer_list);
		if(count($final_array)>0){
			$stmt_add_list="INSERT INTO vicidial_lists (list_id,list_name,campaign_id,active,list_description,list_changedate) values('$list_id','$list_name','$campaign_id','$active','$list_description','$NOW_TIME');";
			$rslt_add_list=mysql_to_mysqli($stmt_add_list, $link);
		    writeErroIntoLogFile('insert data in vicidial_lists query=> '.$stmt_add_list,$uploadDataLog);

		}
		//echo "<pre>";
		//print_r($final_array);
		//echo "endddd";
		$error = array();
		foreach ($final_array as $key => $value) {
		// while ($row_retailer_list = mysqli_fetch_assoc($rslt_retailer_list)) {
			$mob = $value['primary_mob_number'];
			if($mob=='0000' || $mob=='00000' || $mob=='000000' || $mob=='0000000' || $mob=='00000000' || $mob=='0000000000' || $mob==''){
				//$stmt = "INSERT INTO kloudq_invalid_numbers set mobile = '$mob'";
				//$rslt = mysql_to_mysqli($stmt,$link);
               // writeErroIntoLogFile('insert invalid number query=> '.$stmt,$uploadDataLog);
			}
			else{
				$title = 'Test';
				$first_name = $value['retailer_name'];
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
				$phone_number = $value['primary_mob_number'];
				$alt_phone = $value['secondary_mob_number'];
				$phone_code = '1';
				$email = '';
				$security = '';
				$comments = $value['from_time'].' TO '.$value['to_time'];
				$rank = '';
				$owner = $value['agent_id'];
				$vendor_id = $value['retailer_code'];
				$source_id = $value['retailer_code'];
				

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

				$list_valid=0;
				$stmt="SELECT count(*) from vicidial_lists where list_id='" . mysqli_real_escape_string($link, $list_id) . "' $LOGallowed_campaignsSQL;";
				$rslt=mysql_to_mysqli($stmt, $link);
                writeErroIntoLogFile('select count() vicidial_lists query=> '.$stmt_add_list,$uploadDataLog);
				$list_to_print = mysqli_num_rows($rslt);
				if ($list_to_print > 0) 
					{
					$rowx=mysqli_fetch_row($rslt);
					$list_valid = $rowx[0];
					}

				if ( ($list_valid > 0) or (preg_match('/\-ALL/i', $LOGallowed_campaigns)) )
					{
					$source_idSQL='';
					if ($SSsource_id_display > 0)
						{$source_idSQL = ",source_id='" . mysqli_real_escape_string($link, $source_id) . "'";}
					$stmt="INSERT INTO vicidial_list set status='" . mysqli_real_escape_string($link, $status) . "',title='" . mysqli_real_escape_string($link, $title) . "',first_name='" . mysqli_real_escape_string($link, $first_name) . "',middle_initial='" . mysqli_real_escape_string($link, $middle_initial) . "',last_name='" . mysqli_real_escape_string($link, $last_name) . "',address1='" . mysqli_real_escape_string($link, $address1) . "',address2='" . mysqli_real_escape_string($link, $address2) . "',address3='" . mysqli_real_escape_string($link, $address3) . "',city='" . mysqli_real_escape_string($link, $city) . "',state='" . mysqli_real_escape_string($link, $state) . "',province='" . mysqli_real_escape_string($link, $province) . "',postal_code='" . mysqli_real_escape_string($link, $postal_code) . "',country_code='" . mysqli_real_escape_string($link, $country_code) . "',alt_phone='" . mysqli_real_escape_string($link, $alt_phone) . "',phone_number='$phone_number',phone_code='$phone_code',email='" . mysqli_real_escape_string($link, $email) . "',security_phrase='" . mysqli_real_escape_string($link, $security) . "',comments='" . mysqli_real_escape_string($link, $comments) . "',rank='" . mysqli_real_escape_string($link, $rank) . "',owner='" . mysqli_real_escape_string($link, $owner) . "',vendor_lead_code='" . mysqli_real_escape_string($link, $vendor_id) . "'$source_idSQL, list_id='" . mysqli_real_escape_string($link, $list_id) . "',date_of_birth='" . mysqli_real_escape_string($link, $date_of_birth) . "',gmt_offset_now='$gmt_offset',entry_date='$NOW_TIME'";
					if ($DB) {echo "$stmt\n";}
					$rslt=mysql_to_mysqli($stmt, $link);
					writeErroIntoLogFile('insert into vicidial_list query=> '.$stmt,$uploadDataLog);

					$affected_rows = mysqli_affected_rows($link);
					if ($affected_rows > 0)
						{
						$lead_id = mysqli_insert_id($link);
						// echo _QXZ("Lead has been added").": $lead_id ($gmt_offset)<BR><BR>\n";
						$end_call=0;
						}
					else
						{
						// echo _QXZ("ERROR: Lead not added, please go back and look at what you entered")."<BR><BR>\n";}
							$error[] = "ERROR: Lead not added, please go back and look at what you entered";
							$failed_count++;
						// echo json_encode(array('success'=>false,'failure_code'=>0,'message'=>'Somthing went wrong!'));
					}
				}
				else
					{
					// echo _QXZ("you do not have permission to add this lead")." $list_id &nbsp; &nbsp; &nbsp; $NOW_TIME\n<BR><BR>\n";
						$error[] = "you do not have permission to add this lead";
						$failed_count++;
					// echo json_encode(array('success'=>false,'failure_code'=>0,'message'=>'Somthing went wrong!'));
					}
				}
		} 
		writeErroIntoLogFile('Selected Campaign End******** => '.$campaign_id,$uploadDataLog);

		}

}else{
	    echo json_encode(array('success'=>true,'message'=>"No Campaing Found"));
		writeErroIntoLogFile('No Campaign Found => ',$uploadDataLog);
		writeErroIntoLogFile('*************createNewList API End**************',$uploadDataLog);
		return false;
}

	
	if($failed_count==0){
		echo json_encode(array('success'=>true,'message'=>"All Leads added!"));
		writeErroIntoLogFile('All Leads Added Successfully => ',$uploadDataLog);
	}
	else{
		echo  json_encode(array('success'=>false,'failure_code'=>$failed_count,'message'=>$error));
      	writeErroIntoLogFile('Failed Upload Leads => '.$error,$uploadDataLog);

	}
	writeErroIntoLogFile('*************createNewList API End**************',$uploadDataLog);

	### END - Add a new lead in the system ###
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