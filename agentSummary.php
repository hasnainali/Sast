
<script src="js/multiselect.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<link href="css/materialize.css" rel="stylesheet" />
<link href="css/styles_new.css" rel="stylesheet" />
<link href="css/styles.css" rel="stylesheet" />
<?php
require_once("dbconnect_mysqli.php");
require_once("functions.php");
            
            // $list_id = $_GET['list_id'];
            $campaign_id = $_GET['campaign_id'];
            $agent_id = $_GET['agent_id'];
            // $campaign_id ='';
            $get_today_list_id = "SELECT list_id FROM vicidial_lists WHERE campaign_id =$campaign_id AND list_description = '".date("Y-m-d")."'";
            $getList_result= mysql_to_mysqli($get_today_list_id, $link);
            $get_list_num_rows = mysqli_num_rows($getList_result);
            
            if($get_list_num_rows==0){
            	?>
                 <div class="container py-5">
                 	
                 	<div class="row py-5" style="box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
                 		<!-- <a href="https://vvrural.tekzee.in/tekdial/login.php" class="blue-btn-new">Back</a> -->
                 		<div style="    display: block;
    margin: 0 auto;    display: block;
    margin: 0 auto;
    color: black;
    font-size: 20px;
    font-family: sans-serif;
    /* font-style: italic; */
    font-weight: 800;">No Data Found</div>
                 	</div>
                 </div>
            	<?php
            	 die();
            }else{
              $get_list_row=mysqli_fetch_row($getList_result);
			        $list_id = $get_list_row[0];
            }
            // die();
			echo "<TABLE width=80% style='width: 100%;'><TR ><TD>\n";
			echo "<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2>";
            
			if ($SSexpired_lists_inactive > 0)
			{
				$expired_check=0;
				$stmt="SELECT count(*) from vicidial_lists where list_id='$list_id' and expiration_date < \"$REPORTdate\";";
				$rslt=mysql_to_mysqli($stmt, $link);
				$Cexp_to_print = mysqli_num_rows($rslt);
				if ($Cexp_to_print > 0) 
				{
					$rowx=mysqli_fetch_row($rslt);
					$expired_check = "$rowx[0]";
				}
				if ($DB) {echo "$expired_check|$stmt|\n";}
				if ($expired_check > 0)
				{
					$stmt = "UPDATE vicidial_lists SET active='N' where list_id='$list_id';";
					$rslt=mysql_to_mysqli($stmt, $link);
					$ELaffected_rows = mysqli_affected_rows($link);
					if ($DB) {echo "|$stmt|\n";}

					### LOG INSERTION Admin Log Table ###
					$SQL_log = "$stmt|";
					$SQL_log = preg_replace('/;/', '', $SQL_log);
					$SQL_log = addslashes($SQL_log);
					$stmt="INSERT INTO vicidial_admin_log set event_date='$SQLdate', user='$PHP_AUTH_USER', ip_address='$ip', event_section='LISTS', event_type='MODIFY', record_id='$list_id', event_code='ADMIN AUTO EXPIRE LIST', event_sql=\"$SQL_log\", event_notes='LIST: $list_id CHANGED: $ELaffected_rows';";
					if ($DB) {echo "|$stmt|\n";}
					$rslt=mysql_to_mysqli($stmt, $link);

					echo "<br><b>"._QXZ("NOTICE: expired list set to inactive")."\n";
				}
			}

			$stmt="SELECT vicidial_lists.list_id,list_name,campaign_id,active,list_description,list_changedate,list_lastcalldate,reset_time,agent_script_override,campaign_cid_override,am_message_exten_override,drop_inbound_group_override,xferconf_a_number,xferconf_b_number,xferconf_c_number,xferconf_d_number,xferconf_e_number,web_form_address,web_form_address_two,time_zone_setting,inventory_report,IFNULL(audit_comments,0),expiration_date,DATE_FORMAT(expiration_date,'%Y%m%d'),na_call_url,local_call_time,web_form_address_three,status_group_id,user_new_lead_limit,inbound_list_script_override,default_xfer_group,daily_reset_limit,resets_today,auto_active_list_rank from vicidial_lists left outer join vicidial_lists_custom on vicidial_lists.list_id=vicidial_lists_custom.list_id where vicidial_lists.list_id='$list_id' $LOGallowed_campaignsSQL;";

			$rslt=mysql_to_mysqli($stmt, $link);
			if ($DB) {echo "$stmt\n";}
			$row=mysqli_fetch_row($rslt);
			$list_name =				$row[1];
			$campaign_id =				$row[2];
			$active =					$row[3];
			$list_description =			$row[4];
			$list_changedate =			$row[5];
			$list_lastcalldate =		$row[6];
			$reset_time =				$row[7];
			$agent_script_override =	$row[8];
			$campaign_cid_override =	$row[9];
			$am_message_exten_override =	$row[10];
			$drop_inbound_group_override =	$row[11];
			$xferconf_a_number =		$row[12];
			$xferconf_b_number =		$row[13];
			$xferconf_c_number =		$row[14];
			$xferconf_d_number =		$row[15];
			$xferconf_e_number =		$row[16];
			$web_form_address =			$row[17];
			$web_form_address_two =		$row[18];
			$time_zone_setting =		$row[19];
			$inventory_report =			$row[20];
			$audit_comments = 			$row[21];
			$expiration_date =			$row[22];
			$expiration_dateINT =		$row[23];
			$na_call_url =				$row[24];
			$list_local_call_time =		$row[25];
			$web_form_address_three =	$row[26];
			$status_group_id =			$row[27];
			$user_new_lead_limit =		$row[28];
			$inbound_list_script_override =		$row[29];
			$default_xfer_group =		$row[30];
			$daily_reset_limit =		$row[31];
			$resets_today =				$row[32];
			$auto_active_list_rank =	$row[33];

			# grab names of global statuses and statuses in the selected campaign
			$stmt="SELECT status,status_name,selectable,human_answered,category,sale,dnc,customer_contact,not_interested,unworkable,scheduled_callback,completed,min_sec,max_sec,answering_machine from vicidial_statuses order by status;";
			$rslt=mysql_to_mysqli($stmt, $link);
			$statuses_to_print = mysqli_num_rows($rslt);

			$o=0;
			while ($statuses_to_print > $o) 
			{
				$rowx=mysqli_fetch_row($rslt);
				$statuses_list["$rowx[0]"] = "$rowx[1]";
				$statuses_complete_list["$rowx[0]"] = "$rowx[11]";
				$o++;
			}

			$stmt="SELECT status,status_name,selectable,campaign_id,human_answered,category,sale,dnc,customer_contact,not_interested,unworkable,scheduled_callback,completed,min_sec,max_sec,answering_machine from vicidial_campaign_statuses where campaign_id='$campaign_id' $LOGallowed_campaignsSQL order by status;";
			$rslt=mysql_to_mysqli($stmt, $link);
			$Cstatuses_to_print = mysqli_num_rows($rslt);

			$o=0;
			while ($Cstatuses_to_print > $o) 
			{
				$rowx=mysqli_fetch_row($rslt);
				$statuses_list["$rowx[0]"] = "$rowx[1]";
				$statuses_complete_list["$rowx[0]"] = "$rowx[12]";
				$o++;
			}
			# end grab status names

			##### get scripts listings for pulldown
			$Lscripts_list = "<option value=\"\">"._QXZ("NONE - INACTIVE")."</option>\n";
			$stmt="SELECT script_id,script_name from vicidial_scripts $whereLOGadmin_viewable_groupsSQL order by script_id;";
			$rslt=mysql_to_mysqli($stmt, $link);
			$scripts_to_print = mysqli_num_rows($rslt);
			$o=0;
			while ($scripts_to_print > $o)
			{
				$rowx=mysqli_fetch_row($rslt);
				$Lscripts_list .= "<option value=\"$rowx[0]\">$rowx[0] - $rowx[1]</option>\n";
				$scriptname_list["$rowx[0]"] = "$rowx[1]";
				$o++;
			}

			##### get in-groups listings for dynamic drop in-group pulldown
			$stmt="SELECT group_id,group_name from vicidial_inbound_groups $whereLOGadmin_viewable_groupsSQL order by group_id;";
			$rslt=mysql_to_mysqli($stmt, $link);
			$Dgroups_to_print = mysqli_num_rows($rslt);
			$Dgroups_menu='';
			$Dgroups_selected=0;
			$o=0;
			while ($Dgroups_to_print > $o) 
			{
				$rowx=mysqli_fetch_row($rslt);
				$Dgroups_menu .= "<option ";
				if ($drop_inbound_group_override == "$rowx[0]") 
				{
					$Dgroups_menu .= "SELECTED ";
					$Dgroups_selected++;
				}
				$Dgroups_menu .= "value=\"$rowx[0]\">$rowx[0] - $rowx[1]</option>\n";
				$o++;
			}
			if ($Dgroups_selected < 1) 
				{$Dgroups_menu .= "<option SELECTED value=\"\">---"._QXZ("NONE")."---</option>\n";}
			else 
				{$Dgroups_menu .= "<option value=\"\">---"._QXZ("NONE")."---</option>\n";}



			$stmt="SELECT campaign_id,campaign_name from vicidial_campaigns $whereLOGallowed_campaignsSQL order by campaign_id;";
			$rslt=mysql_to_mysqli($stmt, $link);
			$campaigns_to_print = mysqli_num_rows($rslt);
			$campaigns_list='';
			$camp_list='|';
			$o=0;
			while ($campaigns_to_print > $o) 
			{
				$rowx=mysqli_fetch_row($rslt);
				$campaigns_list .= "<option value=\"$rowx[0]\">$rowx[0] - $rowx[1]</option>\n";
				$camp_list .= "$rowx[0]|";
				$o++;
			}

			$DID_edit_link_BEGIN='';
			$DID_edit_link_END='';
			if (strlen($campaign_cid_override) > 0)
			{
				$stmt="SELECT did_id from vicidial_inbound_dids where did_pattern='$campaign_cid_override' $LOGadmin_viewable_groupsSQL limit 1;";
				$rslt=mysql_to_mysqli($stmt, $link);
				$dids_to_print = mysqli_num_rows($rslt);
				if ($dids_to_print > 0) 
				{
					$rowx=mysqli_fetch_row($rslt);
					$DID_edit_link_BEGIN = "<a href=\"$PHP_SELF?ADD=3311&did_id=$rowx[0]\">";
					$DID_edit_link_END='</a>';
				}
			}

			##### get status group listings for dynamic pulldown menu
			$stmt="SELECT status_group_id,status_group_notes from vicidial_status_groups $whereLOGadmin_viewable_groupsSQL order by status_group_id;";
			$rslt=mysql_to_mysqli($stmt, $link);
			$status_groups_to_print = mysqli_num_rows($rslt);
			$status_groups_menu='';
			$status_groups_selected=0;
			$o=0;
			while ($status_groups_to_print > $o) 
			{
				$rowx=mysqli_fetch_row($rslt);
				$status_groups_menu .= "<option ";
				if ($status_group_id == "$rowx[0]") 
				{
					$status_groups_menu .= "SELECTED ";
					$status_groups_selected++;
				}
				$status_groups_menu .= "value=\"$rowx[0]\">$rowx[0] - $rowx[1]</option>\n";
				$o++;
			}
			$sglinkB='';   $sglinkE='';
			if (strlen($status_group_id)>1)
			{
				$sglinkB="<a href=\"$PHP_SELF?ADD=393111111111&status_group_id=$status_group_id\">";
				$sglinkE='</a>';
			}

			##### get in-groups listings for dynamic transfer group pulldown list menu
			$xfer_groupsSQL='';
			$stmt="SELECT closer_campaigns,xfer_groups from vicidial_campaigns where campaign_id='$campaign_id' $LOGallowed_campaignsSQL;";
			$rslt=mysql_to_mysqli($stmt, $link);
			$row=mysqli_fetch_row($rslt);
			$closer_campaigns =	$row[0];
			$closer_campaigns = preg_replace("/ -$/","",$closer_campaigns);
			$groups = explode(" ", $closer_campaigns);
			$xfer_groups =	$row[1];
			$xfer_groups = preg_replace("/ -$/","",$xfer_groups);
			$XFERgroups = explode(" ", $xfer_groups);
			$xfer_groupsSQL = preg_replace("/^ | -$/","",$xfer_groups);
			$xfer_groupsSQL = preg_replace("/ /","','",$xfer_groupsSQL);
			$xfer_groupsSQL = "WHERE group_id IN('$xfer_groupsSQL')";

			$nxLOGadmin_viewable_groupsSQL = $LOGadmin_viewable_groupsSQL;
			if (strlen($xfer_groupsSQL) < 6)
				{$nxLOGadmin_viewable_groupsSQL = $whereLOGadmin_viewable_groupsSQL;}

			$stmt="SELECT group_id,group_name from vicidial_inbound_groups $xfer_groupsSQL $nxLOGadmin_viewable_groupsSQL order by group_id;";
			$rslt=mysql_to_mysqli($stmt, $link);
			$Xgroups_to_print = mysqli_num_rows($rslt);
			$Xgroups_menu='';
			$Xgroups_selected=0;
			$o=0;
			while ($Xgroups_to_print > $o) 
			{
				$rowx=mysqli_fetch_row($rslt);
				$Xgroups_menu .= "<option ";
				if ($default_xfer_group == "$rowx[0]") 
				{
					$Xgroups_menu .= "SELECTED ";
					$Xgroups_selected++;
				}
				$Xgroups_menu .= "value=\"$rowx[0]\">$rowx[0] - $rowx[1]</option>\n";
				$o++;
			}
			if ($Xgroups_selected < 1) 
				{$Xgroups_menu .= "<option SELECTED value=\"---NONE---\">---"._QXZ("NONE")."---</option>\n";}
			else 
				{$Xgroups_menu .= "<option value=\"---NONE---\">---"._QXZ("NONE")."---</option>\n";}


			echo "<center>\n";
			echo "<br><b style='font-size:15px;'>"._QXZ("OWNERS WITHIN THIS LIST").":</b><br>\n";
			if ($SSuser_territories_active > 0)
			{
				### if territories are active in the system then allow for selected-territory called-since-last-reset resetting
				echo "</form>\n";
				echo "<form action=$PHP_SELF method=POST>\n";
				echo "<input type=hidden name=ADD value=411>\n";
				echo "<input type=hidden name=DB value=$DB>\n";
				echo "<input type=hidden name=stage value=territory_reset>\n";
				echo "<input type=hidden name=list_id value=\"$list_id\">\n";
				echo "<TABLE width=600 cellspacing=3>\n";
				echo "<tr class=table-bg-new >"._QXZ("OWNER")."</td><td>"._QXZ("CALLED")."</td><td>"._QXZ("NOT CALLED")."</td><td align=center>"._QXZ("RESET")."</td></tr>\n";

				$leads_in_list = 0;
				$leads_in_list_N = 0;
				$leads_in_list_Y = 0;
				$stmt="SELECT owner,called_since_last_reset,count(*) from vicidial_list where list_id='$list_id' group by owner,called_since_last_reset order by owner,called_since_last_reset;";
				if ($DB) {echo "$stmt\n";}
				$rslt=mysql_to_mysqli($stmt, $link);
				$owners_to_print = mysqli_num_rows($rslt);

				$o=0;
				$lead_list['count'] = 0;
				$lead_list['Y_count'] = 0;
				$lead_list['N_count'] = 0;
				while ($owners_to_print > $o) 
				{
					$rowx=mysqli_fetch_row($rslt);
					
					$lead_list['count'] = ($lead_list['count'] + $rowx[2]);
					if ($rowx[1] == 'N') 
					{
						$since_reset = 'N';
						$since_resetX = 'Y';
					}
					else 
					{
						$since_reset = 'Y';
						$since_resetX = 'N';
					} 
					$lead_list[$since_reset][$rowx[0]] = ($lead_list[$since_reset][$rowx[0]] + $rowx[2]);
					$lead_list[$since_reset.'_count'] = ($lead_list[$since_reset.'_count'] + $rowx[2]);
					#If opposite side is not set, it may not in the future so give it a value of zero
					if (!isset($lead_list[$since_resetX][$rowx[0]])) 
					{
						$lead_list[$since_resetX][$rowx[0]]=0;
					}
					$o++;
				}

				$o=0;
				if ($lead_list['count'] > 0)
				{
					while (list($owner,) = each($lead_list[$since_reset]))
					{
						if (preg_match('/1$|3$|5$|7$|9$/i', $o))
							{$bgcolor='bgcolor="#fff"';} 
						else
							{$bgcolor='bgcolor="#fff"';}

						$CLB='';
						$CLE='';

						echo "<tr class=table-bg-new><td><font size=1><a href=\"./user_territories.php?action=MODIFY_TERRITORY&territory=$owner\">$CLB$owner$CLE</a></td><td><font size=1>".$lead_list['Y'][$owner]."</td><td><font size=1>".$lead_list['N'][$owner]." </td><td align=center><input type=\"checkbox\" name=\"territory_reset[]\" value=\"$owner\"></td></tr>\n";
						$o++;
					}
				}

				echo "<tr class=table-td-bg-new><td><font size=1>"._QXZ("SUBTOTALS")."</td><td><font size=1>$lead_list[Y_count]</td><td><font size=1>$lead_list[N_count]</td>\n";
				echo "<td class=table-td-bg-new rowspan=2 align=center><input type=submit value=\""._QXZ("SUBMIT")."\"></td></tr>\n";
				echo "<tr class=table-td-bg-new><td><font size=1>"._QXZ("TOTAL")."</td><td colspan=3 align=center><font size=1>$lead_list[count]</td></tr>\n";
				echo "</form>\n";

			}
			else
			{
				echo "<TABLE width=800 cellspacing=3 class='called-counts-table'>\n";
				echo "<tr class=table-bg-new><td>"._QXZ("OWNER")."</td><td>"._QXZ("CALLED")."</td><td>"._QXZ("NOT CALLED")."</td></tr>\n";

				$leads_in_list = 0;
				$leads_in_list_N = 0;
				$leads_in_list_Y = 0;
				$stmt="SELECT owner,called_since_last_reset,count(*) from vicidial_list where list_id='$list_id' group by owner,called_since_last_reset order by owner,called_since_last_reset;";
				if ($DB) {echo "$stmt\n";}
				$rslt=mysql_to_mysqli($stmt, $link);
				$owners_to_print = mysqli_num_rows($rslt);

				$o=0;
				$lead_list['count'] = 0;
				$lead_list['Y_count'] = 0;
				$lead_list['N_count'] = 0;
				while ($owners_to_print > $o) 
				{
					$rowx=mysqli_fetch_row($rslt);
					
					$lead_list['count'] = ($lead_list['count'] + $rowx[2]);
					if ($rowx[1] == 'N') 
					{
						$since_reset = 'N';
						$since_resetX = 'Y';
					}
					else 
					{
						$since_reset = 'Y';
						$since_resetX = 'N';
					} 
					$lead_list[$since_reset][$rowx[0]] = ($lead_list[$since_reset][$rowx[0]] + $rowx[2]);
					$lead_list[$since_reset.'_count'] = ($lead_list[$since_reset.'_count'] + $rowx[2]);
					#If opposite side is not set, it may not in the future so give it a value of zero
					if (!isset($lead_list[$since_resetX][$rowx[0]])) 
					{
						$lead_list[$since_resetX][$rowx[0]]=0;
					}
					$o++;
				}

				$o=0;
				if ($lead_list['count'] > 0)
				{
					while (list($owner,) = each($lead_list[$since_reset]))
					{
						if (preg_match('/1$|3$|5$|7$|9$/i', $o))
							{$bgcolor='bgcolor="#fff"';} 
						else
							{$bgcolor='bgcolor="#fff"';}

						$CLB='';
						$CLE='';

						echo "<tr $bgcolor><td><font size=1>$CLB$owner$CLE</td><td><font size=1>".$lead_list['Y'][$owner]."</td><td><font size=1>".$lead_list['N'][$owner]." </td></tr>\n";
						$o++;
					}
				}

				echo "<tr class=table-td-bg-new><td><font size=1>"._QXZ("SUBTOTALS")."</td><td><font size=1>$lead_list[Y_count]</td><td><font size=1>$lead_list[N_count]</td></tr>\n";
				echo "<tr class=table-td-bg-new><td><font size=1>"._QXZ("TOTAL")."</td><td colspan=3 align=center><font size=1>$lead_list[count]</td></tr>\n";
			}
			echo "</table></center><br>\n";
			unset($lead_list);

			if ($SScountry_code_list_stats > 0)
			{
				echo "<center>\n";
				echo "<br><b>"._QXZ("COUNTRY CODES WITHIN THIS LIST").":</b><br>\n";
				echo "<TABLE width=800 cellspacing=3>\n";
				echo "<tr><td>"._QXZ("CODE")."</td><td>"._QXZ("CALLED")."</td><td>"._QXZ("NOT CALLED")."</td></tr>\n";

				$leads_in_list = 0;
				$leads_in_list_N = 0;
				$leads_in_list_Y = 0;
				$stmt="SELECT country_code,called_since_last_reset,count(*) from vicidial_list where list_id='$list_id' group by country_code,called_since_last_reset order by country_code,called_since_last_reset;";
				if ($DB) {echo "$stmt\n";}
				$rslt=mysql_to_mysqli($stmt, $link);
				$ranks_to_print = mysqli_num_rows($rslt);

				$o=0;
				$lead_list['count'] = 0;
				$lead_list['Y_count'] = 0;
				$lead_list['N_count'] = 0;
				while ($ranks_to_print > $o) 
				{
					$rowx=mysqli_fetch_row($rslt);
					
					$lead_list['count'] = ($lead_list['count'] + $rowx[2]);
					if ($rowx[1] == 'N') 
					{
						$since_reset = 'N';
						$since_resetX = 'Y';
					}
					else 
					{
						$since_reset = 'Y';
						$since_resetX = 'N';
					} 
					$lead_list[$since_reset][$rowx[0]] = ($lead_list[$since_reset][$rowx[0]] + $rowx[2]);
					$lead_list[$since_reset.'_count'] = ($lead_list[$since_reset.'_count'] + $rowx[2]);
					#If opposite side is not set, it may not in the future so give it a value of zero
					if (!isset($lead_list[$since_resetX][$rowx[0]])) 
					{
						$lead_list[$since_resetX][$rowx[0]]=0;
					}
					$o++;
				}

				$o=0;
				if ($lead_list['count'] > 0)
				{
					while (list($rank,) = each($lead_list[$since_reset]))
					{
						if (preg_match('/1$|3$|5$|7$|9$/i', $o))
							{$bgcolor='bgcolor="#fff"';} 
						else
							{$bgcolor='bgcolor="#fff"';}

						$CLB='';
						$CLE='';

						echo "<tr $bgcolor><td><font size=1>$CLB$rank$CLE</td><td><font size=1>".$lead_list['Y'][$rank]."</td><td><font size=1>".$lead_list['N'][$rank]." </td></tr>\n";
						$o++;
					}
				}

				echo "<tr><td><font size=1>"._QXZ("SUBTOTALS")."</td><td><font size=1>$lead_list[Y_count]</td><td><font size=1>$lead_list[N_count]</td></tr>\n";
				echo "<tr bgcolor=\"#$SSstd_row1_background\"><td><font size=1>"._QXZ("TOTAL")."</td><td colspan=3 align=center><font size=1>$lead_list[count]</td></tr>\n";

				echo "</table></center><br>\n";
				unset($lead_list);			
			}

			$leads_in_list = 0;
			$leads_in_list_N = 0;
			$leads_in_list_Y = 0;
			$stmt="SELECT status, if(called_count >= 100, 100, called_count), count(*) from vicidial_list where list_id='$list_id' group by status, if(called_count >= 100, 100, called_count) order by status,called_count;";
			$rslt=mysql_to_mysqli($stmt, $link);
			$status_called_to_print = mysqli_num_rows($rslt);

			$status = $MT;
			$o=0;
			$sts=0;
			$first_row=1;
			$all_called_first=1000;
			$all_called_last=0;
			while ($status_called_to_print > $o) 
			{
				$rowx=mysqli_fetch_row($rslt);
				$leads_in_list = ($leads_in_list + $rowx[2]);
				$count_statuses[$o]			= $rowx[0];
				$count_called[$o]			= $rowx[1];
				$count_count[$o]			= $rowx[2];
				$all_called_count[$rowx[1]] = ($all_called_count[$rowx[1]] + $rowx[2]);

				if ( (strlen($status[$sts]) < 1) or ($status[$sts] != "$rowx[0]") )
				{
					if ($first_row) {$first_row=0;}
					else {$sts++;}
					$status[$sts] = "$rowx[0]";
					$status_called_first[$sts] = "$rowx[1]";
					if ($status_called_first[$sts] < $all_called_first) {$all_called_first = $status_called_first[$sts];}
				}
				$leads_in_sts[$sts] = ($leads_in_sts[$sts] + $rowx[2]);
				$status_called_last[$sts] = "$rowx[1]";
				if ($status_called_last[$sts] > $all_called_last) {$all_called_last = $status_called_last[$sts];}

				$o++;
			}


			echo "<center>\n";
			echo "<br><b style='font-size:15px;'>"._QXZ("CALLED COUNTS WITHIN THIS LIST").":</b><br>\n";
			echo "<TABLE width=800 cellspacing=1 class='called-counts-table'>\n";
			echo "<tr class=table-bg-new><td align=left><font size=1>"._QXZ("STATUS")."</td><td align=center><font size=1>"._QXZ("STATUS NAME")."</td>";
			$first = $all_called_first;
			while ($first <= $all_called_last)
			{
				if (preg_match('/1$|3$|5$|7$|9$/i', $first)) {$AB='bgcolor="#7b7c80"';} 
				else{$AB='bgcolor="#7b7c80"';}
				if ($first >= 100) {$Fplus='+';}
				else {$Fplus='';}
				echo "<td align=center $AB><font size=1>$first$Fplus</td>";
				// echo "<td align=center $AB><font size=1>Total</td>";
				$first++;
			}
			echo "<td align=center><font size=1>"._QXZ("SUBTOTAL")."</td></tr>\n";

			$sts=0;
			$statuses_called_to_print = count($status);
			while ($statuses_called_to_print > $sts) 
			{
				$Pstatus = $status[$sts];
				if (preg_match("/1$|3$|5$|7$|9$/i", $sts))
					{$bgcolor='bgcolor="#fff"';   $AB='bgcolor="#fff"';} 
				else
					{$bgcolor='bgcolor="#fff"';   $AB='bgcolor="#fff"';}
			#	echo "$status[$sts]|$status_called_first[$sts]|$status_called_last[$sts]|$leads_in_sts[$sts]|\n";
			#	echo "$status[$sts]|";
				echo "<tr class=table-td-bg-new $bgcolor><td><font size=1>$Pstatus</td><td><font size=1>$statuses_list[$Pstatus]</td>";

				$first = $all_called_first;
				while ($first <= $all_called_last)
				{
					if (preg_match("/1$|3$|5$|7$|9$/i", $sts))
					{
						if (preg_match('/1$|3$|5$|7$|9$/i', $first)) {$AB='bgcolor="#fff"';} 
						else{$AB='bgcolor="#fff"';}
					}
					else
					{
						if (preg_match("/0$|2$|4$|6$|8$/i", $first)) {$AB='bgcolor="#fff"';} 
						else{$AB='bgcolor="#fff"';}
					}

					$called_printed=0;
					$o=0;
					while ($status_called_to_print > $o)
					{
						if ( ($count_statuses[$o] == "$Pstatus") and ($count_called[$o] == "$first") )
						{
							$called_printed++;
							echo "<td class=table-td-bg-new $AB><font size=1> <a href=\"admin_search_lead.php?list_id=$list_id&status=$Pstatus&called_count=$first\">$count_count[$o]</a></td>";
						}

						$o++;
					}
					if (!$called_printed) 
						{echo "<td $AB><font size=1> &nbsp;</td>";}
					$first++;
				}
				echo "<td class=table-td-bg-new><font size=1><a href=\"admin_search_lead.php?list_id=$list_id&status=$Pstatus\">$leads_in_sts[$sts]</a></td></tr>\n\n";

				$sts++;
			}

			echo "<tr class=table-td-bg-new><td align=center colspan=2><b><font size=1>"._QXZ("TOTAL")."</td>";
			$first = $all_called_first;
			while ($first <= $all_called_last)
			{
				if (preg_match('/1$|3$|5$|7$|9$/i', $first)) {$AB='bgcolor="#AFEEEE"';} 
				else{$AB='bgcolor="#E0FFFF"';}
				echo "<td align=center $AB><b><font size=1><a href=\"admin_search_lead.php?list_id=$list_id&called_count=$first\">$all_called_count[$first]</a></td>";
				$first++;
			}
			echo "<td align=center class=table-td-bg-new><b><font size=1>$leads_in_list</td></tr>\n";

			echo "</table></center><br>\n";

			echo "<center><b>\n";

			if ($SScustom_fields_enabled > 0)
			{
				$stmt="SELECT count(*) from vicidial_lists_fields where list_id='$list_id';";
				$rslt=mysql_to_mysqli($stmt, $link);
				$rowx=mysqli_fetch_row($rslt);

				echo "<br><br><a href=\"./$admin_lists_custom?action=MODIFY_CUSTOM_FIELDS&list_id=$list_id\">"._QXZ("Custom fields defined for this list").": $rowx[0]</a><BR>\n";
			}

?>
