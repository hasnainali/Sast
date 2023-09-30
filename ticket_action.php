<?php
require_once("dbconnect_mysqli.php");
require_once("functions.php");
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//$agc_id = $_POST["agc_id"];
$ticket_id = $_POST["ticket_id"];
$phone_number = $_POST["phone_no"];
// $break_code= "ABCD";
//$start_time=date("Y-m-d h:i:s");
//$get_cust = "SELECT id FROM customer  where mobile_number =".$phone_number;
//$rslt_cust=mysql_to_mysqli($get_cust, $link);
//$rows_cust = mysqli_fetch_assoc($rslt_cust);

//if(!empty($rows_cust["id"])){
// $stmt="SELECT td.id ,td.ticket_status,td.ticket_data,td.creat_at,vu.full_name FROM customer_tickets_details as td LEFT JOIN vicidial_users  as vu ON vu.user=td.agent   WHERE td.ticket_id='".$ticket_id."' ORDER BY id DESC";
$stmt="SELECT cd.id,cd.ticket_id,cd.agent,cd.ticket_status,cd.ticket_data,cd.clouser_fields,cd.creat_at,cd.source FROM customer as c LEFT JOIN customer_tickets_details  as cd ON c.id=cd.customer_id WHERE cd.ticket_id='".$ticket_id."' ORDER BY cd.id DESC";

		if ($DB) {echo "$stmt\n";}
		$rslt=mysql_to_mysqli($stmt, $link);
		while ($rows = mysqli_fetch_assoc($rslt)){
			//get status
            $get_ticket_last_action = "SELECT id,name  FROM  ticket_status_manager where id='".$rows["ticket_status"]."'";
			$ticket_action=mysql_to_mysqli($get_ticket_last_action, $link);
			$rows_ticket_status = mysqli_fetch_assoc($ticket_action); 
           
            if(!empty($rows_ticket_status['name'])){
            $rows['ticket_status'] = $rows_ticket_status['name']; 
            			}
		    else{
            $rows['ticket_status'] = "";
		    }

            // $clouserfields = array(
            //     'Field1'=> 'Value1',
            //     'Field2'=> 'Value2',
            //     'Field3'=> 'Value3',
            //     'Field4'=> 'Value4'
            // );
            // $rows['clouserfields1'] = $clouserfields;
            $rows['clouserfields'] = json_decode($rows['clouser_fields']);
            // $rows['clouserfields_count'] = count($clouserfields);
             
            $get_agent_name = "SELECT user,full_name  FROM  vicidial_users where user='".$rows["agent"]."'";
            // print_r($get_agent_name);die();
            $agent_info=mysql_to_mysqli($get_agent_name, $link);
            $agent = mysqli_fetch_assoc($agent_info); 
             if(!empty($agent['full_name'])){       
             $rows['agent'] = $agent['user'].'('.$agent['full_name'].')';        
             }
             else{
             $rows['agent'] = "";    
             }
            //fetch ticket data
            $json_ticket = preg_replace( "/\r|\n/", "", $rows['ticket_data']);
            $ticket_data  = json_decode($json_ticket,true);
            if(!empty($ticket_data)){
            //fetch ticket type
            $get_ticket_type = "SELECT tm.id,tm.ticket_name FROM ticket_mapping as tp left join tickets_management as tm on tm.id=tp.ticket_type_id where tp.id=".$ticket_data['tickettype'];
			
			$get_ticket_type_res=mysql_to_mysqli($get_ticket_type, $link);
			$rows_ticket_type_result = mysqli_fetch_assoc($get_ticket_type_res); 
            if(!empty($rows_ticket_type_result['ticket_name'])){
                $rows['ticket_type'] = $rows_ticket_type_result['ticket_name']; 
			}
		    else{
                $rows['ticket_type'] = "";
		    } 
            //

            //fetch parent
            //fetch ticket type
            $get_ticket_parent = "SELECT tm.id,tm.ticket_name FROM ticket_mapping as tp left join tickets_management as tm on tm.id=tp.ticket_type_id where tp.id=".$ticket_data['parent'];
			
			$get_ticket_parent_res=mysql_to_mysqli($get_ticket_parent, $link);
			$rows_ticket_parent_result = mysqli_fetch_assoc($get_ticket_parent_res); 
            if(!empty($rows_ticket_parent_result['ticket_name'])){
                $rows['ticket_parent'] = $rows_ticket_parent_result['ticket_name']; 
			}
		    else{
                $rows['ticket_parent'] = "";
		    } 
           
            //fetch child
            //fetch ticket type
            $get_ticket_child = "SELECT tm.id,tm.ticket_name FROM ticket_mapping as tp left join tickets_management as tm on tm.id=tp.ticket_type_id where tp.id=".$ticket_data['child'];
			
			$get_ticket_child_res=mysql_to_mysqli($get_ticket_child, $link);
			$rows_ticket_child_result = mysqli_fetch_assoc($get_ticket_child_res); 
            if(!empty($rows_ticket_child_result['ticket_name'])){
                $rows['ticket_child'] = $rows_ticket_child_result['ticket_name']; 
			}
		    else{
                $rows['ticket_child'] = "";
		    } 

            //fetch closure
            $get_closure = "SELECT id,name FROM ticket_clouser where id=".$ticket_data['clousertype'];
			
			$get_closre_res=mysql_to_mysqli($get_closure, $link);
			$rows_closre_result = mysqli_fetch_assoc($get_closre_res); 
            if(!empty($rows_closre_result['name'])){
                $rows['ticket_closure'] = $rows_closre_result['name']; 
			}
		    else{
                $rows['ticket_closure'] = "";
		    } 

                $rows['seconds'] =(!empty($ticket_data['ticketCall_time']) ? $ticket_data['ticketCall_time'] :""  );
                $rows['comments'] =(!empty($ticket_data['comments']) ? $ticket_data['comments'] : "" );
                $rows['ticketAt'] = (!empty($ticket_data['ticketAt']) ? $ticket_data['ticketAt'] : "" );

                $acw_time = (!empty($ticket_data['acw_time']) ? $ticket_data['acw_time'] : "00:00:00" );
                $acw_time = strtotime($acw_time)-strtotime("00:00:00");
                $call_time = $acw_time+strtotime($rows['seconds']);
                // $rows['duration'] = date("H:i:s",$call_time);

            }
            else{
                $rows['ticket_type'] = "";
                $rows['ticket_parent'] = "";
                $rows['ticket_child'] = "";
                $rows['ticket_closure'] = "";
                $rows['seconds'] =""; 
                $rows['comments'] ="";
            }
            $date=date_create($rows['creat_at']);
            $rows['creat_at'] = date_format($date,"d-m-Y H:i:s"); 
            $date2=date_create($rows['ticketAt']);
            $rows['ticketAt'] = date_format($date2,"d-m-Y H:i:s"); 

            $time = $rows['creat_at'];
            $time2 = $rows['ticketAt'];
            $secs = strtotime($time2)-strtotime("00:00:00");
            $rows['duration'] = date("H:i:s",strtotime($time)-$secs);
             
            //$rows['action'] = "<a href='#' id='ticket-action' data-ticket_id='".$rows['id']."' >View Action</a>";

			$rowdata[] = $rows;
		    
		}
		/*echo "<pre>";
        print_r($rowdata);
        echo "</pre>";
		die;*/
         $array = array("status"=>1,"data"=>$rowdata);
		 echo json_encode($array);
		// print_r($rowdata);
		//print_r(json_encode($rowdata));
    //}