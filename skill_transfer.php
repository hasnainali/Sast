<?php 
require_once("functions.php");
require_once("dbconnect_mysqli.php");

if(isset($_POST['extension']) && $_POST['extension']!=''){
	$extension_no = $_POST['extension'];
	$stmt = "SELECT vu.user,va.status FROM skill_group as sg left join vicidial_users as vu on vu.user_skills_group=sg.skill_group_id left join vicidial_live_agents as va on va.user=vu.user WHERE group_extension='$extension_no'";
	$rslt = mysql_to_mysqli($stmt,$link);
	$available = 0;
	while ($row = mysqli_fetch_assoc($rslt)) {
		if($row['status']=='READY'){
			$available++;	
		}
	}
	// print_r($available);die();
	echo json_encode(array('success'=>1,'message'=>'Success','data'=>$available));
}
elseif(isset($_POST['inbound_group']) && $_POST['inbound_group']!=''){
	$inbound_group = $_POST['inbound_group'];
	$stmt = "SELECT va.status FROM vicidial_inbound_groups as vg left join vicidial_campaigns as vc on vg.group_name=vc.campaign_name left join vicidial_live_agents as va on va.campaign_id=vc.campaign_id WHERE va.status='READY'";
	$rslt = mysql_to_mysqli($stmt,$link);
	$row = mysqli_num_rows($rslt);
	// print_r($row);die();
	echo json_encode(array('success'=>1,'message'=>'Success','data'=>$row));
}
else{
	echo json_encode(array('success'=>0,'message'=>'Sorry! Something went wrong. Please try again','data'=>0));
}
?>