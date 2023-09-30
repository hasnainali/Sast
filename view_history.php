<?php
require_once("dbconnect_mysqli.php");
require_once("functions.php");
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$agc_id = $_POST["agc_id"];


if($_POST['status']!=''){
    $ticket_status = $_POST['status'];
    $todayDate = $_POST['fromDate']." 00:00:00";
    $nextDate = $_POST['toDate']." 23:59:59";


$stmt = "SELECT ticket_id FROM `customer_tickets_details` WHERE agent = '$agc_id' AND creat_at >= '$todayDate' AND creat_at <= '$nextDate' GROUP BY ticket_id";
    $rslt = mysql_to_mysqli($stmt, $link);
    while ($row = mysqli_fetch_assoc($rslt)) {
        $ticket_id = $row['ticket_id'];
        $stmt1 = "SELECT id,ticket_status FROM `customer_tickets_details` WHERE agent = '$agc_id' AND creat_at >= '$todayDate' AND creat_at <= '$nextDate' AND ticket_id='$ticket_id' ORDER BY id DESC LIMIT 1";
        $rslt1 = mysql_to_mysqli($stmt1, $link);
        $row1 = mysqli_fetch_row($rslt1);
            if($row1[1] == $ticket_status){
                $show_tickets[] = $row1[0];
            }
    }
    $ids = join("','",$show_tickets);
    if($show_tickets!=null){
        $stmt = "SELECT td.ticket_id as id,td.ticket_status,td.ticket_data,td.creat_at,customer_id,tsm.name as ticket_status_name,vu.full_name,cd.mobile_number FROM customer_tickets_details as td LEFT JOIN ticket_status_manager as tsm ON td.ticket_status=tsm.id LEFT JOIN vicidial_users  as vu ON vu.user=td.agent LEFT JOIN customer as cd ON td.customer_id=cd.id WHERE td.id IN ('$ids')"; 
        $rslt = mysql_to_mysqli($stmt, $link);
        while($rows_ticket_status = mysqli_fetch_assoc($rslt)){
            if(!empty($rows_ticket_status['ticket_status_name'])){ 
                $json_data = preg_replace( "/\r|\n/", "", $rows_ticket_status['ticket_data']);    
                $ticket_data = json_decode($json_data);
                if(array_key_exists('child', $ticket_data)){
                    $child_id = $ticket_data->child;
                }
                elseif(array_key_exists('parent', $ticket_data)){
                    $child_id = $ticket_data->parent;
                }
                elseif(array_key_exists('tickettype', $ticket_data)){
                    $child_id = $ticket_data->tickettype;
                } 

                $stmt_tn = "SELECT tm.ticket_name FROM ticket_mapping as tp left join tickets_management as tm on tm.id=tp.ticket_type_id where tp.id='$child_id'";
                $rslt_tn = mysql_to_mysqli($stmt_tn,$link);
                $row = mysqli_fetch_row($rslt_tn);             

                $rows['id'] = $rows_ticket_status['id'];
                // $rows['creat_at'] = $rows_ticket_status['creat_at'];
                $date=date_create($rows_ticket_status['creat_at']);
                $rows['creat_at'] = date_format($date,"d-m-Y H:i:s"); 
                $rows['customer_id'] = $rows_ticket_status['customer_id'];
                $rows['ticket_status'] = $rows_ticket_status['ticket_status_name'];
                $rows['agent'] = $rows_ticket_status['full_name'];
                $rows['child_node'] = $row[0];
                $rows['action'] = "<a href='#' class='ticket-action' data-phone_no='".$rows_ticket_status['mobile_number']."' data-ticket_id='".$rows_ticket_status['id']."'>MORE..</a>";
                $rowdata[] = $rows;          
            }    
        }
    }
}
else{

    if(isset($_POST['ticket_type'])){
        $ticket_type = $_POST['ticket_type'];
    }
    else{
        $ticket_type = "";
    }

    $phone_number = $_POST["phone_number"];
    // $break_code= "ABCD";
    $start_time   = date("Y-m-d h:i:s");

    $get_cust  = "SELECT id FROM customer  where mobile_number =".$phone_number;
    $rslt_cust = mysql_to_mysqli($get_cust, $link);
    $rows_cust = mysqli_fetch_assoc($rslt_cust);
    $cust_id   = $rows_cust["id"];

    $closed_ticket = "SELECT GROUP_CONCAT( ticket_id ) as ticket_id FROM customer_tickets_details WHERE customer_id ='$cust_id' AND ticket_status =2";
    $ticket_closed = mysql_to_mysqli($closed_ticket, $link);
    $closed = mysqli_fetch_row($ticket_closed);
    $closed_tickets = 0;
    if($closed[0]!=''){ 
        $closed_tickets = $closed[0];
    }

    //Get open tickets

    if(!empty($ticket_type)){
       $get_ticket_last_action = "SELECT td.ticket_id as id,td.ticket_status,td.ticket_data,td.creat_at,customer_id,tsm.name as ticket_status_name,vu.full_name FROM customer_tickets_details as td LEFT JOIN ticket_status_manager as tsm ON td.ticket_status=tsm.id LEFT JOIN vicidial_users  as vu ON vu.user=td.agent WHERE customer_id ='$cust_id' AND `ticket_id`  NOT IN ($closed_tickets) and td.id in (select max(id) from customer_tickets_details where customer_id='$cust_id' group by ticket_id)  group by td.ticket_id";      
    }else{
        $get_ticket_last_action = "SELECT td.ticket_id as id,td.ticket_status,td.ticket_data,td.creat_at,customer_id,tsm.name as ticket_status_name,vu.full_name FROM customer_tickets_details as td LEFT JOIN ticket_status_manager as tsm ON td.ticket_status=tsm.id LEFT JOIN vicidial_users  as vu ON vu.user=td.agent WHERE customer_id ='$cust_id' and td.id in (select max(id) from customer_tickets_details where customer_id='$cust_id' group by ticket_id)";
    }
    $ticket_action=mysql_to_mysqli($get_ticket_last_action, $link);
    while($rows_ticket_status = mysqli_fetch_assoc($ticket_action)){
        if(!empty($rows_ticket_status['ticket_status_name'])){ 
            $json_data = preg_replace( "/\r|\n/", "", $rows_ticket_status['ticket_data']);  
            $ticket_data = json_decode($json_data);
            if(array_key_exists('child', $ticket_data)){
                $child_id = $ticket_data->child;
            }
            elseif(array_key_exists('parent', $ticket_data)){
                $child_id = $ticket_data->parent;
            }
            elseif(array_key_exists('tickettype', $ticket_data)){
                $child_id = $ticket_data->tickettype;
            } 

            $rows['child_id'] = $child_id;
            $stmt = "SELECT tm.ticket_name FROM ticket_mapping as tp left join tickets_management as tm on tm.id=tp.ticket_type_id where tp.id='$child_id'";
            $rslt = mysql_to_mysqli($stmt,$link);
            $row = mysqli_fetch_row($rslt);  
                
            $rows['id'] = $rows_ticket_status['id'];
            $date=date_create($rows_ticket_status['creat_at']);
            $rows['creat_at'] = date_format($date,"d-m-Y H:i:s"); 
            // $rows['creat_at'] = $rows_ticket_status['creat_at'];
            $rows['customer_id'] = $rows_ticket_status['customer_id'];
            $rows['ticket_status'] = $rows_ticket_status['ticket_status_name'];
            $rows['agent'] = $rows_ticket_status['full_name'];
            $rows['action'] = "<a href='#' class='ticket-action' data-phone_no='".$phone_number."' data-ticket_id='".$rows_ticket_status['id']."'>MORE..</a>";
            $rows['child_node'] = $row[0];
            $rowdata[] = $rows;          
        }    
    }
}
$array = array("status"=>1,"data"=>$rowdata,"stmt"=>$stmt);
echo json_encode($array);





// $get_cust = "SELECT id FROM customer  where mobile_number =".$phone_number;
// $rslt_cust=mysql_to_mysqli($get_cust, $link);
// $rows_cust = mysqli_fetch_assoc($rslt_cust);

// // if(!empty($rows_cust["id"])){
// $stmt="SELECT id ,creat_at,customer_id FROM customer_ticket  WHERE customer_id='".$rows_cust["id"]."' or id='".$phone_number."' ORDER BY id DESC";

// if ($DB) {echo "$stmt\n";}
// $rslt=mysql_to_mysqli($stmt, $link);
// $i=0;
//         while ($rows = mysqli_fetch_assoc($rslt)){       
//             //get status
//             if(!empty($ticket_type)){
//                 $get_ticket_last_action = "SELECT td.ticket_status,tsm.name as ticket_status_name,vu.full_name FROM customer_tickets_details as td LEFT JOIN ticket_status_manager as tsm ON td.ticket_status=tsm.id LEFT JOIN vicidial_users  as vu ON vu.user=td.agent   where td.ticket_id ='".$rows["id"]."' and ticket_status='$ticket_type'  ORDER BY td.id DESC LIMIT 1";                      
            
//                 $ticket_action=mysql_to_mysqli($get_ticket_last_action, $link);
//                 $rows_ticket_status = mysqli_fetch_assoc($ticket_action);                  
             
//                 if(!empty($rows_ticket_status['ticket_status_name'])){
//                     $rows['ticket_status'] = $rows_ticket_status['ticket_status_name'];
//                     $rows['agent'] = $rows_ticket_status['full_name'];
//                     $rows['action'] = "<a href='#' class='ticket-action' data-phone_no='".$phone_number."' data-ticket_id='".$rows['id']."'>MORE..</a>";
//                     $rowdata[] = $rows;          
//                 }
//             }
//             else{
//                 $get_ticket_last_action = "SELECT td.ticket_status,tsm.name as ticket_status_name,vu.full_name FROM customer_tickets_details as td LEFT JOIN ticket_status_manager as tsm ON td.ticket_status=tsm.id LEFT JOIN vicidial_users  as vu ON vu.user=td.agent   where td.ticket_id ='".$rows["id"]."'  ORDER BY td.id DESC LIMIT 1";
//                 $ticket_action=mysql_to_mysqli($get_ticket_last_action, $link);
//                 $rows_ticket_status = mysqli_fetch_assoc($ticket_action); 
//                 if(!empty($rows_ticket_status['ticket_status_name'])){
//                     $rows['ticket_status'] = $rows_ticket_status['ticket_status_name']; 
//                     $rows['agent'] = $rows_ticket_status['full_name'];
//                 }
//                 else{
//                     $rows['ticket_status'] = "";
//                 } 
//                 //$rows['agent'] = $rows_ticket_status['agent'];
//                 $rows['action'] = "<a href='#' class='ticket-action' data-phone_no='".$phone_number."' data-ticket_id='".$rows['id']."' >MORE..</a>";
//                 $rowdata[] = $rows; 
//             }
//             $i++;   
//         }        
//         $array = array("status"=>1,"data"=>$rowdata);
//         //print_r($array);die;       
//         echo json_encode($array);
//      // print_r($rowdata);
//      //print_r(json_encode($rowdata));
//     // }