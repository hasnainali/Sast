<?php 
require_once("dbconnect_mysqli.php");
require_once("functions.php");


if(isset($_POST['parent_id']) && $_POST['parent_id']!=''){
	$parent_id    = $_POST["parent_id"];	
	
	$stmt="SELECT clouser_types,ticket_fields from ticket_mapping where id = '$parent_id' AND map_type='1' ;";
	$rslt=mysql_to_mysqli($stmt, $link);
	$row = mysqli_fetch_row($rslt);
	if($row!=''||$row!=null){
		$clouser_types = $row[0];
		$ticketFields = $row[1];
	}
	else{
		$clouserData = '';
		$ticketFieldsData = '';
	}
	if(!empty($clouser_types)){
		$clouserstmt = "SELECT id,name from ticket_clouser where id IN ($clouser_types) and status = '1';";
		$clouserrslt=mysql_to_mysqli($clouserstmt, $link);
		while ($rows = mysqli_fetch_assoc($clouserrslt)){
			$clouserData[] = $rows;
		}
	}
	if(!empty($ticketFields)){
		$fieldstmt = "SELECT * from form_fields where id IN ($ticketFields) and status = '1';";
		$fieldrslt=mysql_to_mysqli($fieldstmt, $link);
		while ($fieldrows = mysqli_fetch_assoc($fieldrslt)){
			$ticketFieldsData[] = $fieldrows;
		}
	}
	// $stmt="SELECT id, ticket_name FROM  `ticket_parent` JOIN tickets_management ON FIND_IN_SET( id, ticket_type_id ) WHERE root_ticket_id =$parent_id GROUP BY id";	
	$stmt="SELECT tp.id,tm.ticket_name from ticket_mapping as tp left JOIN tickets_management as tm on tp.ticket_type_id=tm.id where tp.root_id = '$parent_id' AND tp.map_type='2' AND tm.status='1' AND tp.is_visible=1;";
	$rowdata = array();	
	$rslt=mysql_to_mysqli($stmt, $link);
	while ($rows = mysqli_fetch_assoc($rslt)){
		$rowdata[] = $rows;
	}
	// if(!empty($rowdata)){
		echo json_encode(array('success'=>1,'data'=>$rowdata,'clouser_types'=>$clouserData,'ticketFields'=>$ticketFieldsData));
		
	// }else{
	// 	echo json_encode(array('success'=>0,'data'=>'Sub child not found'));
	// }
	// print_r($rowdata);

}

if(isset($_POST['child_id']) && $_POST['child_id']!=''){
	$child_id    = $_POST["child_id"];		

	$stmt="SELECT clouser_types,ticket_fields from ticket_mapping where id = '$child_id' AND map_type='2' ;";
	$rslt=mysql_to_mysqli($stmt, $link);
	$row = mysqli_fetch_row($rslt);
	if($row!=''||$row!=null){
		$clouser_types = $row[0];
		$ticketFields = $row[1];
	}
	else{
		$clouserData = '';
		$ticketFieldsData = '';
	}

	if(!empty($clouser_types)){
		$clouserstmt = "SELECT id,name from ticket_clouser where id IN ($clouser_types) and status = '1';";
		$clouserrslt=mysql_to_mysqli($clouserstmt, $link);
		while ($rows = mysqli_fetch_assoc($clouserrslt)){
			$clouserData[] = $rows;
		}
	}
	if(!empty($ticketFields)){
		$fieldstmt = "SELECT * from form_fields where id IN ($ticketFields) and status = '1';";
		$fieldrslt=mysql_to_mysqli($fieldstmt, $link);
		while ($fieldrows = mysqli_fetch_assoc($fieldrslt)){
			$ticketFieldsData[] = $fieldrows;
		}
	}
	// $stmt="SELECT id, ticket_name FROM  `ticket_child` JOIN tickets_management ON FIND_IN_SET( id, ticket_type_id ) WHERE cat_id =$child_id GROUP BY id";	
	$stmt="SELECT tp.id,tm.ticket_name from ticket_mapping as tp left JOIN tickets_management as tm on tp.ticket_type_id=tm.id where tp.root_id = '$child_id' AND tp.map_type='3' AND tm.status='1' AND tp.is_visible=1;";	
	$rslt=mysql_to_mysqli($stmt, $link);
	$rowdata = array();
	while ($rows = mysqli_fetch_assoc($rslt)){
		$rowdata[] = $rows;
	}
	// if(!empty($rowdata)){
		echo json_encode(array('success'=>1,'data'=>$rowdata,'clouser_types'=>$clouserData,'ticketFields'=>$ticketFieldsData));
		
	// }else{
	// 	echo json_encode(array('success'=>0,'data'=>'Child not found'));
	// }
	// print_r($rowdata);

}

if(isset($_POST['selected_child_id']) && $_POST['selected_child_id']!=''){
	$selected_child_id = $_POST["selected_child_id"];		
	$stmt="SELECT clouser_types,ticket_fields from ticket_mapping where id = '$selected_child_id' ;";
	$rslt=mysql_to_mysqli($stmt, $link);
	$row = mysqli_fetch_row($rslt);
	$clouser_types = $row[0];
	$ticketFields = $row[1];
	if(!empty($clouser_types)){
		$clouserstmt = "SELECT id,name from ticket_clouser where id IN ($clouser_types) and status = '1';";
		$clouserrslt=mysql_to_mysqli($clouserstmt, $link);
		while ($rows = mysqli_fetch_assoc($clouserrslt)){
			$clouserData[] = $rows;
		}
	}
	if(!empty($ticketFields)){
		$fieldstmt = "SELECT * from form_fields where id IN ($ticketFields) and status = '1';";
		$fieldrslt=mysql_to_mysqli($fieldstmt, $link);
		while ($fieldrows = mysqli_fetch_assoc($fieldrslt)){
			$fieldsData[] = $fieldrows;
		}
	}
	if(!empty($clouserData) || !empty($fieldsData)){
		echo json_encode(array('success'=>1,'clouser_types'=>$clouserData,'ticketFields'=>$fieldsData));
		
	}else{
		echo json_encode(array('success'=>0,'data'=>'Fields not found'));
	}
	// print_r($rowdata);

}


?>