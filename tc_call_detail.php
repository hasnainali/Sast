 <?php

 require_once("dbconnect_mysqli.php");
require_once("functions.php");
        $VD_login =$_POST['agent_id'];
        $data=array();
       if(isset($VD_login) && !empty($VD_login)){
        
                $today_date=date("Y-m-d");
                $totalLeadsCount=0;
		        $totalOrdersCount=0;
		        $totalCallBackCount=0;
		        $totalNoorderCount=0;
		        $totalNotConnectedCount=0;
		        $totalConversationPerc=0;
		        $averageSale=0;
		        $total_sale=0;
            $campaign ='';
           
                  $user_group_sql ="SELECT g.allowed_campaigns FROM `vicidial_users` u left join vicidial_user_groups g on g.user_group = u.user_group WHERE u.user='$VD_login'";
                  $user_group_result = mysql_to_mysqli($user_group_sql, $link); 
                  $user_group_num_rows  = mysqli_num_rows($user_group_result);
                  $final_arr=array();
                  if($user_group_num_rows>0){
                      $total_camp     = mysqli_fetch_row($user_group_result);
                      $total_camp_assign = $total_camp[0]; 

                      if($total_camp_assign=='BLENDED -'){
                            $campaign='BLENDED';
                      }elseif($total_camp_assign=='-ALL-CAMPAIGNS- -'){
                            $campaign=$total_camp_assign;

                            $totalLeadsQuery = "SELECT count(lead_id) FROM `vicidial_list` WHERE list_id in (SELECT list_id FROM `vicidial_lists` where list_id NOT IN (998,999) and list_description ='".date("Y-m-d")."') AND active='Y')";
                            $totalLeads_result = mysql_to_mysqli($totalLeadsQuery, $link); 
                            $totalLeads_num_rows  = mysqli_num_rows($totalLeads_result);
                            if($totalLeads_num_rows>0){
                                $totalLeadsrow     = mysqli_fetch_row($totalLeads_result);
                                $totalLeadsCount = $totalLeadsrow[0]; 
                            }

                      }else{
                            $campaigns=$total_camp_assign;
                            $campaigns = str_replace(" -","",$campaigns);
                            // $campaign = explode(" ",$campaign);
                            $groups = explode(" ", $campaigns);
                            
                            if(!empty($groups)){
                              foreach($groups as $gro){
                                if($gro !=""){
                                  $final_arr[] =$gro;
                                }
                                 
                              }    
                            }

                            if(!empty($final_arr)){
                                $campaign = implode("','",$final_arr);


                                $totalLeadsQuery = "SELECT count(lead_id) FROM `vicidial_list` WHERE list_id in (SELECT list_id FROM `vicidial_lists` where list_id NOT IN (998,999) and list_description ='".date("Y-m-d")."' AND active='Y' AND campaign_id IN ('$campaign'))";
                                $totalLeads_result = mysql_to_mysqli($totalLeadsQuery, $link); 
                                $totalLeads_num_rows  = mysqli_num_rows($totalLeads_result);
                                if($totalLeads_num_rows>0){
                                    $totalLeadsrow     = mysqli_fetch_row($totalLeads_result);
                                    $totalLeadsCount = $totalLeadsrow[0]; 
                                }
                            }
                      }
                  }
// SELECT lead_id,vendor_lead_code,list_id FROM `vicidial_list` WHERE list_id IN (SELECT list_id FROM `vicidial_lists` WHERE list_id in (SELECT list_id FROM `vicidial_lists` where list_id NOT IN (998,999) and list_description ='2022-10-14') )

                  /*$totalLeadsQuery = "SELECT count(lead_id) FROM `vicidial_list` WHERE user='$VD_login'  AND list_id NOT IN (998,999)";
                  $totalLeads_result = mysql_to_mysqli($totalLeadsQuery, $link); 
                  $totalLeads_num_rows  = mysqli_num_rows($totalLeads_result);
                  if($totalLeads_num_rows>0){
                      $totalLeadsrow     = mysqli_fetch_row($totalLeads_result);
                      $totalLeadsCount = $totalLeadsrow[0]; 
                  }*/
                  

                  $totalOrdersQuery = "SELECT count(id) FROM `orders` WHERE user='$VD_login' AND orderflage ='1'";
                  $totalOrders_result = mysql_to_mysqli($totalOrdersQuery, $link); 
                  $totalOrders_num_rows  = mysqli_num_rows($totalOrders_result);
                  if($totalOrders_num_rows>0){
                      $totalOrdersrow     = mysqli_fetch_row($totalOrders_result);
                      $totalOrdersCount = $totalOrdersrow[0]; 
                  }

                  $totalCallBackQuery = "SELECT count(id) FROM `orders` WHERE user='$VD_login' AND orderflage ='4'";
                  $totalCallBack_result = mysql_to_mysqli($totalCallBackQuery, $link); 
                  $totalCallBack_num_rows  = mysqli_num_rows($totalCallBack_result);
                  if($totalCallBack_num_rows>0){
                      $totalCallBackrow     = mysqli_fetch_row($totalCallBack_result);
                      $totalCallBackCount = $totalCallBackrow[0]; 
                  }

                  $totalNoOrderQuery = "SELECT count(id) FROM `orders` WHERE user='$VD_login' AND orderflage ='2'";
                  $totalNoorder_result = mysql_to_mysqli($totalNoOrderQuery, $link); 
                  $totalNoorder_num_rows  = mysqli_num_rows($totalNoorder_result);
                  if($totalNoorder_num_rows>0){
                      $totalNoorderrow     = mysqli_fetch_row($totalNoorder_result);
                      $totalNoorderCount = $totalNoorderrow[0]; 
                  }

                  $totalNotConnectedQuery = "SELECT count(lead_id) FROM `vicidial_list` WHERE user='$VD_login'  AND status ='NA' AND list_id NOT IN (998,999)";
                  $totalNotConnected_result = mysql_to_mysqli($totalNotConnectedQuery, $link); 
                  $totalNotConnected_num_rows  = mysqli_num_rows($totalNotConnected_result);
                  if($totalNotConnected_num_rows>0){
                      $totalNotConnectedrow     = mysqli_fetch_row($totalNotConnected_result);
                      $totalNotConnectedCount = $totalNotConnectedrow[0]; 
                  }
                  $data=array(
                       "agent_id"=>$VD_login,
                       "total_leads"=>$totalLeadsCount,
                       "total_orders"=>$totalOrdersCount,
                       "total_no_orders"=>$totalNoorderCount,
                       "total_callback"=>$totalCallBackCount,
                       "total_not_conn"=>$totalNotConnectedCount,
                       "camp_assign"=>$campaign,
                  );
                  $final_array=array(
                      'success'=>true,'message'=>'Data Found Successfully','data'=>$data
                  );
                  echo json_encode($final_array);


       }else{
       	  $final_array=array(
                      'success'=>false,'message'=>'Agent Id Is Empty','data'=>array()
                  ); 
       	  echo json_encode($final_array);
       }  

    ?> 



   