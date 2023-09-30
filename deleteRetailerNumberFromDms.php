<?php
  require_once("dbconnect_mysqli.php");
  require_once("functions.php");

  $deleteNumbersFromDms_log = '/var/log/tekdial/deleteNumbersFromDms/'.date('Y-m-d').'-deleteNumbersFromDms.log';
  $date_today = date("Y-m-d") ." 00:00:00";
  $date =date("Y-m-d");

  writeErroIntoLogFile('deleteNumbersFromDms_log api started',$deleteNumbersFromDms_log);

  $json1 = file_get_contents('php://input');
	$json_array = json_decode($json1);
	writeErroIntoLogFile('Input Data'.$json1,$deleteNumbersFromDms_log);
	// echo "yes";
	$success_count_vl = 0;
	$success_count_dms_retailer = 0;

	if(isset($json_array->data) && !empty($json_array->data)){

     foreach($json_array->data as $unique_code){
         
         writeErroIntoLogFile('unique_code => '.$unique_code,$deleteNumbersFromDms_log);

         $retailer_data_query ="DELETE FROM dms_retailers_data WHERE retailer_code = '$unique_code' AND Optional1 = '$date'";

         writeErroIntoLogFile('retailer_data_query  => '.$retailer_data_query,$deleteNumbersFromDms_log);

				 $retailer_data_result = mysql_to_mysqli($retailer_data_query,$link);

				 $affected_rows = mysqli_affected_rows($link);

				 writeErroIntoLogFile('retailer_data_query affected_rows  => '.$affected_rows,$deleteNumbersFromDms_log);

				 if($affected_rows > 0){
				 	  $success_count_dms_retailer ++;
            writeErroIntoLogFile('retailer_data_query result => '.$retailer_data_result,$deleteNumbersFromDms_log);
				 }else{
            writeErroIntoLogFile('unique_code not found in dms_retailer_data => '.$unique_code,$deleteNumbersFromDms_log);
				 }

				 $vl_query ="DELETE FROM vicidial_list WHERE vendor_lead_code = '$unique_code' AND entry_date >=  '$date_today'";

         writeErroIntoLogFile('vl_query  => '.$vl_query,$deleteNumbersFromDms_log);

				 $vl_query_result = mysql_to_mysqli($vl_query,$link);

				 $affected_rows1 = mysqli_affected_rows($link);

				 writeErroIntoLogFile('vl_query affected_rows1  => '.$affected_rows1,$deleteNumbersFromDms_log);

				 if($affected_rows1 > 0){
				 	  $success_count_vl ++;
            writeErroIntoLogFile('vl_query result => '.$vl_query_result,$deleteNumbersFromDms_log);
				 }else{
            writeErroIntoLogFile('unique_code not found in vicidial_list => '.$unique_code,$deleteNumbersFromDms_log);
				 }

				 $hopper_delete_query ="DELETE FROM vicidial_hopper WHERE vendor_lead_code = '$unique_code' ";

         writeErroIntoLogFile('hopper_delete_query  => '.$hopper_delete_query,$deleteNumbersFromDms_log);

         $hopper_delete_result = mysql_to_mysqli($hopper_delete_query,$link);

				 $affected_rows2 = mysqli_affected_rows($link);

				 writeErroIntoLogFile('hopper_delete_query affected_rows2  => '.$affected_rows2,$deleteNumbersFromDms_log);

				 if($affected_rows2 > 0){
				 	  $success_count_vl ++;
            writeErroIntoLogFile('hopper_delete_query result => '.$hopper_delete_result,$deleteNumbersFromDms_log);
				 }else{
            writeErroIntoLogFile('unique_code not found in hopper => '.$unique_code,$deleteNumbersFromDms_log);
				 }

     }

     $res = array('success'=>true,'message'=>'data deleted successfully','success_count_dms_retailer'=>$success_count_dms_retailer,'success_count_vl'=>$success_count_vl);

     echo json_encode($res);

	}else{

		   $res = array('success'=>false,'message'=>'unique_code is required','success_count_dms_retailer'=>$success_count_dms_retailer,'success_count_vl'=>$success_count_vl);

       echo json_encode($res);
       
		   writeErroIntoLogFile('empty unique_code',$deleteNumbersFromDms_log);
		   writeErroIntoLogFile('deleteNumbersFromDms_log api end',$deleteNumbersFromDms_log);

	}
  /*if(isset($_POST['retailer_code']) && !empty($_POST['retailer_code'])){
  	// print_r($_POST['retailer_code']);
  	$retailer_code = implode("','",$_POST['retailer_code']);

  	  $delete_vicidial_list ="DELETE FROM vicidial_list WHERE vendor_lead_code IN ('$retailer_code') AND entry_date >= '$date_today'";

						     $de_vici_result = mysqli_query($link,$delete_vicidial_list);
					       $affected_rows = mysqli_affected_rows($link);
						   
						     $delete_dms_retailers_data ="DELETE FROM dms_retailers_data WHERE retailer_code IN ('$retailer_code') AND Optional1 = '$date'";

						     $de_dms_retailers_data_result = mysqli_query($link,$delete_dms_retailers_data);
  	 echo $affected_rows ;
  }*/


  
  function writeErroIntoLogFile($msg,$log_file_name)
    {
    $date = date('d.m.Y G:i:s');
    $logText =  $date.'     |       '.$msg.PHP_EOL;
    error_log( $logText, 3, $log_file_name);
    }
?>