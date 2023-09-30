<style>
	.vv-tekdial-login{
		overflow: hidden;
		background: #eee;
		position: relative;
		background-image:url('./images/tekdial-login-bg.png');	
	}
	.vv-tekdial-login:after {
	    content: '';
	    width: 100%;
	    height: 100%;
	    background-color: rgb(140 196 63 / 80%);
	    position: absolute;
	    top: 0px;
	    left: 0px;
	    z-index: -1;
	}
</style>


<?php
$WeBRooTWritablE=1;
	if ($WeBRooTWritablE > 0)
		{$fp = fopen ("./vicidial_auth_entries.txt", "a");}
	$VDloginDISPLAY=0;

	if ( (strlen($VD_login)<2) or (strlen($VD_pass)<2) or (strlen($VD_campaign)<2) )
		{
		$VDloginDISPLAY=1;
		}
	else
		{
		//$response = is_xlite_login($VD_login);
		$response = 1;//is_xlite_login($VD_login);
		if($response==1){		
		
		$auth=0;
		$auth_message = user_authorization($VD_login,$VD_pass,'',1,0,1,0,'vicidial');
		if (preg_match("/^GOOD/",$auth_message))
			{
			$auth=1;
			$pass_hash = preg_replace("/GOOD\|/",'',$auth_message);
			}
		# case-sensitive check for user
		if($auth>0)
			{
			if ($VD_login != "$VUuser") 
				{
				$auth=0;
				$auth_message='ERRCASE';
				}
			}

		if($auth>0)
			{
			##### grab the full name and other settings of the agent
			$stmt="SELECT full_name,user_level,hotkeys_active,agent_choose_ingroups,scheduled_callbacks,agentonly_callbacks,agentcall_manual,vicidial_recording,vicidial_transfers,closer_default_blended,user_group,vicidial_recording_override,alter_custphone_override,alert_enabled,agent_shift_enforcement_override,shift_override_flag,allow_alerts,closer_campaigns,agent_choose_territories,custom_one,custom_two,custom_three,custom_four,custom_five,agent_call_log_view_override,agent_choose_blended,agent_lead_search_override,preset_contact_search,max_inbound_calls,wrapup_seconds_override,email,user_choose_language,ready_max_logout,mute_recordings,max_inbound_filter_enabled,status_group_id from vicidial_users where user='$VD_login' and active='Y' and api_only_user != '1';";
			$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01007',$VD_login,$server_ip,$session_name,$one_mysql_log);}
			$row=mysqli_fetch_row($rslt);
			$LOGfullname =							$row[0];
			$user_level =							$row[1];
			$VU_hotkeys_active =					$row[2];
			$VU_agent_choose_ingroups =				$row[3];
			$VU_scheduled_callbacks =				$row[4];
			$agentonly_callbacks =					$row[5];
			$agentcall_manual =						$row[6];
			$VU_vicidial_recording =				$row[7];
			$VU_vicidial_transfers =				$row[8];
			$VU_closer_default_blended =			$row[9];
			$VU_user_group =						$row[10];
			$VU_vicidial_recording_override =		$row[11];
			$VU_alter_custphone_override =			$row[12];
			$VU_alert_enabled =						$row[13];
			$VU_agent_shift_enforcement_override =	$row[14];
			$VU_shift_override_flag =				$row[15];
			$VU_allow_alerts =						$row[16];
			$VU_closer_campaigns =					$row[17];
			$VU_agent_choose_territories =			$row[18];
			$VU_custom_one =						$row[19];
			$VU_custom_two =						$row[20];
			$VU_custom_three =						$row[21];
			$VU_custom_four =						$row[22];
			$VU_custom_five =						$row[23];
			$VU_agent_call_log_view_override =		$row[24];
			$VU_agent_choose_blended =				$row[25];
			$VU_agent_lead_search_override =		$row[26];
			$VU_preset_contact_search =				$row[27];
			$VU_max_inbound_calls =					$row[28];
			$VU_wrapup_seconds_override =			$row[29];
			$LOGemail =								$row[30];
			$VU_user_choose_language =				$row[31];
			$VU_ready_max_logout =					$row[32];
			$VU_mute_recordings =					$row[33];
			$VU_max_inbound_filter_enabled =		$row[34];
			$VU_status_group_id =					$row[35];

			if ( ($VU_alert_enabled > 0) and ($VU_allow_alerts > 0) ) {$VU_alert_enabled = 'ON';}
			else {$VU_alert_enabled = 'OFF';}
			$AgentAlert_allowed = $VU_allow_alerts;

			### Gather timeclock and shift enforcement restriction settings
			$stmt="SELECT forced_timeclock_login,shift_enforcement,group_shifts,agent_status_viewable_groups,agent_status_view_time,agent_call_log_view,agent_xfer_consultative,agent_xfer_dial_override,agent_xfer_vm_transfer,agent_xfer_blind_transfer,agent_xfer_dial_with_customer,agent_xfer_park_customer_dial,agent_fullscreen,webphone_url_override,webphone_dialpad_override,webphone_systemkey_override,admin_viewable_groups,agent_xfer_park_3way,webphone_layout from vicidial_user_groups where user_group='$VU_user_group';";
			$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01052',$VD_login,$server_ip,$session_name,$one_mysql_log);}
			$row=mysqli_fetch_row($rslt);
			$forced_timeclock_login =	$row[0];
			$shift_enforcement =		$row[1];
			$LOGgroup_shiftsSQL = preg_replace('/\s\s/i','',$row[2]);
			$LOGgroup_shiftsSQL = preg_replace('/\s/i',"','",$LOGgroup_shiftsSQL);
			$LOGgroup_shiftsSQL = "shift_id IN('$LOGgroup_shiftsSQL')";
			$agent_status_viewable_groups = $row[3];
			$agent_status_viewable_groupsSQL = preg_replace('/\s\s/i','',$agent_status_viewable_groups);
			$agent_status_viewable_groupsSQL = preg_replace('/\s/i',"','",$agent_status_viewable_groupsSQL);
			$agent_status_viewable_groupsSQL = "user_group IN('$agent_status_viewable_groupsSQL')";
			$agent_status_view = 0;
			if (strlen($agent_status_viewable_groups) > 2)
				{$agent_status_view = 1;}
			$agent_status_view_time=0;
			if ($row[4] == 'Y')
				{$agent_status_view_time=1;}
			if ($row[5] == 'Y')
				{$agent_call_log_view=1;}
			if ($row[6] == 'Y')
				{$agent_xfer_consultative=1;}
			if ($row[7] == 'Y')
				{$agent_xfer_dial_override=1;}
			if ($row[8] == 'Y')
				{$agent_xfer_vm_transfer=1;}
			if ($row[9] == 'Y')
				{$agent_xfer_blind_transfer=1;}
			if ($row[10] == 'Y')
				{$agent_xfer_dial_with_customer=1;}
			if ($row[11] == 'Y')
				{$agent_xfer_park_customer_dial=1;}
			if ( ($row[17] == 'Y') and ($SSagent_xfer_park_3way > 0) )
				{$agent_xfer_park_3way=1;}
			if ($VU_agent_call_log_view_override == 'Y')
				{$agent_call_log_view=1;}
			if ($VU_agent_call_log_view_override == 'N')
				{$agent_call_log_view=0;}
			$agent_fullscreen =				$row[12];
			$webphone_url =					$row[13];
			$webphone_dialpad_override =	$row[14];
			$system_key =					$row[15];
			$admin_viewable_groups =		$row[16];
			$webphone_layout_override =		$row[18];

			$admin_viewable_groupsALL=0;
			$LOGadmin_viewable_groupsSQL='';
			$whereLOGadmin_viewable_groupsSQL='';
			$valLOGadmin_viewable_groupsSQL='';
			$vmLOGadmin_viewable_groupsSQL='';
			if ( (!preg_match('/\-\-ALL\-\-/i',$admin_viewable_groups)) and (strlen($admin_viewable_groups) > 3) )
				{
				$rawLOGadmin_viewable_groupsSQL = preg_replace("/ -/",'',$admin_viewable_groups);
				$rawLOGadmin_viewable_groupsSQL = preg_replace("/ /","','",$rawLOGadmin_viewable_groupsSQL);
				$LOGadmin_viewable_groupsSQL = "and user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
				$whereLOGadmin_viewable_groupsSQL = "where user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
				$valLOGadmin_viewable_groupsSQL = "and val.user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
				$vmLOGadmin_viewable_groupsSQL = "and vm.user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
				}
			else 
				{$admin_viewable_groupsALL=1;}

			if ( ($webphone_dialpad_override != 'DISABLED') and (strlen($webphone_dialpad_override) > 0) )
				{$webphone_dialpad = $webphone_dialpad_override;}

			if ( (strlen($VD_language)>0) and ($VU_user_choose_language == '1') )
				{
				$LANGUAGEactive=0;
				if ($VD_language == 'default English')
					{$LANGUAGEactive=1;}
				else
					{
					$stmt="SELECT count(*) FROM vicidial_languages where language_id='$VD_language' and active='Y' $LOGadmin_viewable_groupsSQL;";
					if ($DB) {echo "|$stmt|\n";}
					$rslt=mysql_to_mysqli($stmt, $link);
						if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01082',$VD_login,$server_ip,$session_name,$one_mysql_log);}
					$row=mysqli_fetch_row($rslt);
					$LANGUAGEactive=$row[0];
					}

				if ($LANGUAGEactive > 0)
					{
					$stmt="UPDATE vicidial_users SET selected_language='$VD_language' where user='$VD_login';";
					if ($DB) {echo "$stmt\n";}
					$rslt=mysql_to_mysqli($stmt, $link);
							if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01083',$VD_login,$server_ip,$session_name,$one_mysql_log);}
					$VUlanguage_affected_rows = mysqli_affected_rows($link);

					echo "<!-- USER LANGUAGE OVERRIDE: |$VUselected_language|$VD_language| -->\n";

					$VUselected_language=$VD_language;
					}
				}

			### BEGIN - CHECK TO SEE IF AGENT IS LOGGED IN TO TIMECLOCK, IF NOT, OUTPUT ERROR
			if ( (preg_match('/Y/',$forced_timeclock_login)) or ( (preg_match('/ADMIN_EXEMPT/',$forced_timeclock_login)) and ($VU_user_level < 8) ) )
				{
				$last_agent_event='';
				$HHMM = date("Hi");
				$HHteod = substr($timeclock_end_of_day,0,2);
				$MMteod = substr($timeclock_end_of_day,2,2);

				if ($HHMM < $timeclock_end_of_day)
					{$EoD = mktime($HHteod, $MMteod, 10, date("m"), date("d")-1, date("Y"));}
				else
					{$EoD = mktime($HHteod, $MMteod, 10, date("m"), date("d"), date("Y"));}

				$EoDdate = date("Y-m-d H:i:s", $EoD);

				##### grab timeclock logged-in time for each user #####
				$stmt="SELECT event from vicidial_timeclock_log where user='$VD_login' and event_epoch >= '$EoD' order by timeclock_id desc limit 1;";
				$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01053',$VD_login,$server_ip,$session_name,$one_mysql_log);}
				$events_to_parse = mysqli_num_rows($rslt);
				if ($events_to_parse > 0)
					{
					$rowx=mysqli_fetch_row($rslt);
					$last_agent_event = $rowx[0];
					}
				if ($DB>0) {echo "|$stmt|$events_to_parse|$last_agent_event|";}
				if ( (strlen($last_agent_event)<2) or (preg_match('/LOGOUT/',$last_agent_event)) )
					{
					$VDloginDISPLAY=1;
                    $VDdisplayMESSAGE = _QXZ("YOU MUST LOG IN TO THE TIMECLOCK FIRST")."<br />";
					}
				}
			### END - CHECK TO SEE IF AGENT IS LOGGED IN TO TIMECLOCK, IF NOT, OUTPUT ERROR

			### BEGIN - CHECK TO SEE IF SHIFT ENFORCEMENT IS ENABLED AND AGENT IS OUTSIDE OF THEIR SHIFTS, IF SO, OUTPUT ERROR
			if ( ( (preg_match("/START|ALL/",$shift_enforcement)) and (!preg_match("/OFF/",$VU_agent_shift_enforcement_override)) ) or (preg_match("/START|ALL/",$VU_agent_shift_enforcement_override)) )
				{
				$shift_ok=0;
				if ( (strlen($LOGgroup_shiftsSQL) < 3) and ($VU_shift_override_flag < 1) )
					{
					$VDloginDISPLAY=1;
                    $VDdisplayMESSAGE = _QXZ("ERROR: There are no Shifts enabled for your user group")."<br />";
					}
				else
					{
					$HHMM = date("Hi");
					$wday = date("w");

					$stmt="SELECT shift_id,shift_start_time,shift_length,shift_weekdays from vicidial_shifts where $LOGgroup_shiftsSQL order by shift_id";
					$rslt=mysql_to_mysqli($stmt, $link);
						if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01056',$VD_login,$server_ip,$session_name,$one_mysql_log);}
					$shifts_to_print = mysqli_num_rows($rslt);

					$o=0;
					while ( ($shifts_to_print > $o) and ($shift_ok < 1) )
						{
						$rowx=mysqli_fetch_row($rslt);
						$shift_id =			$rowx[0];
						$shift_start_time =	$rowx[1];
						$shift_length =		$rowx[2];
						$shift_weekdays =	$rowx[3];

						if (preg_match("/$wday/i",$shift_weekdays))
							{
							$HHshift_length = substr($shift_length,0,2);
							$MMshift_length = substr($shift_length,3,2);
							$HHshift_start_time = substr($shift_start_time,0,2);
							$MMshift_start_time = substr($shift_start_time,2,2);
							$HHshift_end_time = ($HHshift_length + $HHshift_start_time);
							$MMshift_end_time = ($MMshift_length + $MMshift_start_time);
							if ($MMshift_end_time > 59)
								{
								$MMshift_end_time = ($MMshift_end_time - 60);
								$HHshift_end_time++;
								}
							if ($HHshift_end_time > 23)
								{$HHshift_end_time = ($HHshift_end_time - 24);}
							$HHshift_end_time = sprintf("%02s", $HHshift_end_time);	
							$MMshift_end_time = sprintf("%02s", $MMshift_end_time);	
							$shift_end_time = "$HHshift_end_time$MMshift_end_time";

							if ( 
								( ($HHMM >= $shift_start_time) and ($HHMM < $shift_end_time) ) or
								( ($HHMM < $shift_start_time) and ($HHMM < $shift_end_time) and ($shift_end_time <= $shift_start_time) ) or
								( ($HHMM >= $shift_start_time) and ($HHMM >= $shift_end_time) and ($shift_end_time <= $shift_start_time) )
							   )
								{$shift_ok++;}
							}
						$o++;
						}

					if ( ($shift_ok < 1) and ($VU_shift_override_flag < 1) )
						{
						$VDloginDISPLAY=1;
                        $VDdisplayMESSAGE = _QXZ("ERROR: You are not allowed to log in outside of your shift")."<br />";
						}
					}
				if ( ($shift_ok < 1) and ($VU_shift_override_flag < 1) and ($VDloginDISPLAY > 0) )
					{
                    $VDdisplayMESSAGE.= "<br /><br />"._QXZ("MANAGER OVERRIDE:")."<br />\n";
                    $VDdisplayMESSAGE.= "<form action=\"$PHP_SELF\" method=\"post\">\n";
                    $VDdisplayMESSAGE.= "<input type=\"hidden\" name=\"MGR_override\" value=\"1\" />\n";
                    $VDdisplayMESSAGE.= "<input type=\"hidden\" name=\"relogin\" value=\"YES\" />\n";
                    $VDdisplayMESSAGE.= "<input type=\"hidden\" name=\"DB\" value=\"$DB\" />\n";
                    $VDdisplayMESSAGE.= "<input type=\"hidden\" name=\"phone_login\" value=\"$phone_login\" />\n";
                    $VDdisplayMESSAGE.= "<input type=\"hidden\" name=\"phone_pass\" value=\"$phone_pass\" />\n";
                    $VDdisplayMESSAGE.= "<input type=\"hidden\" name=\"VD_login\" value=\"$VD_login\" />\n";
                    $VDdisplayMESSAGE.= "<input type=\"hidden\" name=\"VD_pass\" value=\"$VD_pass\" />\n";
                    $VDdisplayMESSAGE.= "<input type=\"hidden\" name=\"LOGINvarONE\" id=\"LOGINvarONE\" value=\"$LOGINvarONE\" />\n";
                    $VDdisplayMESSAGE.= "<input type=\"hidden\" name=\"LOGINvarTWO\" id=\"LOGINvarTWO\" value=\"$LOGINvarTWO\" />\n";
                    $VDdisplayMESSAGE.= "<input type=\"hidden\" name=\"LOGINvarTHREE\" id=\"LOGINvarTHREE\" value=\"$LOGINvarTHREE\" />\n";
                    $VDdisplayMESSAGE.= "<input type=\"hidden\" name=\"LOGINvarFOUR\" id=\"LOGINvarFOUR\" value=\"$LOGINvarFOUR\" />\n";
                    $VDdisplayMESSAGE.= "<input type=\"hidden\" name=\"LOGINvarFIVE\" id=\"LOGINvarFIVE\" value=\"$LOGINvarFIVE\" />\n";
                    $VDdisplayMESSAGE.= "Manager Login: <input type=\"text\" name=\"MGR_login$loginDATE\" size=\"10\" maxlength=\"20\" /><br />\n";
                    $VDdisplayMESSAGE.= "Manager Password: <input type=\"password\" name=\"MGR_pass$loginDATE\" size=\"10\" maxlength=\"20\" /><br />\n";
                    $VDdisplayMESSAGE.= "<input type=\"submit\" name=\"SUBMIT\" value=\""._QXZ("SUBMIT")."\" /></form>\n";
					}
				}
			### END - CHECK TO SEE IF SHIFT ENFORCEMENT IS ENABLED AND AGENT IS OUTSIDE OF THEIR SHIFTS, IF SO, OUTPUT ERROR

			### BEGIN find any custom field labels ###
			$label_title =				_QXZ(" Title");
			$label_first_name =			_QXZ("First");
			$label_middle_initial =		_QXZ("MI");
			$label_last_name =			_QXZ("Last ");
			$label_address1 =			_QXZ("Address1");
			$label_address2 =			_QXZ("Address2");
			$label_address3 =			_QXZ("Address3");
			$label_city =				_QXZ("City");
			$label_state =				_QXZ(" State");
			$label_province =			_QXZ("Province");
			$label_postal_code =		_QXZ("PostCode");
			$label_vendor_lead_code =	_QXZ("Vendor ID");
			$label_gender =				_QXZ(" Gender");
			$label_phone_number =		_QXZ("Phone");
			$label_phone_code =			_QXZ("DialCode");
			$label_alt_phone =			_QXZ("Alt. Phone");
			$label_security_phrase =	_QXZ("Show");
			$label_email =				_QXZ("Email");
			$label_comments =			_QXZ(" Comments");

			$stmt="SELECT label_title,label_first_name,label_middle_initial,label_last_name,label_address1,label_address2,label_address3,label_city,label_state,label_province,label_postal_code,label_vendor_lead_code,label_gender,label_phone_number,label_phone_code,label_alt_phone,label_security_phrase,label_email,label_comments from system_settings;";
			$rslt=mysql_to_mysqli($stmt, $link);
			$row=mysqli_fetch_row($rslt);
			if (strlen($row[0])>0)	{$label_title =				$row[0];}
			if (strlen($row[1])>0)	{$label_first_name =		$row[1];}
			if (strlen($row[2])>0)	{$label_middle_initial =	$row[2];}
			if (strlen($row[3])>0)	{$label_last_name =			$row[3];}
			if (strlen($row[4])>0)	{$label_address1 =			$row[4];}
			if (strlen($row[5])>0)	{$label_address2 =			$row[5];}
			if (strlen($row[6])>0)	{$label_address3 =			$row[6];}
			if (strlen($row[7])>0)	{$label_city =				$row[7];}
			if (strlen($row[8])>0)	{$label_state =				$row[8];}
			if (strlen($row[9])>0)	{$label_province =			$row[9];}
			if (strlen($row[10])>0) {$label_postal_code =		$row[10];}
			if (strlen($row[11])>0) {$label_vendor_lead_code =	$row[11];}
			if (strlen($row[12])>0) {$label_gender =			$row[12];}
			if (strlen($row[13])>0) {$label_phone_number =		$row[13];}
			if (strlen($row[14])>0) {$label_phone_code =		$row[14];}
			if (strlen($row[15])>0) {$label_alt_phone =			$row[15];}
			if (strlen($row[16])>0) {$label_security_phrase =	$row[16];}
			if (strlen($row[17])>0) {$label_email =				$row[17];}
			if (strlen($row[18])>0) {$label_comments =			$row[18];}
			### END find any custom field labels ###
			if ($label_gender == '---HIDE---')
				{$hide_gender=1;}

			if ($WeBRooTWritablE > 0)
				{
				fwrite ($fp, "vdweb|GOOD|$date|$VD_login|XXXX|$ip|$browser|$LOGfullname|\n");
				fclose($fp);
				}
			$user_abb = "$VD_login$VD_login$VD_login$VD_login";
			while ( (strlen($user_abb) > 4) and ($forever_stop < 200) )
				{$user_abb = preg_replace("/^\./i","",$user_abb);   $forever_stop++;}

			$stmt="SELECT allowed_campaigns from vicidial_user_groups where user_group='$VU_user_group';";
			$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01008',$VD_login,$server_ip,$session_name,$one_mysql_log);}
			$row=mysqli_fetch_row($rslt);
			$LOGallowed_campaigns		=$row[0];

			if ( (!preg_match("/\s$VD_campaign\s/i",$LOGallowed_campaigns)) and (!preg_match("/ALL-CAMPAIGNS/i",$LOGallowed_campaigns)) )
				{
				echo "<title>"._QXZ("Agent web client: Campaign Login")."</title>\n";
				echo "</head>\n";
                echo "<body onresize=\"browser_dimensions();\" onload=\"browser_dimensions();\">\n";
				if ($hide_timeclock_link < 1)
                    {echo "<a href=\"./timeclock.php?referrer=agent&amp;pl=$phone_login&amp;pp=$phone_pass&amp;VD_login=$VD_login&amp;VD_pass=$VD_pass\"> <font class=\"sb_text\">"._QXZ("Timeclock")."</font></a>$grey_link<br />\n";}
                echo "<table width=\"100%\"><tr><td></td>\n";
				echo "<!-- INTERNATIONALIZATION-LINKS-PLACEHOLDER-VICIDIAL -->\n";
                echo "</tr></table>\n";
                echo "<b><font class=\"skb_text\">"._QXZ("Sorry, you are not allowed to login to this campaign:")." $VD_campaign</b>\n";
                echo "<form action=\"$PHP_SELF\" method=\"post\">\n";
                echo "<input type=\"hidden\" name=\"db\" value=\"$DB\" />\n";
                echo "<input type=\"hidden\" name=\"JS_browser_height\" id=\"JS_browser_height\" value=\"\" />\n";
                echo "<input type=\"hidden\" name=\"JS_browser_width\" id=\"JS_browser_width\" value=\"\" />\n";
                echo "<input type=\"hidden\" name=\"phone_login\" value=\"$phone_login\" />\n";
                echo "<input type=\"hidden\" name=\"phone_pass\" value=\"$phone_pass\" />\n";
				echo "<input type=\"hidden\" name=\"LOGINvarONE\" id=\"LOGINvarONE\" value=\"$LOGINvarONE\" />\n";
				echo "<input type=\"hidden\" name=\"LOGINvarTWO\" id=\"LOGINvarTWO\" value=\"$LOGINvarTWO\" />\n";
				echo "<input type=\"hidden\" name=\"LOGINvarTHREE\" id=\"LOGINvarTHREE\" value=\"$LOGINvarTHREE\" />\n";
				echo "<input type=\"hidden\" name=\"LOGINvarFOUR\" id=\"LOGINvarFOUR\" value=\"$LOGINvarFOUR\" />\n";
				echo "<input type=\"hidden\" name=\"LOGINvarFIVE\" id=\"LOGINvarFIVE\" value=\"$LOGINvarFIVE\" />\n";
                echo "<font class=\"skb_text\">"._QXZ("Login").": <input type=\"text\" name=\"VD_login\" size=\"10\" maxlength=\"20\" value=\"$VD_login\" />\n<br />";
                echo "<font class=\"skb_text\">"._QXZ("Password").": <input type=\"password\" name=\"VD_pass\" size=\"10\" maxlength=\"20\" value=\"$VD_pass\" /><br />\n";
                echo "<font class=\"skb_text\">"._QXZ("Campaign").": <span id=\"LogiNCamPaigns\">$camp_form_code</span><br />\n";
                echo "<input type=\"submit\" name=\"SUBMIT\" value=\""._QXZ("SUBMIT")."\" /> &nbsp; \n";
				echo "<span id=\"LogiNReseT\"></span>\n";
                echo "</form>\n\n";
				echo "</body>\n\n";
				echo "</html>\n\n";
				exit;
				}

			##### check to see that the campaign is active
			$stmt="SELECT count(*) FROM vicidial_campaigns where campaign_id='$VD_campaign' and active='Y';";
			if ($DB) {echo "|$stmt|\n";}
			$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01009',$VD_login,$server_ip,$session_name,$one_mysql_log);}
			$row=mysqli_fetch_row($rslt);
			$CAMPactive=$row[0];
			if($CAMPactive>0)
				{
				$VARstatuses='';
				$VARstatusnames='';
				$VARSELstatuses='';
				$VARSELstatuses_ct=0;
				$VARCBstatuses='';
				$VARMINstatuses='';
				$VARMAXstatuses='';
				$VARCBstatusesLIST='';
				$cVARstatuses='';
				$cVARstatusnames='';
				$cVARSELstatuses='';
				$cVARSELstatuses_ct=0;
				$cVARCBstatuses='';
				$cVARMINstatuses='';
				$cVARMAXstatuses='';
				$cVARCBstatusesLIST='';
				##### grab the statuses that can be used for dispositioning by an agent for all calls
				$stmt="SELECT status,status_name,scheduled_callback,selectable,min_sec,max_sec FROM vicidial_statuses WHERE status != 'NEW' order by status limit 500;";
				$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01010',$VD_login,$server_ip,$session_name,$one_mysql_log);}
				if ($DB) {echo "$stmt\n";}
				$VD_statuses_ct = mysqli_num_rows($rslt);
				$i=0;
				while ($i < $VD_statuses_ct)
					{
					$row=mysqli_fetch_row($rslt);
					$statuses[$i] =		$row[0];
					$status_names[$i] =	$row[1];
					$CBstatuses[$i] =	$row[2];
					$SELstatuses[$i] =	$row[3];
					$MINsec[$i] =		$row[4];
					$MAXsec[$i] =		$row[5];
					if ($TEST_all_statuses > 0) {$SELstatuses[$i]='Y';}
					$VARstatuses = "$VARstatuses'$statuses[$i]',";
					$VARstatusnames = "$VARstatusnames'$status_names[$i]',";
					$VARSELstatuses = "$VARSELstatuses'$SELstatuses[$i]',";
					$VARCBstatuses = "$VARCBstatuses'$CBstatuses[$i]',";
					$VARMINstatuses = "$VARMINstatuses'$MINsec[$i]',";
					$VARMAXstatuses = "$VARMAXstatuses'$MAXsec[$i]',";
					if ($CBstatuses[$i] == 'Y')
						{$VARCBstatusesLIST .= " $statuses[$i]";}
					if ($SELstatuses[$i] == 'Y')
						{$VARSELstatuses_ct++;}
					$i++;
					}

				##### grab the additional user statuses that can be used for dispositioning by an agent for all calls
				if (strlen($VU_status_group_id) > 0)
					{
					$stmt="SELECT status,status_name,scheduled_callback,selectable,min_sec,max_sec FROM vicidial_campaign_statuses WHERE status != 'NEW' and campaign_id='$VU_status_group_id' order by status limit 500;";
					$rslt=mysql_to_mysqli($stmt, $link);
					if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01XXX',$VD_login,$server_ip,$session_name,$one_mysql_log);}
					if ($DB) {echo "$stmt\n";}
					$VU_statuses_ct = mysqli_num_rows($rslt);
					$k=0;
					while ($k < $VU_statuses_ct)
						{
						$row=mysqli_fetch_row($rslt);
						$statuses[$i] =		$row[0];
						$status_names[$i] =	$row[1];
						$CBstatuses[$i] =	$row[2];
						$SELstatuses[$i] =	$row[3];
						$MINsec[$i] =		$row[4];
						$MAXsec[$i] =		$row[5];
						if ($TEST_all_statuses > 0) {$SELstatuses[$i]='Y';}
						$VARstatuses = "$VARstatuses'$statuses[$i]',";
						$VARstatusnames = "$VARstatusnames'$status_names[$i]',";
						$VARSELstatuses = "$VARSELstatuses'$SELstatuses[$i]',";
						$VARCBstatuses = "$VARCBstatuses'$CBstatuses[$i]',";
						$VARMINstatuses = "$VARMINstatuses'$MINsec[$i]',";
						$VARMAXstatuses = "$VARMAXstatuses'$MAXsec[$i]',";
						if ($CBstatuses[$i] == 'Y')
							{$VARCBstatusesLIST .= " $statuses[$i]";}
						if ($SELstatuses[$i] == 'Y')
							{$VARSELstatuses_ct++;}
						$i++;
						$k++;
						}
					}

				##### grab the campaign-specific statuses that can be used for dispositioning by an agent
				$stmt="SELECT status,status_name,scheduled_callback,selectable,min_sec,max_sec FROM vicidial_campaign_statuses WHERE status != 'NEW' and campaign_id='$VD_campaign' order by status limit 500;";
				$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01011',$VD_login,$server_ip,$session_name,$one_mysql_log);}
				if ($DB) {echo "$stmt\n";}
				$VD_statuses_camp = mysqli_num_rows($rslt);
				$j=0;
				while ($j < $VD_statuses_camp)
					{
					$row=mysqli_fetch_row($rslt);
					$statuses[$i] =		$row[0];
					$status_names[$i] =	$row[1];
					$CBstatuses[$i] =	$row[2];
					$SELstatuses[$i] =	$row[3];
					$MINsec[$i] =		$row[4];
					$MAXsec[$i] =		$row[5];
					if ($TEST_all_statuses > 0) {$SELstatuses[$i]='Y';}
					$cVARstatuses = "$cVARstatuses'$statuses[$i]',";
					$cVARstatusnames = "$cVARstatusnames'$status_names[$i]',";
					$cVARSELstatuses = "$cVARSELstatuses'$SELstatuses[$i]',";
					$cVARCBstatuses = "$cVARCBstatuses'$CBstatuses[$i]',";
					$cVARMINstatuses = "$cVARMINstatuses'$MINsec[$i]',";
					$cVARMAXstatuses = "$cVARMAXstatuses'$MAXsec[$i]',";
					if ($CBstatuses[$i] == 'Y')
						{$cVARCBstatusesLIST .= " $statuses[$i]";}
					if ($SELstatuses[$i] == 'Y')
						{$cVARSELstatuses_ct++;}
					$i++;
					$j++;
					}
			#	$VD_statuses_ct = ($VD_statuses_ct+$VD_statuses_camp);
				$VARstatuses = substr("$VARstatuses", 0, -1);
				$VARstatusnames = substr("$VARstatusnames", 0, -1);
				$VARSELstatuses = substr("$VARSELstatuses", 0, -1);
				$VARCBstatuses = substr("$VARCBstatuses", 0, -1);
				$VARMINstatuses = substr("$VARMINstatuses", 0, -1);
				$VARMAXstatuses = substr("$VARMAXstatuses", 0, -1);
				$VARCBstatusesLIST .= " ";
				$cVARstatuses = substr("$cVARstatuses", 0, -1);
				$cVARstatusnames = substr("$cVARstatusnames", 0, -1);
				$cVARSELstatuses = substr("$cVARSELstatuses", 0, -1);
				$cVARCBstatuses = substr("$cVARCBstatuses", 0, -1);
				$cVARMINstatuses = substr("$cVARMINstatuses", 0, -1);
				$cVARMAXstatuses = substr("$cVARMAXstatuses", 0, -1);
				$cVARCBstatusesLIST .= " ";

				##### grab the campaign-specific HotKey statuses that can be used for dispositioning by an agent
				$stmt="SELECT hotkey,status,status_name FROM vicidial_campaign_hotkeys WHERE selectable='Y' and status != 'NEW' and campaign_id='$VD_campaign' order by hotkey limit 9;";
				$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01012',$VD_login,$server_ip,$session_name,$one_mysql_log);}
				if ($DB) {echo "$stmt\n";}
				$HK_statuses_camp = mysqli_num_rows($rslt);
				$w=0;
				$HKboxA='';
				$HKboxB='';
				$HKboxC='';
				while ($w < $HK_statuses_camp)
					{
					$row=mysqli_fetch_row($rslt);
					$HKhotkey[$w] =$row[0];
					$HKstatus[$w] =$row[1];
					$HKstatus_name[$w] =$row[2];
					$HKhotkeys = "$HKhotkeys'$HKhotkey[$w]',";
					$HKstatuses = "$HKstatuses'$HKstatus[$w]',";
					$HKstatusnames = "$HKstatusnames'$HKstatus_name[$w]',";
					if ($w < 3)
                        {$HKboxA = "$HKboxA <font class=\"skb_text\">$HKhotkey[$w]</font> - $HKstatus[$w] - $HKstatus_name[$w]<br />";}
					if ( ($w >= 3) and ($w < 6) )
                        {$HKboxB = "$HKboxB <font class=\"skb_text\">$HKhotkey[$w]</font> - $HKstatus[$w] - $HKstatus_name[$w]<br />";}
					if ($w >= 6)
                        {$HKboxC = "$HKboxC <font class=\"skb_text\">$HKhotkey[$w]</font> - $HKstatus[$w] - $HKstatus_name[$w]<br />";}
					$w++;
					}
				$HKhotkeys = substr("$HKhotkeys", 0, -1); 
				$HKstatuses = substr("$HKstatuses", 0, -1); 
				$HKstatusnames = substr("$HKstatusnames", 0, -1); 

				##### grab the campaign settings
				$stmt="SELECT park_ext,park_file_name,web_form_address,allow_closers,auto_dial_level,dial_timeout,dial_prefix,campaign_cid,campaign_vdad_exten,campaign_rec_exten,campaign_recording,campaign_rec_filename,campaign_script,get_call_launch,am_message_exten,xferconf_a_dtmf,xferconf_a_number,xferconf_b_dtmf,xferconf_b_number,alt_number_dialing,scheduled_callbacks,wrapup_seconds,wrapup_message,closer_campaigns,use_internal_dnc,allcalls_delay,omit_phone_code,agent_pause_codes_active,no_hopper_leads_logins,campaign_allow_inbound,manual_dial_list_id,default_xfer_group,xfer_groups,disable_alter_custphone,display_queue_count,manual_dial_filter,agent_clipboard_copy,use_campaign_dnc,three_way_call_cid,dial_method,three_way_dial_prefix,web_form_target,vtiger_screen_login,agent_allow_group_alias,default_group_alias,quick_transfer_button,prepopulate_transfer_preset,view_calls_in_queue,view_calls_in_queue_launch,call_requeue_button,pause_after_each_call,no_hopper_dialing,agent_dial_owner_only,agent_display_dialable_leads,web_form_address_two,agent_select_territories,crm_popup_login,crm_login_address,timer_action,timer_action_message,timer_action_seconds,start_call_url,dispo_call_url,xferconf_c_number,xferconf_d_number,xferconf_e_number,use_custom_cid,scheduled_callbacks_alert,scheduled_callbacks_count,manual_dial_override,blind_monitor_warning,blind_monitor_message,blind_monitor_filename,timer_action_destination,enable_xfer_presets,hide_xfer_number_to_dial,manual_dial_prefix,customer_3way_hangup_logging,customer_3way_hangup_seconds,customer_3way_hangup_action,ivr_park_call,manual_preview_dial,api_manual_dial,manual_dial_call_time_check,my_callback_option,per_call_notes,agent_lead_search,agent_lead_search_method,queuemetrics_phone_environment,auto_pause_precall,auto_pause_precall_code,auto_resume_precall,manual_dial_cid,custom_3way_button_transfer,callback_days_limit,disable_dispo_screen,disable_dispo_status,screen_labels,status_display_fields,pllb_grouping,pllb_grouping_limit,in_group_dial,in_group_dial_select,pause_after_next_call,owner_populate,manual_dial_lead_id,dead_max,dispo_max,pause_max,dead_max_dispo,dispo_max_dispo,max_inbound_calls,manual_dial_search_checkbox,hide_call_log_info,timer_alt_seconds,wrapup_bypass,wrapup_after_hotkey,callback_active_limit,callback_active_limit_override,comments_all_tabs,comments_dispo_screen,comments_callback_screen,qc_comment_history,show_previous_callback,clear_script,manual_dial_search_filter,web_form_address_three,manual_dial_override_field,status_display_ingroup,customer_gone_seconds,agent_display_fields,manual_dial_timeout,manual_auto_next,manual_auto_show,allow_required_fields,dead_to_dispo,agent_xfer_validation,ready_max_logout,callback_display_days,three_way_record_stop,hangup_xfer_record_start,max_inbound_calls_outcome,manual_auto_next_options,agent_screen_time_display,pause_max_dispo,script_top_dispo,routing_initiated_recordings,dead_trigger_seconds,dead_trigger_action,dead_trigger_repeat,dead_trigger_filename,scheduled_callbacks_force_dial,callback_hours_block,callback_display_days,scheduled_callbacks_timezones_container,three_way_volume_buttons,manual_dial_validation,mute_recordings,leave_vm_no_dispo,leave_vm_message_group_id,campaign_script_two FROM vicidial_campaigns where campaign_id = '$VD_campaign';";
				$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01013',$VD_login,$server_ip,$session_name,$one_mysql_log);}
				if ($DB) {echo "$stmt\n";}
				$row=mysqli_fetch_row($rslt);
				$park_ext =					$row[0];
				$park_file_name =			$row[1];
				$web_form_address =			stripslashes($row[2]);
				$allow_closers =			$row[3];
				$auto_dial_level =			$row[4];
				$dial_timeout =				$row[5];
				$dial_prefix =				$row[6];
				$campaign_cid =				$row[7];
				$campaign_vdad_exten =		$row[8];
				$campaign_rec_exten =		$row[9];
				$campaign_recording =		$row[10];
				$campaign_rec_filename =	$row[11];
				$campaign_script =			$row[12];
				$get_call_launch =			$row[13];
				$campaign_am_message_exten = '8320';
				$xferconf_a_dtmf =			$row[15];
				$xferconf_a_number =		$row[16];
				$xferconf_b_dtmf =			$row[17];
				$xferconf_b_number =		$row[18];
				$alt_number_dialing =		$row[19];
				$VC_scheduled_callbacks =	$row[20];
				$wrapup_seconds =			$row[21];
				$wrapup_message =			$row[22];
				$closer_campaigns =			$row[23];
				$use_internal_dnc =			$row[24];
				$allcalls_delay =			$row[25];
				$omit_phone_code =			$row[26];
				$agent_pause_codes_active =	$row[27];
				$no_hopper_leads_logins =	$row[28];
				$campaign_allow_inbound =	$row[29];
				$manual_dial_list_id =		$row[30];
				$default_xfer_group =		$row[31];
				$xfer_groups =				$row[32];
				$disable_alter_custphone =	$row[33];
				$display_queue_count =		$row[34];
				$manual_dial_filter =		$row[35];
				$CopY_tO_ClipboarD =		$row[36];
				$use_campaign_dnc =			$row[37];
				$three_way_call_cid =		$row[38];
				$dial_method =				$row[39];
				$three_way_dial_prefix =	$row[40];
				$web_form_target =			$row[41];
				$vtiger_screen_login =		$row[42];
				$agent_allow_group_alias =	$row[43];
				$default_group_alias =		$row[44];
				$quick_transfer_button =	$row[45];
				$prepopulate_transfer_preset = $row[46];
				$view_calls_in_queue =		$row[47];
				$view_calls_in_queue_launch = $row[48];
				$call_requeue_button =		$row[49];
				$pause_after_each_call =	$row[50];
				$no_hopper_dialing =		$row[51];
				$agent_dial_owner_only =	$row[52];
				$agent_display_dialable_leads = $row[53];
				$web_form_address_two =		$row[54];
				$agent_select_territories = $row[55];
				$crm_popup_login =			$row[56];
				$crm_login_address =		$row[57];
				$timer_action =				$row[58];
				$timer_action_message =		$row[59];
				$timer_action_seconds =		$row[60];
				$start_call_url =			$row[61];
				$dispo_call_url =			$row[62];
				$xferconf_c_number =		$row[63];
				$xferconf_d_number =		$row[64];
				$xferconf_e_number =		$row[65];
				$use_custom_cid =			$row[66];
				$scheduled_callbacks_alert = $row[67];
				$scheduled_callbacks_count = $row[68];
				$manual_dial_override =		$row[69];
				$blind_monitor_warning =	$row[70];
				$blind_monitor_message =	$row[71];
				$blind_monitor_filename =	$row[72];
				$timer_action_destination =	$row[73];
				$enable_xfer_presets =		$row[74];
				$hide_xfer_number_to_dial =	$row[75];
				$manual_dial_prefix =		$row[76];
				$customer_3way_hangup_logging =	$row[77];
				$customer_3way_hangup_seconds =	$row[78];
				$customer_3way_hangup_action =	$row[79];
				$ivr_park_call =			$row[80];
				$manual_preview_dial =		$row[81];
				$api_manual_dial =			$row[82];
				$manual_dial_call_time_check = $row[83];
				$my_callback_option =		$row[84];
				$per_call_notes = 			$row[85];
				$agent_lead_search =		$row[86];
				$agent_lead_search_method = $row[87];
				$qm_phone_environment =		$row[88];
				$auto_pause_precall =		$row[89];
				$auto_pause_precall_code =	$row[90];
				$auto_resume_precall =		$row[91];
				$manual_dial_cid =			$row[92];
				$custom_3way_button_transfer =	$row[93];
				$callback_days_limit =		$row[94];
				$disable_dispo_screen =		$row[95];
				$disable_dispo_status =		$row[96];
				$screen_labels =			$row[97];
				$status_display_fields =	$row[98];
				$pllb_grouping =			$row[99];
				$pllb_grouping_limit =		$row[100];
				$in_group_dial =			$row[101];
				$in_group_dial_select =		$row[102];
				$pause_after_next_call =	$row[103];
				$owner_populate =			$row[104];
				$manual_dial_lead_id =		$row[105];
				$dead_max =					$row[106];
				$dispo_max =				$row[107];
				$pause_max =				$row[108];
				$dead_max_dispo =			$row[109];
				$dispo_max_dispo =			$row[110];
				$CP_max_inbound_calls =		$row[111];
				$manual_dial_search_checkbox =	$row[112];
				$hide_call_log_info =		$row[113];
				$timer_alt_seconds =		$row[114];
				$wrapup_bypass =			$row[115];
				$wrapup_after_hotkey =		$row[116];
				$callback_active_limit =	$row[117];
				$callback_active_limit_override = $row[118];
				$comments_all_tabs =		$row[119];
				$comments_dispo_screen =	$row[120];
				$comments_callback_screen =	$row[121];
				$qc_comment_history =		$row[122];
				$show_previous_callback =	$row[123];
				$clear_script =				$row[124];
				$manual_dial_search_filter =$row[125];
				$web_form_address_three =	$row[126];
				$manual_dial_override_field=$row[127];
				$status_display_ingroup =	$row[128];
				$customer_gone_seconds =	$row[129];
				$agent_display_fields =		$row[130];
				$manual_dial_timeout =		$row[131];
				$manual_auto_next =			$row[132];
				$manual_auto_show =			$row[133];
				$allow_required_fields =	$row[134];
				$dead_to_dispo =			$row[135];
				$agent_xfer_validation =	$row[136];
				$ready_max_logout =			$row[137];
				$callback_display_days =	$row[138];
				$three_way_record_stop =	$row[139];
				$hangup_xfer_record_start =	$row[140];
				$max_inbound_calls_outcome= $row[141];
				$manual_auto_next_options = $row[142];
				$agent_screen_time_display= $row[143];
				$pause_max_dispo =			$row[144];
				$script_top_dispo =			$row[145];
				$routing_initiated_recording=$row[146];
				$dead_trigger_seconds =		$row[147];
				$dead_trigger_action =		$row[148];
				$dead_trigger_repeat =		$row[149];
				$dead_trigger_filename =	$row[150];
				$scheduled_callbacks_force_dial = $row[151];
				$callback_hours_block =		$row[152];
				$callback_display_days =	$row[153];
				$scheduled_callbacks_timezones_container = $row[154];
				$three_way_volume_buttons = $row[155];
				$manual_dial_validation =	$row[156];
				$mute_recordings =			$row[157];
				$leave_vm_no_dispo =		$row[158];
				$leave_vm_message_group_id = $row[159];
				$campaign_script_two =		$row[160];

				if ($leave_vm_no_dispo == 'ENABLED')
					{$leave_vm_no_dispo = 'VMNOHANG';}
				else
					{$leave_vm_no_dispo = '';}

				if ($SSmute_recordings < 1)
					{$mute_recordings='N';}
				else
					{
					if ($VU_mute_recordings != 'DISABLED')
						{$mute_recordings = $VU_mute_recordings;}
					}

				if ($SSmanual_dial_validation == '2') {$manual_dial_validation='Y';}
				if ($SSmanual_dial_validation == '0') {$manual_dial_validation='N';}

				$scheduled_callbacks_timezones_enabled=0;
				if ( ($scheduled_callbacks_timezones_container != 'DISABLED') and (strlen($scheduled_callbacks_timezones_container) > 0) )
					{$scheduled_callbacks_timezones_enabled++;}

				$MI_PAUSE = 0;
				if (preg_match("/MI_PAUSE/",$max_inbound_calls_outcome))
					{$MI_PAUSE = 1;}

				if ($VU_ready_max_logout >= 0)
					{$ready_max_logout = $VU_ready_max_logout;}

				if ($dead_to_dispo == 'ENABLED')
					{$dead_to_dispo = 1;}
				else
					{$dead_to_dispo = 0;}

				if ( ($SSmanual_auto_next < 1) or ( ($dial_method != 'INBOUND_MAN') and ($dial_method != 'MANUAL') ) )
					{$manual_auto_next = 0;}

				if ( ($manual_dial_timeout < 1) or (strlen($manual_dial_timeout) < 1) )
					{$manual_dial_timeout = $dial_timeout;}

				if ( (strlen($customer_gone_seconds) < 1) or ($customer_gone_seconds < 1) )
					{$customer_gone_seconds=30;}
				$customer_gone_seconds_negative = ($customer_gone_seconds * -1);

				if ( ($callback_active_limit_override == 'Y') and ($callback_active_limit > 0) )
					{
					$temp_cb_act_lmt_ovrd = preg_replace("/[^0-9]/",'',$VU_custom_three);
					if (strlen($temp_cb_act_lmt_ovrd) > 0)
					$callback_active_limit = $temp_cb_act_lmt_ovrd;
					}
				if ($VU_wrapup_seconds_override >= 0)
					{$wrapup_seconds = $VU_wrapup_seconds_override;}
				if ( ($pause_max < 10) or (strlen($pause_max)<2) )
					{$pause_max=0;}
				if ( ($pause_max > 9) and ($pause_max <= $dial_timeout) )
					{$pause_max = ($dial_timeout + 10);}
				if ( ($queuemetrics_pe_phone_append > 0) and (strlen($qm_phone_environment)>0) )
					{$qm_phone_environment .= "-$qm_extension";}

				$status_display_NAME=0;
				$status_display_CALLID=0;
				$status_display_LEADID=0;
				$status_display_LISTID=0;
				if (preg_match("/NAME/",$status_display_fields))
					{$status_display_NAME=1;}
				if (preg_match("/CALLID/",$status_display_fields))
					{$status_display_CALLID=1;}
				if (preg_match("/LEADID/",$status_display_fields))
					{$status_display_LEADID=1;}
				if (preg_match("/LISTID/",$status_display_fields))
					{$status_display_LISTID=1;}

				if ( (strlen($leave_vm_message_group_id) > 1) and ($leave_vm_message_group_id != '---NONE---') )
					{
					$leave_vm_message_group_exists=0;
					$stmt="SELECT count(*) from leave_vm_message_groups where leave_vm_message_group_id='$leave_vm_message_group_id' and active='Y';";
					$rslt=mysql_to_mysqli($stmt, $link);
					if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01089',$VD_login,$server_ip,$session_name,$one_mysql_log);}
					$vmmg_count = mysqli_num_rows($rslt);
					if ($vmmg_count > 0)
						{
						$row=mysqli_fetch_row($rslt);
						if (strlen($row[0])>0)	{$leave_vm_message_group_exists=1;}
						}
					if ($leave_vm_message_group_exists > 0)
						{
						$stmt="SELECT count(*) from leave_vm_message_groups_entries where leave_vm_message_group_id='$leave_vm_message_group_id';";
						$rslt=mysql_to_mysqli($stmt, $link);
						if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01090',$VD_login,$server_ip,$session_name,$one_mysql_log);}
						$vmmge_count = mysqli_num_rows($rslt);
						if ($vmmge_count > 0)
							{
							$row=mysqli_fetch_row($rslt);
							if (strlen($row[0])>0)	{$leave_vm_message_group_exists = $row[0];}
							}
						}
					}

				if ( ($screen_labels != '--SYSTEM-SETTINGS--') and (strlen($screen_labels)>1) )
					{
					$stmt="SELECT label_title,label_first_name,label_middle_initial,label_last_name,label_address1,label_address2,label_address3,label_city,label_state,label_province,label_postal_code,label_vendor_lead_code,label_gender,label_phone_number,label_phone_code,label_alt_phone,label_security_phrase,label_email,label_comments from vicidial_screen_labels where label_id='$screen_labels' and active='Y' limit 1;";
					$rslt=mysql_to_mysqli($stmt, $link);
					if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01073',$VD_login,$server_ip,$session_name,$one_mysql_log);}
					$screenlabels_count = mysqli_num_rows($rslt);
					if ($screenlabels_count > 0)
						{
						$row=mysqli_fetch_row($rslt);
						if (strlen($row[0])>0)	{$label_title =				$row[0];}
						if (strlen($row[1])>0)	{$label_first_name =		$row[1];}
						if (strlen($row[2])>0)	{$label_middle_initial =	$row[2];}
						if (strlen($row[3])>0)	{$label_last_name =			$row[3];}
						if (strlen($row[4])>0)	{$label_address1 =			$row[4];}
						if (strlen($row[5])>0)	{$label_address2 =			$row[5];}
						if (strlen($row[6])>0)	{$label_address3 =			$row[6];}
						if (strlen($row[7])>0)	{$label_city =				$row[7];}
						if (strlen($row[8])>0)	{$label_state =				$row[8];}
						if (strlen($row[9])>0)	{$label_province =			$row[9];}
						if (strlen($row[10])>0) {$label_postal_code =		$row[10];}
						if (strlen($row[11])>0) {$label_vendor_lead_code =	$row[11];}
						if (strlen($row[12])>0) {$label_gender =			$row[12];   $hide_gender=0;}
						if (strlen($row[13])>0) {$label_phone_number =		$row[13];}
						if (strlen($row[14])>0) {$label_phone_code =		$row[14];}
						if (strlen($row[15])>0) {$label_alt_phone =			$row[15];}
						if (strlen($row[16])>0) {$label_security_phrase =	$row[16];}
						if (strlen($row[17])>0) {$label_email =				$row[17];}
						if (strlen($row[18])>0) {$label_comments =			$row[18];}
						### END find any custom field labels ###
						if ($label_gender == '---HIDE---')
							{$hide_gender=1;}
						}
					}

				$launch_scb_force_dial=0;
				if ( ($VC_scheduled_callbacks=='Y') and ($VU_scheduled_callbacks=='1') )
					{
					$scheduled_callbacks='1';
					
					# check for any existing triggered USERONLY Scheduled Callbacks
					if ($scheduled_callbacks_force_dial == 'Y')
						{
						$campaignCBsql = '';
						$campaignCBhoursSQL = '';
						$campaignCBdisplaydaysSQL = '';
						if ($agentonly_callback_campaign_lock > 0)
							{$campaignCBsql = "and campaign_id='$VD_campaign'";}
						if ($callback_hours_block > 0)
							{
							$x_hours_ago = date("Y-m-d H:i:s", mktime(date("H")-$callback_hours_block,date("i"),date("s"),date("m"),date("d"),date("Y")));
							$campaignCBhoursSQL = "and entry_time < \"$x_hours_ago\"";
							}
						if ($callback_display_days > 0)
							{
							$x_days_from_now = date("Y-m-d H:i:s", mktime(0,0,0,date("m"),date("d")+$callback_display_days,date("Y")));
							$campaignCBdisplaydaysSQL = "and callback_time < \"$x_days_from_now\"";
							}

						$stmt = "SELECT count(*) from vicidial_callbacks where recipient='USERONLY' and user='$VD_login' $campaignCBsql $campaignCBhoursSQL $campaignCBdisplaydaysSQL and status IN('LIVE');";
						if ($DB) {echo "$stmt\n";}
						$rslt=mysql_to_mysqli($stmt, $link);
							if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01091',$user,$server_ip,$session_name,$one_mysql_log);}
						$row=mysqli_fetch_row($rslt);
						$launch_scb_force_dial=$row[0];
						}
					}

				$hide_dispo_list=0;
				if ( ($disable_dispo_screen == 'DISPO_ENABLED') or ($disable_dispo_screen == 'DISPO_SELECT_DISABLED') or (strlen($disable_dispo_status) < 1) )
					{
					if ($disable_dispo_screen == 'DISPO_SELECT_DISABLED')
						{$hide_dispo_list=1;}
					$disable_dispo_screen=0;
					$disable_dispo_status='';
					}
				if ( ($disable_dispo_screen == 'DISPO_DISABLED') and (strlen($disable_dispo_status) > 0) )
					{$disable_dispo_screen=1;}
				
				if ( ($VU_agent_lead_search_override == 'ENABLED') or ($VU_agent_lead_search_override == 'LIVE_CALL_INBOUND') or ($VU_agent_lead_search_override == 'LIVE_CALL_INBOUND_AND_MANUAL') or ($VU_agent_lead_search_override == 'DISABLED') )
					{$agent_lead_search = $VU_agent_lead_search_override;}
				$AllowManualQueueCalls=1;
				$AllowManualQueueCallsChoice=0;
				if ($api_manual_dial == 'QUEUE')
					{
					$AllowManualQueueCalls=0;
					$AllowManualQueueCallsChoice=1;
					}
				if ($manual_preview_dial == 'DISABLED')
					{$manual_dial_preview = 0;}
				if ($manual_dial_override == 'ALLOW_ALL')
					{$agentcall_manual = 1;}
				if ($manual_dial_override == 'DISABLE_ALL')
					{$agentcall_manual = 0;}
				if ($user_territories_active < 1)
					{$agent_select_territories = 0;}
				if (preg_match("/Y/",$agent_select_territories))
					{$agent_select_territories=1;}
				else
					{$agent_select_territories=0;}

				if (preg_match("/Y/",$agent_display_dialable_leads))
					{$agent_display_dialable_leads=1;}
				else
					{$agent_display_dialable_leads=0;}

				if (preg_match("/Y/",$no_hopper_dialing))
					{$no_hopper_dialing=1;}
				else
					{$no_hopper_dialing=0;}

				if ( (preg_match("/Y/",$call_requeue_button)) and ($auto_dial_level > 0) )
					{$call_requeue_button=1;}
				else
					{$call_requeue_button=0;}

				if ( (preg_match("/AUTO/",$view_calls_in_queue_launch)) and ($auto_dial_level > 0) )
					{$view_calls_in_queue_launch=1;}
				else
					{$view_calls_in_queue_launch=0;}

				if ( (!preg_match("/NONE/",$view_calls_in_queue)) and ($auto_dial_level > 0) )
					{$view_calls_in_queue=1;}
				else
					{$view_calls_in_queue=0;}

				if (preg_match("/Y/",$pause_after_each_call))
					{$dispo_check_all_pause=1;}

				$quick_transfer_button_enabled=0;
				$quick_transfer_button_locked=0;
				if (preg_match("/IN_GROUP|PRESET_1|PRESET_2|PRESET_3|PRESET_4|PRESET_5/",$quick_transfer_button))
					{$quick_transfer_button_enabled=1;}
				if (preg_match("/LOCKED/",$quick_transfer_button))
					{$quick_transfer_button_locked=1;}

				$custom_3way_button_transfer_enabled=0;
				$custom_3way_button_transfer_park=0;
				$custom_3way_button_transfer_view=0;
				$custom_3way_button_transfer_contacts=0;
				if (preg_match("/PRESET_|FIELD_/",$custom_3way_button_transfer))
					{$custom_3way_button_transfer_enabled=1;}
				if (preg_match("/PARK_/",$custom_3way_button_transfer))
					{$custom_3way_button_transfer_park=1;   $custom_3way_button_transfer_enabled=1;}
				if (preg_match("/VIEW_PRESET/",$custom_3way_button_transfer))
					{$custom_3way_button_transfer_view=1;   $custom_3way_button_transfer_enabled=1;}
				if ( (preg_match("/VIEW_CONTACTS/",$custom_3way_button_transfer)) and ($enable_xfer_presets == 'CONTACTS') and ($VU_preset_contact_search != 'DISABLED') )
					{$custom_3way_button_transfer_contacts=1;   $custom_3way_button_transfer_enabled=1;}

				$preset_populate='';
				$prepopulate_transfer_preset_enabled=0;
				if (preg_match("/PRESET_1|PRESET_2|PRESET_3|PRESET_4|PRESET_5/",$prepopulate_transfer_preset))
					{
					$prepopulate_transfer_preset_enabled=1;
					if (preg_match("/PRESET_1/",$prepopulate_transfer_preset))
						{$preset_populate = $xferconf_a_number;}
					if (preg_match("/PRESET_2/",$prepopulate_transfer_preset))
						{$preset_populate = $xferconf_b_number;}
					if (preg_match("/PRESET_3/",$prepopulate_transfer_preset))
						{$preset_populate = $xferconf_c_number;}
					if (preg_match("/PRESET_4/",$prepopulate_transfer_preset))
						{$preset_populate = $xferconf_d_number;}
					if (preg_match("/PRESET_5/",$prepopulate_transfer_preset))
						{$preset_populate = $xferconf_e_number;}
					}

				$VARpreset_names='';
				$VARpreset_numbers='';
				$VARpreset_dtmfs='';
				$VARpreset_hide_numbers='';
				if ($enable_xfer_presets == 'ENABLED')
					{
					##### grab the presets for this campaign
					$stmt="SELECT preset_name,preset_number,preset_dtmf,preset_hide_number FROM vicidial_xfer_presets WHERE campaign_id='$VD_campaign' order by preset_name limit 500;";
					$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01067',$VD_login,$server_ip,$session_name,$one_mysql_log);}
					if ($DB) {echo "$stmt\n";}
					$VD_presets = mysqli_num_rows($rslt);
					$j=0;
					while ($j < $VD_presets)
						{
						$row=mysqli_fetch_row($rslt);
						$preset_names[$j] =			$row[0];
						$preset_numbers[$j] =		$row[1];
						$preset_dtmfs[$j] =			$row[2];
						$preset_hide_numbers[$j] =	$row[3];
						$VARpreset_names = "$VARpreset_names'$preset_names[$j]',";
						$VARpreset_numbers = "$VARpreset_numbers'$preset_numbers[$j]',";
						$VARpreset_dtmfs = "$VARpreset_dtmfs'$preset_dtmfs[$j]',";
						$VARpreset_hide_numbers = "$VARpreset_hide_numbers'$preset_hide_numbers[$j]',";
						$j++;
						}
					$VARpreset_names = substr("$VARpreset_names", 0, -1);
					$VARpreset_numbers = substr("$VARpreset_numbers", 0, -1);
					$VARpreset_dtmfs = substr("$VARpreset_dtmfs", 0, -1);
					$VARpreset_hide_numbers = substr("$VARpreset_hide_numbers", 0, -1);
					$VD_preset_names_ct = $j;
					if ($j < 1)
						{$enable_xfer_presets='DISABLED';}
					}

				$default_group_alias_cid='';
				if (strlen($default_group_alias)>1)
					{
					$stmt = "select caller_id_number from groups_alias where group_alias_id='$default_group_alias';";
					if ($DB) {echo "$stmt\n";}
					$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01055',$VD_login,$server_ip,$session_name,$one_mysql_log);}
					$VDIG_cidnum_ct = mysqli_num_rows($rslt);
					if ($VDIG_cidnum_ct > 0)
						{
						$row=mysqli_fetch_row($rslt);
						$default_group_alias_cid	= $row[0];
						}
					}

				$stmt = "select group_web_vars from vicidial_campaign_agents where campaign_id='$VD_campaign' and user='$VD_login';";
				if ($DB) {echo "$stmt\n";}
				$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01056',$VD_login,$server_ip,$session_name,$one_mysql_log);}
				$VDIG_cidogwv = mysqli_num_rows($rslt);
				if ($VDIG_cidogwv > 0)
					{
					$row=mysqli_fetch_row($rslt);
					$default_web_vars =	$row[0];
					}

				if ( (!preg_match('/DISABLED/',$VU_vicidial_recording_override)) and ($VU_vicidial_recording > 0) )
					{
					$campaign_recording = $VU_vicidial_recording_override;
					echo "<!-- USER RECORDING OVERRIDE: |$VU_vicidial_recording_override|$campaign_recording| -->\n";
					}
				if ($VU_vicidial_recording=='0')
					{$campaign_recording='NEVER';}
				if ($VU_alter_custphone_override=='ALLOW_ALTER')
					{$disable_alter_custphone='N';}
				if (strlen($manual_dial_prefix) < 1)
					{$manual_dial_prefix = $dial_prefix;}
				if (strlen($three_way_dial_prefix) < 1)
					{$three_way_dial_prefix = $dial_prefix;}
				if ( ($alt_number_dialing=='Y') or ($alt_number_dialing=='SELECTED') or ($alt_number_dialing=='SELECTED_TIMER_ALT') or ($alt_number_dialing=='SELECTED_TIMER_ADDR3') )
					{$alt_phone_dialing='1';}
				else
					{
					$alt_phone_dialing='0';
					$DefaulTAlTDiaL='0';
					}
				if ($display_queue_count=='N')
					{$callholdstatus='0';}
				if ( ($dial_method == 'INBOUND_MAN') or ($outbound_autodial_active < 1) )
					{$VU_closer_default_blended=0;}

				$closer_campaigns = preg_replace("/^ | -$/","",$closer_campaigns);
				$closer_campaigns = preg_replace("/ /","','",$closer_campaigns);
				$ADcloser_campaigns = preg_replace("/'/",'',$closer_campaigns);
				$closer_campaigns = "'$closer_campaigns'";
				$ADcloser_campaignsARY = explode(',',$ADcloser_campaigns);
				$ADcloser_campaignsARYct = count($ADcloser_campaignsARY);
				$ADc=0;
				$ADcloser_campaigns='';
				while ($ADc < $ADcloser_campaignsARYct)
					{
					if (preg_match("/AGENTDIRECT/i",$ADcloser_campaignsARY[$ADc]))
						{$ADcloser_campaigns .= "'$ADcloser_campaignsARY[$ADc]',";}
					$ADc++;
					}
				if (strlen($ADcloser_campaigns) > 3)
					{$ADcloser_campaigns = preg_replace("/,$/","",$ADcloser_campaigns);}


				if ( (preg_match('/Y/',$agent_pause_codes_active)) or (preg_match('/FORCE/',$agent_pause_codes_active)) )
					{
					##### grab the pause codes for this campaign
					$stmt="SELECT pause_code,pause_code_name,require_mgr_approval FROM vicidial_pause_codes WHERE campaign_id='$VD_campaign' order by pause_code limit 100;";
					$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01014',$VD_login,$server_ip,$session_name,$one_mysql_log);}
					if ($DB) {echo "$stmt\n";}
					$VD_pause_codes = mysqli_num_rows($rslt);
					$j=0;
					$mgrapr_ct=0;
					while ($j < $VD_pause_codes)
						{
						$row=mysqli_fetch_row($rslt);
						$pause_codes[$i] =			$row[0];
						$pause_code_names[$i] =		$row[1];
						$pause_code_mgrapr[$i] =	$row[2];
						$VARpause_codes = "$VARpause_codes'$pause_codes[$i]',";
						$VARpause_code_names = "$VARpause_code_names'$pause_code_names[$i]',";
						$VARpause_code_mgrapr = "$VARpause_code_mgrapr'$pause_code_mgrapr[$i]',";
						if ($pause_code_mgrapr[$i] == 'YES') {$mgrapr_ct++;}
						$i++;
						$j++;
						}
					$VD_pause_codes_ct = ($VD_pause_codes_ct+$VD_pause_codes);
					$VARpause_codes = substr("$VARpause_codes", 0, -1);
					$VARpause_code_names = substr("$VARpause_code_names", 0, -1);
					$VARpause_code_mgrapr = substr("$VARpause_code_mgrapr", 0, -1);
					}

				##### grab the inbound groups to choose from if campaign contains CLOSER
				$VARingroups="''";
				$VARingroup_handlers="''";
				$VARphonegroups="''";
				$VARemailgroups="''";
				$VARchatgroups="''";
				if ( ($campaign_allow_inbound == 'Y') and ($dial_method != 'MANUAL') )
					{
					### validate that the agent has not exceeded their max inbound calls for today
					if ( ($VU_max_inbound_calls > 0) or ($CP_max_inbound_calls > 0) )
						{
						$max_inbound_calls = $CP_max_inbound_calls;
						if ($VU_max_inbound_calls > 0)
							{$max_inbound_calls = $VU_max_inbound_calls;}

						$stmt = "SELECT sum(calls_today),sum(calls_today_filtered) FROM vicidial_inbound_group_agents where user='$VD_login' and group_type='C';";
						$rslt=mysql_to_mysqli($stmt, $link);
							if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01080',$VD_login,$server_ip,$session_name,$one_mysql_log);}
						if ($DB) {echo "\n<!-- $rowx[0]|$stmt -->";}
						$vigagt_ct = mysqli_num_rows($rslt);
						if ($vigagt_ct > 0)
							{
							$row=mysqli_fetch_row($rslt);
							$max_inbound_count =		$row[0];
							if ($VU_max_inbound_filter_enabled > 0)
								{$max_inbound_count =		$row[1];}

							if ($max_inbound_count >= $max_inbound_calls)
								{
								if (preg_match("/ALLOW_AGENTDIRECT/",$max_inbound_calls_outcome))
									{$closer_campaigns = $ADcloser_campaigns;}
								else
									{$closer_campaigns = "''";}
								}
							}
						}

					$VARingroups='';
					$VARingroup_handlers='';
					$VARphonegroups='';
					$VARemailgroups='';
					$VARchatgroups='';
					$stmt="SELECT group_id,group_handling from vicidial_inbound_groups where active = 'Y' and group_id IN($closer_campaigns) order by group_id limit 800;";
					$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01015',$VD_login,$server_ip,$session_name,$one_mysql_log);}
					if ($DB) {echo "$stmt\n";}
					$closer_ct = mysqli_num_rows($rslt);
					$INgrpCT=0;
					$EMAILgrpCT=0;
					$CHATgrpCT=0;
					$PHONEgrpCT=0;
					while ($INgrpCT < $closer_ct)
						{
						$row=mysqli_fetch_row($rslt);
						$closer_groups[$INgrpCT] =$row[0];
						$closer_group_handling[$INgrpCT] =$row[1]; // PHONE OR EMAIL OR CHAT - this is important
						$VARingroups = "$VARingroups'$closer_groups[$INgrpCT]',";
						$VARingroup_handlers = "$VARingroup_handlers'$closer_group_handling[$INgrpCT]',";
						if ($row[1]=="EMAIL") // Make a list of ingroups for email handling groups, chat handling groups and one for phones, so there is no overlap
							{
							$VARemailgroups = "$VARemailgroups'$closer_groups[$INgrpCT]',";
							$VARemailgroupsURL = $VARemailgroupsURL."&email_group_ids[]=$closer_groups[$INgrpCT]";
							$EMAILgrpCT++;
							} 
						else if ($row[1]=="CHAT") 
							{
							$VARchatgroups = "$VARchatgroups'$closer_groups[$INgrpCT]',";
							$VARchatgroupsURL = $VARchatgroupsURL."&chat_group_ids[]=$closer_groups[$INgrpCT]";
							$CHATgrpCT++;
							}
						else 
							{
							$VARphonegroups = "$VARphonegroups'$closer_groups[$INgrpCT]',";
							$VARphonegroupsURL = $VARphonegroupsURL."&phone_group_ids[]=$closer_groups[$INgrpCT]";
							$PHONEgrpCT++;
							}
						$INgrpCT++;
						}
					$VARingroups = substr("$VARingroups", 0, -1); 
					$VARingroup_handlers = substr("$VARingroup_handlers", 0, -1); 
					$VARphonegroups = substr("$VARphonegroups", 0, -1); 
					$VARemailgroups = substr("$VARemailgroups", 0, -1); 
					$VARchatgroups = substr("$VARchatgroups", 0, -1); 
					}
				else
					{$closer_campaigns = "''";}

				$in_group_dial_display=0;
				if ($in_group_dial != 'DISABLED')
					{
					$in_group_dial_display=1;

					if ($in_group_dial_select == 'CAMPAIGN_SELECTED')
						{
						$VARdialingroups='';
						$stmt="select group_id from vicidial_inbound_groups where active = 'Y' and group_id IN($closer_campaigns) order by group_id limit 800;";
						$rslt=mysql_to_mysqli($stmt, $link);
							if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01076',$VD_login,$server_ip,$session_name,$one_mysql_log);}
						if ($DB) {echo "$stmt\n";}
						$dialcloser_ct = mysqli_num_rows($rslt);
						$dialINgrpCT=0;
						while ($dialINgrpCT < $dialcloser_ct)
							{
							$row=mysqli_fetch_row($rslt);
							$dial_closer_groups[$dialINgrpCT] =$row[0];
							$VARdialingroups = "$VARdialingroups'$dial_closer_groups[$dialINgrpCT]',";
							$dialINgrpCT++;
							}
						$VARdialingroups = substr("$VARdialingroups", 0, -1); 
						}
					if ($in_group_dial_select == 'ALL_USER_GROUP')
						{
						$VARdialingroups='';
						$stmt="select group_id from vicidial_inbound_groups where active = 'Y' and user_group IN('---ALL---','$user_group') order by group_id limit 800;";
						$rslt=mysql_to_mysqli($stmt, $link);
							if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01077',$VD_login,$server_ip,$session_name,$one_mysql_log);}
						if ($DB) {echo "$stmt\n";}
						$dialcloser_ct = mysqli_num_rows($rslt);
						$dialINgrpCT=0;
						while ($dialINgrpCT < $dialcloser_ct)
							{
							$row=mysqli_fetch_row($rslt);
							$dial_closer_groups[$dialINgrpCT] =$row[0];
							$VARdialingroups = "$VARdialingroups'$dial_closer_groups[$dialINgrpCT]',";
							$dialINgrpCT++;
							}
						$VARdialingroups = substr("$VARdialingroups", 0, -1); 
						}
					}


				##### gather territory listings for this agent if select territories is enabled
				$VARterritories='';
				if ($agent_select_territories > 0)
					{
					$stmt="SELECT territory from vicidial_user_territories where user='$VD_login';";
					$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01062',$VD_login,$server_ip,$session_name,$one_mysql_log);}
					if ($DB) {echo "$stmt\n";}
					$territory_ct = mysqli_num_rows($rslt);
					$territoryCT=0;
					while ($territoryCT < $territory_ct)
						{
						$row=mysqli_fetch_row($rslt);
						$territories[$territoryCT] =$row[0];
						$VARterritories = "$VARterritories'$territories[$territoryCT]',";
						$territoryCT++;
						}
					$VARterritories = substr("$VARterritories", 0, -1); 
					echo "<!-- $territory_ct  $territoryCT |$stmt| -->\n";
					}

				##### grab the allowable inbound groups to choose from for transfer options
				$xfer_groups = preg_replace("/^ | -$/","",$xfer_groups);
				$xfer_groups = preg_replace("/ /","','",$xfer_groups);
				$xfer_groups = "'$xfer_groups'";
				$VARxfergroups="''";
				if ($allow_closers == 'Y')
					{
					$VARxfergroups='';
					$stmt="select group_id,group_name from vicidial_inbound_groups where active = 'Y' and group_id IN($xfer_groups) order by group_id limit 800;";
					$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01016',$VD_login,$server_ip,$session_name,$one_mysql_log);}
					if ($DB) {echo "$stmt\n";}
					$xfer_ct = mysqli_num_rows($rslt);
					$XFgrpCT=0;
					while ($XFgrpCT < $xfer_ct)
						{
						$row=mysqli_fetch_row($rslt);
						$VARxfergroups = "$VARxfergroups'$row[0]',";
						$VARxfergroupsnames = "$VARxfergroupsnames'$row[1]',";
						if ($row[0] == "$default_xfer_group") {$default_xfer_group_name = $row[1];}
						$XFgrpCT++;
						}
					$VARxfergroups = substr("$VARxfergroups", 0, -1); 
					$VARxfergroupsnames = substr("$VARxfergroupsnames", 0, -1); 
					}

				if (preg_match('/Y/',$agent_allow_group_alias))
					{
					##### grab the active group aliases
					$stmt="SELECT group_alias_id,group_alias_name,caller_id_number FROM groups_alias WHERE active='Y' order by group_alias_id limit 1000;";
					$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01054',$VD_login,$server_ip,$session_name,$one_mysql_log);}
					if ($DB) {echo "$stmt\n";}
					$VD_group_aliases = mysqli_num_rows($rslt);
					$j=0;
					while ($j < $VD_group_aliases)
						{
						$row=mysqli_fetch_row($rslt);
						$group_alias_id[$i] =	$row[0];
						$group_alias_name[$i] = $row[1];
						$caller_id_number[$i] = $row[2];
						$VARgroup_alias_ids = "$VARgroup_alias_ids'$group_alias_id[$i]',";
						$VARgroup_alias_names = "$VARgroup_alias_names'$group_alias_name[$i]',";
						$VARcaller_id_numbers = "$VARcaller_id_numbers'$caller_id_number[$i]',";
						$i++;
						$j++;
						}
					$VD_group_aliases_ct = ($VD_group_aliases_ct+$VD_group_aliases);
					$VARgroup_alias_ids = substr("$VARgroup_alias_ids", 0, -1); 
					$VARgroup_alias_names = substr("$VARgroup_alias_names", 0, -1); 
					$VARcaller_id_numbers = substr("$VARcaller_id_numbers", 0, -1); 
					}

				##### grab the number of leads in the hopper for this campaign
				$stmt="SELECT count(*) FROM vicidial_hopper where campaign_id = '$VD_campaign' and status='READY';";
				$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01017',$VD_login,$server_ip,$session_name,$one_mysql_log);}
				if ($DB) {echo "$stmt\n";}
				$row=mysqli_fetch_row($rslt);
				$campaign_leads_to_call = $row[0];
				echo "<!-- $campaign_leads_to_call - leads left to call in hopper -->\n";
				}
			else
				{
				$VDloginDISPLAY=1;
                $VDdisplayMESSAGE = _QXZ("Campaign not active, please try again")."<br />";
				}
				?>
				<script type="text/javascript">
					var user_logged_in = <?php echo $VD_login; ?>;
					// $.ajax({
				 //      type:'POST',
				 //      dataType:'JSON',
				 //      crossDomain: true,
				 //      data:{'UserName':user_logged_in,'Password':'','Key':''},
				 //      // url:'https://beatvistaartest.kloudqapps.net/Account/agentLogin',
				 //      url:'https://beatvistaar.kloudqapps.com/Account/agentLogin',
				 //      success:function(res){
					// 	console.log(res);
				 //      },
				 //      error:function(res){
			  //   		console.log(res);
			  //   	}
				 //    });
				</script>
				<?php
			}
		else
			{
			if ($WeBRooTWritablE > 0)
				{
				fwrite ($fp, "vdweb|FAIL|$date|$VD_login|XXXX|$ip|$browser|\n");
				fclose($fp);
				}
			$VDloginDISPLAY=1;

            $VDdisplayMESSAGE = _QXZ("Login incorrect, please try again")."<br />";
            // header($PHP_SELF);
			if ($auth_message == 'LOCK')
				{$VDdisplayMESSAGE = _QXZ("Too many login attempts, try again in 15 minutes")."<br />";}
			if ($auth_message == 'ERRNETWORK')
				{$VDdisplayMESSAGE = _QXZ("Too many network errors, please contact your administrator")."<br />";}
			if ($auth_message == 'ERRSERVERS')
				{$VDdisplayMESSAGE = _QXZ("No available servers, please contact your administrator")."<br />";}
			if ($auth_message == 'ERRPHONES')
				{$VDdisplayMESSAGE = _QXZ("No available phones, please contact your administrator")."<br />";}
			if ($auth_message == 'ERRDUPLICATE')
				{$VDdisplayMESSAGE = _QXZ("You are already logged in, please log out of your other session first")."<br />";}
			if ($auth_message == 'ERRAGENTS')
				{$VDdisplayMESSAGE = _QXZ("Too many agents logged in, please contact your administrator")."<br />";}
			if ($auth_message == 'ERRCASE')
				{$VDdisplayMESSAGE = _QXZ("Login incorrect, user names are case sensitive")."<br />";}
			if ($auth_message == 'IPBLOCK')
				{$VDdisplayMESSAGE = _QXZ("Your IP Address is not allowed").": $ip<br />";}
			}

		} else{
			$VDloginDISPLAY=1;
			$VDdisplayMESSAGE = _QXZ("You are not logged in SIP phone, please login and try again")."<br />";
		}

		}
	if ($VDloginDISPLAY)
		{
		echo "<title>"._QXZ("Agent web client: Campaign Login")."</title>\n";
		echo "</head>\n";
        echo "<body class=\"vv-tekdial-login\"  onresize=\"browser_dimensions();\"  onload=\"browser_dimensions();\">\n";
		if ($hide_timeclock_link < 1)
            {echo "<a style=\"display:none\" href=\"./timeclock.php?referrer=agent&amp;pl=$phone_login&amp;pp=$phone_pass&amp;VD_login=$VD_login&amp;VD_pass=$VD_pass\"> <font class=\"sb_text\">"._QXZ("Timeclock")."</font></a>$grey_link<br />\n";}
        echo "<table width=\"100%\"><tr><td></td>\n";
		echo "<!-- INTERNATIONALIZATION-LINKS-PLACEHOLDER-VICIDIAL -->\n";
        echo "</tr></table>\n";
        echo "<form name=\"vicidial_form\" id=\"vicidial_form\" action=\"$agcPAGE\" method=\"post\">\n";
        echo "<input type=\"hidden\" name=\"DB\" value=\"$DB\" />\n";
        echo "<input type=\"hidden\" name=\"JS_browser_height\" id=\"JS_browser_height\" value=\"\" />\n";
        echo "<input type=\"hidden\" name=\"JS_browser_width\" id=\"JS_browser_width\" value=\"\" />\n";
        echo "<input type=\"hidden\" name=\"phone_login\" value=\"$phone_login\" />\n";
        echo "<input type=\"hidden\" name=\"phone_pass\" value=\"$phone_pass\" />\n";
		echo "<input type=\"hidden\" name=\"LOGINvarONE\" id=\"LOGINvarONE\" value=\"$LOGINvarONE\" />\n";
		echo "<input type=\"hidden\" name=\"LOGINvarTWO\" id=\"LOGINvarTWO\" value=\"$LOGINvarTWO\" />\n";
		echo "<input type=\"hidden\" name=\"LOGINvarTHREE\" id=\"LOGINvarTHREE\" value=\"$LOGINvarTHREE\" />\n";
		echo "<input type=\"hidden\" name=\"LOGINvarFOUR\" id=\"LOGINvarFOUR\" value=\"$LOGINvarFOUR\" />\n";
		echo "<input type=\"hidden\" name=\"LOGINvarFIVE\" id=\"LOGINvarFIVE\" value=\"$LOGINvarFIVE\" />\n";
		echo "<input type=\"hidden\" name=\"VDdisplayMESSAGE\" id=\"VDdisplayMESSAGE\" value=\"$VDdisplayMESSAGE\" />\n";
        echo "<center><br /><b>$VDdisplayMESSAGE</b><br /><br />";
        echo "<table  cellpadding=\"3\" style=\"width:25%;background-color:#FFFFFF;padding: 50px 0;position: absolute;top: 42%;
    left: 50%;transform: translate(-50%, -45%);box-shadow:0px 0px 17px rgb(0 0 0 / 50%);\" cellspacing=\"20\" class=\"login_frm\" bgcolor=\"#$SSframe_background\">
        <tr bgcolor=\"white\">";
        //echo "<td align=\"left\" valign=\"bottom\" bgcolor=\"#$SSmenu_background\" width=\"170\"><img src=\"$selected_logo\" border=\"0\" height=\"45\" width=\"170\" alt=\"Agent Screen\" /></td>";
        echo "<td align=\"center\" valign=\"middle\" bgcolor=\"#FFFFFF\" colspan=\"2\" > <font class=\"login_frm_title\"><img src=\"./images/tekDial_logo.gif\" alt=\"MAIN\" width=\"200px\" border=\"0\"></font> </td>";
        echo "</tr>\n";
        // echo "<tr><td align=\"right\"><font class=\"skb_text\">"._QXZ("User Login").":</font>  </td>";
        echo "<td align=\"left\"><font class=\"skb_text\">"._QXZ("User Login").":</font><input type=\"text\" required name=\"VD_login\" id=\"VD_login\" class=\"frm form-control\" size=\"10\"  maxlength=\"20\" /></td></tr>\n";
        // echo "<tr><td align=\"right\"><font class=\"skb_text\">"._QXZ("User Password:")."</font>  </td>";
        echo "<td align=\"left\"><font class=\"skb_text\">"._QXZ("User Password:")."</font><input type=\"password\" required class=\"frm form-control\"  name=\"VD_pass\" id=\"VD_pass\" size=\"10\" maxlength=\"20\" /></td></tr>\n";
        echo "<tr id=campaignShow style='float:left;'>";
        echo "</tr>"; 
        echo "<tr><td class=\"btn login_submit_btn\" align=\"center\" colspan=\"2\"><input type=\"submit\" style=\"background-color:#872f6f;color:white\" name=\"SUBMIT\" value=\""._QXZ("Login")."\" /> &nbsp; \n";
        echo "<span id=\"LogiNReseT\"></span></td></tr>\n";
        //echo "<tr><td align=\"left\" colspan=\"2\"><font class=\"body_tiny\"><br />"._QXZ("VERSION:")." $version &nbsp; &nbsp; &nbsp; "._QXZ("BUILD:")." $build</font></td></tr>\n";
        echo "</table>\n";
        echo "</form>\n\n";
        echo "<table class=\"footer-table-ver\" style=\"text-align: center;position: absolute;bottom: 7em;left: 0;width: 100%;\"><tr><td><span style=\"font-size: 15px; color: #333333;font-weight:500;\">Powered By Tekzee Technologies V 1.1</span></td></tr></table>";
		echo "</body>\n\n";
		echo "</html>\n\n";
		exit;
		}
    $phone_login = $_POST['VD_login'];
    $phone_pass=$_POST['VD_pass'];
	$original_phone_login = $phone_login;
   
	# code for parsing load-balanced agent phone allocation where agent interface
	# will send multiple phones-table logins so that the script can determine the
	# server that has the fewest agents logged into it.
	#   login: ca101,cb101,cc101
		$alias_found=0;
	$stmt="select count(*) from phones_alias where alias_id = '$phone_login';";
	
	$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01018',$VD_login,$server_ip,$session_name,$one_mysql_log);}
	$alias_ct = mysqli_num_rows($rslt);
	
	if ($alias_ct > 0)
		{
		$row=mysqli_fetch_row($rslt);
		$alias_found = $row[0];
	
		}
	if ($alias_found > 0)
		{
		$stmt="select alias_name,logins_list from phones_alias where alias_id = '$phone_login' limit 1;";
		$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01019',$VD_login,$server_ip,$session_name,$one_mysql_log);}
		$alias_ct = mysqli_num_rows($rslt);
		if ($alias_ct > 0)
			{
			$row=mysqli_fetch_row($rslt);
			$alias_name = $row[0];
			$phone_login = $row[1];
			}
		}

	$pa=0;
	if ( (preg_match('/,/i',$phone_login)) and (strlen($phone_login) > 2) )
		{
		$phoneSQL = "(";
		$phones_auto = explode(',',$phone_login);
		$phones_auto_ct = count($phones_auto);
		while($pa < $phones_auto_ct)
			{
			if ($pa > 0)
				{$phoneSQL .= " or ";}
			$desc = ($phones_auto_ct - $pa - 1); # traverse in reverse order
			$phoneSQL .= "(login='$phones_auto[$desc]' and pass='$phone_pass')";
			$pa++;
			}
		$phoneSQL .= ")";
		}
    
	else {$phoneSQL = "login='$phone_login' and pass='$phone_pass'";}

	$authphone=0;
	#$stmt="SELECT count(*) from phones where $phoneSQL and active = 'Y';";

	$active_agentSQL = "and active_agent_login_server='Y'";
	if ($admin_test == 'YES')
		{$active_agentSQL='';}
	$stmt="SELECT count(*) from phones,servers where $phoneSQL and phones.active = 'Y' and phones.server_ip=servers.server_ip $active_agentSQL;";
	//echo $stmt;die;
   
	if ($DB) {echo "|$stmt|\n";}
	echo "<!-- server query: $admin_test|$stmt| -->\n";

	$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01020',$VD_login,$server_ip,$session_name,$one_mysql_log);}
	$row=mysqli_fetch_row($rslt);
	$authphone=$row[0];
	if (!$authphone)
		{
		?>
		<script>
    window.location.href = "<?php echo $_SERVER["HTTP_REFERER"]; ?>";
 </script>   
		<?php	

		echo "<title>"._QXZ("Agent web client: Phone Login Error")."</title>\n";
		echo "</head>\n";
        echo "<body onresize=\"browser_dimensions();\"  onload=\"browser_dimensions();\">\n";
		if ($hide_timeclock_link < 1)
            {echo "<a href=\"./timeclock.php?referrer=agent&amp;pl=$phone_login&amp;pp=$phone_pass&amp;VD_login=$VD_login&amp;VD_pass=$VD_pass\"> <font class=\"sb_text\">"._QXZ("Timeclock")."</font></a>$grey_link<br />\n";}
        echo "<table width=\"100%\"><tr><td></td>\n";
		echo "<!-- INTERNATIONALIZATION-LINKS-PLACEHOLDER-VICIDIAL -->\n";
        echo "</tr></table>\n";
        echo "<form name=\"vicidial_form\" id=\"vicidial_form\" action=\"$agcPAGE\" method=\"post\">\n";
        echo "<input type=\"hidden\" name=\"DB\" value=\"$DB\">\n";
        echo "<input type=\"hidden\" name=\"JS_browser_height\" value=\"\" />\n";
        echo "<input type=\"hidden\" name=\"JS_browser_width\" value=\"\" />\n";
        echo "<input type=\"hidden\" name=\"VD_login\" value=\"$VD_login\" />\n";
        echo "<input type=\"hidden\" name=\"VD_pass\" value=\"$VD_pass\" />\n";
        echo "<input type=\"hidden\" name=\"VD_campaign\" value=\"$VD_campaign\" />\n";
		echo "<input type=\"hidden\" name=\"LOGINvarONE\" id=\"LOGINvarONE\" value=\"$LOGINvarONE\" />\n";
		echo "<input type=\"hidden\" name=\"LOGINvarTWO\" id=\"LOGINvarTWO\" value=\"$LOGINvarTWO\" />\n";
		echo "<input type=\"hidden\" name=\"LOGINvarTHREE\" id=\"LOGINvarTHREE\" value=\"$LOGINvarTHREE\" />\n";
		echo "<input type=\"hidden\" name=\"LOGINvarFOUR\" id=\"LOGINvarFOUR\" value=\"$LOGINvarFOUR\" />\n";
		echo "<input type=\"hidden\" name=\"LOGINvarFIVE\" id=\"LOGINvarFIVE\" value=\"$LOGINvarFIVE\" />\n";
        echo "<br /><br /><br /><center><table width=\"460px\" cellpadding=\"3\" cellspacing=\"0\" bgcolor=\"#$SSframe_background\"><tr bgcolor=\"white\">";
        echo "<td align=\"left\" valign=\"bottom\" bgcolor=\"#$SSmenu_background\" width=\"170\"><img src=\"$selected_logo\" border=\"0\" height=\"45\" width=\"170\" alt=\"Agent Screen\" /></td>";
        echo "<td align=\"center\" valign=\"middle\" bgcolor=\"#$SSmenu_background\"> <font class=\"sh_text_white\">"._QXZ("Login Error")."</font></td>";
        echo "</tr>\n";
        echo "<tr><td align=\"center\" colspan=\"2\"><font size=\"1\"> &nbsp; <br /><font size=\"3\">"._QXZ("Sorry, your phone login and password are not active in this system, please try again:")." <br /> &nbsp;</font></td></tr>\n";
        echo "<tr><td align=\"right\"><font class=\"skb_text\">"._QXZ("Phone Login:")."</font> </td>";
        echo "<td align=\"left\"><input type=\"text\" name=\"phone_login\" size=\"10\" maxlength=\"20\" value=\"$phone_login\"></td></tr>\n";
        echo "<tr><td align=\"right\"><font class=\"skb_text\">"._QXZ("Phone Password:")."</font>  </td>";
        echo "<td align=\"left\"><input type=\"password\" name=\"phone_pass\" size=10 maxlength=20 value=\"$phone_pass\"></td></tr>\n";
        echo "<tr><td align=\"center\" colspan=\"2\"><input type=\"submit\" name=\"SUBMIT\" value=\""._QXZ("SUBMIT")."\" /></td></tr>\n";
        echo "<tr><td align=\"left\" colspan=\"2\"><font class=\"body_tiny\"><br />"._QXZ("VERSION:")." $version &nbsp; &nbsp; &nbsp; "._QXZ("BUILD:")." $build</font></td></tr>\n";
        echo "</table></center>\n";
        echo "</form>\n\n";
		echo "</body>\n\n";
		echo "</html>\n\n";
		exit;
		}
	else
		{
		##### BEGIN phone login load balancing functions #####
		### go through the phones logins list to figure out which server has 
		### fewest non-remote agents logged in and use that phone login account
		if ($pa > 0)
			{
			$pb=0;
			$pb_login='';
			$pb_server_ip='';
			$pb_count=0;
			$pb_log='';
			$pb_valid_server_ips='';
			$pb_force_set=0;
			while ( ($pb < $phones_auto_ct) and ($pb_force_set < 1) )
				{
				### find the server_ip of each phone_login
				$stmtn="SELECT count(*) from phones where login = '$phones_auto[$pb]';";
				if ($DB) {echo "|$stmtx|\n";}
				if ($non_latin > 0) {$rslt=mysql_to_mysqli("SET NAMES 'UTF8'", $link);}
				$rslt=mysql_to_mysqli($stmtn, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01084',$VD_login,$server_ip,$session_name,$one_mysql_log);}
				$rown=mysqli_fetch_row($rslt);
				if ($rown[0] > 0)
					{
					$stmtx="SELECT server_ip from phones where login = '$phones_auto[$pb]';";
					if ($DB) {echo "|$stmtx|\n";}
					$rslt=mysql_to_mysqli($stmtx, $link);
					if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01021',$VD_login,$server_ip,$session_name,$one_mysql_log);}
					$rowx=mysqli_fetch_row($rslt);
					}
				else
					{$rowx[0]='0.0.0.0';}
				
				### get number of agents logged in to each server
				$stmt="SELECT count(*) from vicidial_live_agents where server_ip = '$rowx[0]' and extension NOT LIKE \"R%\";";
				if ($DB) {echo "|$stmt|\n";}
				$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01022',$VD_login,$server_ip,$session_name,$one_mysql_log);}
				$row=mysqli_fetch_row($rslt);
				
				### find out whether the server is set to active
				$stmt="SELECT count(*) from servers where server_ip = '$rowx[0]' and active='Y' $active_agentSQL;";
				if ($DB) {echo "|$stmt|\n";}
				$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01023',$VD_login,$server_ip,$session_name,$one_mysql_log);}
				$rowy=mysqli_fetch_row($rslt);

				$stmt="SELECT count(*) FROM vicidial_conferences where server_ip='$rowx[0]' and ((extension='') or (extension is null));";
				if ($DB) {echo "|$stmt|\n";}
				$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01085',$VD_login,$server_ip,$session_name,$one_mysql_log);}
				$rowys=mysqli_fetch_row($rslt);

				### find out if this server has a twin
				$twin_not_live=0;
				if ($rowy[0] > 0)
					{
					$stmt="SELECT active_twin_server_ip from servers where server_ip = '$rowx[0]';";
					if ($DB) {echo "|$stmt|\n";}
					$rslt=mysql_to_mysqli($stmt, $link);
					if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01070',$VD_login,$server_ip,$session_name,$one_mysql_log);}
					$rowyy=mysqli_fetch_row($rslt);
					if (strlen($rowyy[0]) > 4)
						{
						### find out whether the twin server_updater is running
						$stmt="SELECT count(*) from server_updater where server_ip = '$rowyy[0]' and last_update > '$past_minutes_date';";
						if ($DB) {echo "|$stmt|\n";}
						$rslt=mysql_to_mysqli($stmt, $link);
						if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01071',$VD_login,$server_ip,$session_name,$one_mysql_log);}
						$rowyz=mysqli_fetch_row($rslt);
						if ($rowyz[0] < 1) {$twin_not_live=1;}
						}
					}

				### find out whether the server_updater is running
				$stmt="SELECT count(*) from server_updater where server_ip = '$rowx[0]' and last_update > '$past_minutes_date';";
				if ($DB) {echo "|$stmt|\n";}
				$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01024',$VD_login,$server_ip,$session_name,$one_mysql_log);}
				$rowz=mysqli_fetch_row($rslt);

				$pb_log .= "$phones_auto[$pb]|$rowx[0]|$row[0]|$rowy[0]|$rowys[0]|$rowz[0]|$twin_not_live|   ";

				if ( ($rowy[0] > 0) and ($rowys[0] > 0) and ($rowz[0] > 0) and ($twin_not_live < 1) )
					{
					if ( ($pllb_grouping == 'ONE_SERVER_ONLY') or ($pllb_grouping == 'CASCADING') )
						{
						if ($pllb_grouping == 'ONE_SERVER_ONLY')
							{
							### one-server-only plib check
							### get number of agents logged in to each server
							$stmt="SELECT count(*) from vicidial_live_agents where server_ip = '$rowx[0]' and campaign_id='$VD_campaign' and extension NOT LIKE \"R%\";";
							if ($DB) {echo "|$stmt|\n";}
							$rslt=mysql_to_mysqli($stmt, $link);
							if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01074',$VD_login,$server_ip,$session_name,$one_mysql_log);}
							$rowG=mysqli_fetch_row($rslt);
							
							if ($rowG[0] > 0)
								{
								$pb_count=$row[0];
								$pb_server_ip=$rowx[0];
								$phone_login=$phones_auto[$pb];
								$pb_force_set++;
								echo "<!--      PLLB: ONE_SERVER_ONLY|$pb_server_ip|$pb_count| -->\n";
								}
							}
						else
							{
							### cascading plib check
							### get number of agents logged in to each server
							$stmt="SELECT count(*) from vicidial_live_agents where server_ip = '$rowx[0]' and campaign_id='$VD_campaign' and extension NOT LIKE \"R%\";";
							if ($DB) {echo "|$stmt|\n";}
							$rslt=mysql_to_mysqli($stmt, $link);
							if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01075',$VD_login,$server_ip,$session_name,$one_mysql_log);}
							$rowG=mysqli_fetch_row($rslt);
							
							echo "<!--      PLLB CASCADING CHECK: |$pllb_grouping|$rowx[0]|$rowG[0]|$pllb_grouping_limit|   |$row[0]|$SSpllb_grouping_limit| -->\n";
							if ( ($rowG[0] > 0) and ($rowG[0] < $pllb_grouping_limit) and ($row[0] < $SSpllb_grouping_limit) )
								{
								$pb_count=$row[0];
								$pb_server_ip=$rowx[0];
								$phone_login=$phones_auto[$pb];
								$pb_force_set++;
								echo "<!--      PLLB: CASCADING|$pb_server_ip|$pb_count| -->\n";
								}
							}
						}
					if ($DB > 0) {echo "($pb_count <> $row[0]) $pb|$pb_force_set|$phones_auto[$pb]|$pb_server_ip|$pb_count| -->\n";}
					if ($pb_force_set < 1)
						{
						if ( ($pb_count >= $row[0]) or (strlen($pb_server_ip) < 4) )
							{
							$pb_count=$row[0];
							$pb_server_ip=$rowx[0];
							$phone_login=$phones_auto[$pb];
							}
						}
					}
				$pb++;
				}


			echo "<!-- Phones balance selection: $phone_login|$pb_server_ip|$past_minutes_date|$pb_force_set|     |$pb_log -->\n";
			}
		##### END phone login load balancing functions #####

		echo "<title>Agent web client</title>\n";
		$stmt="SELECT extension,dialplan_number,voicemail_id,phone_ip,computer_ip,server_ip,login,pass,status,active,phone_type,fullname,company,picture,messages,old_messages,protocol,local_gmt,ASTmgrUSERNAME,ASTmgrSECRET,login_user,login_pass,login_campaign,park_on_extension,conf_on_extension,VICIDIAL_park_on_extension,VICIDIAL_park_on_filename,monitor_prefix,recording_exten,voicemail_exten,voicemail_dump_exten,ext_context,dtmf_send_extension,call_out_number_group,client_browser,install_directory,local_web_callerID_URL,VICIDIAL_web_URL,AGI_call_logging_enabled,user_switching_enabled,conferencing_enabled,admin_hangup_enabled,admin_hijack_enabled,admin_monitor_enabled,call_parking_enabled,updater_check_enabled,AFLogging_enabled,QUEUE_ACTION_enabled,CallerID_popup_enabled,voicemail_button_enabled,enable_fast_refresh,fast_refresh_rate,enable_persistant_mysql,auto_dial_next_number,VDstop_rec_after_each_call,DBX_server,DBX_database,DBX_user,DBX_pass,DBX_port,DBY_server,DBY_database,DBY_user,DBY_pass,DBY_port,outbound_cid,enable_sipsak_messages,email,template_id,conf_override,phone_context,phone_ring_timeout,conf_secret,is_webphone,use_external_server_ip,codecs_list,webphone_dialpad,phone_ring_timeout,on_hook_agent,webphone_auto_answer,webphone_dialbox,webphone_mute,webphone_volume,webphone_debug,webphone_layout from phones where login='$phone_login' and pass='$phone_pass' and active = 'Y';";
		if ($DB) {echo "|$stmt|\n";}
		$rslt=mysql_to_mysqli($stmt, $link);
			if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01025',$VD_login,$server_ip,$session_name,$one_mysql_log);}
		$row=mysqli_fetch_row($rslt);
		$extension=$row[0];
		$dialplan_number=$row[1];
		$voicemail_id=$row[2];
		$phone_ip=$row[3];
		$computer_ip=$row[4];
		$server_ip=$row[5];
		$login=$row[6];
		$pass=$row[7];
		$status=$row[8];
		$active=$row[9];
		$phone_type=$row[10];
		$fullname=$row[11];
		$company=$row[12];
		$picture=$row[13];
		$messages=$row[14];
		$old_messages=$row[15];
		$protocol=$row[16];
		$local_gmt=$row[17];
		$ASTmgrUSERNAME=$row[18];
		$ASTmgrSECRET=$row[19];
		$login_user=$row[20];
		$login_pass=$row[21];
		$login_campaign=$row[22];
		$park_on_extension=$row[23];
		$conf_on_extension=$row[24];
		$VICIDiaL_park_on_extension=$row[25];
		$VICIDiaL_park_on_filename=$row[26];
		$monitor_prefix=$row[27];
		$recording_exten=$row[28];
		$voicemail_exten=$row[29];
		$voicemail_dump_exten=$row[30];
		$ext_context=$row[31];
		$dtmf_send_extension=$row[32];
		$call_out_number_group=$row[33];
		$client_browser=$row[34];
		$install_directory=$row[35];
		$local_web_callerID_URL=$row[36];
		$VICIDiaL_web_URL=$row[37];
		$AGI_call_logging_enabled=$row[38];
		$user_switching_enabled=$row[39];
		$conferencing_enabled=$row[40];
		$admin_hangup_enabled=$row[41];
		$admin_hijack_enabled=$row[42];
		$admin_monitor_enabled=$row[43];
		$call_parking_enabled=$row[44];
		$updater_check_enabled=$row[45];
		$AFLogging_enabled=$row[46];
		$QUEUE_ACTION_enabled=$row[47];
		$CallerID_popup_enabled=$row[48];
		$voicemail_button_enabled=$row[49];
		$enable_fast_refresh=$row[50];
		$fast_refresh_rate=$row[51];
		$enable_persistant_mysql=$row[52];
		$auto_dial_next_number=$row[53];
		$VDstop_rec_after_each_call=$row[54];
		$DBX_server=$row[55];
		$DBX_database=$row[56];
		$DBX_user=$row[57];
		$DBX_pass=$row[58];
		$DBX_port=$row[59];
		$outbound_cid=$row[65];
		$enable_sipsak_messages=$row[66];
		$conf_secret=$row[72];
		$is_webphone=$row[73];
		$use_external_server_ip=$row[74];
		$codecs_list=$row[75];
		$webphone_dialpad=$row[76];
		$phone_ring_timeout=$row[77];
		$on_hook_agent=$row[78];
		$webphone_auto_answer=$row[79];
		$webphone_dialbox=$row[80];
		$webphone_mute=$row[81];
		$webphone_volume=$row[82];
		$webphone_debug=$row[83];
		$webphone_layout=$row[84];

		if (strlen($webphone_layout_override)>0)
			{$webphone_layout = $webphone_layout_override;}
		$login_context = $ext_context;
		if (strlen($meetme_enter_login_filename) > 0)
			{$login_context = 'meetme-enter-login';}

		if ( ($phone_login == 'nophone') or ($on_hook_agent == 'Y') )
			{
			$no_empty_session_warnings=1;
			}
		if ($PhonESComPIP == '1')
			{
			if (strlen($computer_ip) < 4)
				{
				$stmt="UPDATE phones SET computer_ip='$ip' where login='$phone_login' and pass='$phone_pass' and active = 'Y';";
				if ($DB) {echo "|$stmt|\n";}
				$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01026',$VD_login,$server_ip,$session_name,$one_mysql_log);}
				}
			}
		if ($PhonESComPIP == '2')
			{
			$stmt="UPDATE phones SET computer_ip='$ip' where login='$phone_login' and pass='$phone_pass' and active = 'Y';";
			if ($DB) {echo "|$stmt|\n";}
			$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01027',$VD_login,$server_ip,$session_name,$one_mysql_log);}
			}
		if ($clientDST)
			{
			$local_gmt = ($local_gmt + $isdst);
			}

		$stmt="SELECT asterisk_version,web_socket_url,external_web_socket_url from servers where server_ip='$server_ip';";
		if ($DB) {echo "|$stmt|\n";}
		$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01028',$VD_login,$server_ip,$session_name,$one_mysql_log);}
		$row=mysqli_fetch_row($rslt);
		$asterisk_version =			$row[0];
		$web_socket_url =			$row[1];
		$external_web_socket_url =	$row[2];
		if ( ($use_external_server_ip=='Y') and (strlen($external_web_socket_url) > 5) )
			{$web_socket_url = $external_web_socket_url;}

		if ($protocol == 'EXTERNAL')
			{
			$protocol = 'Local';
			$extension = "$dialplan_number$AT$ext_context";
			}
		if (preg_match("/Zap/i",$protocol))
			{
			if (preg_match("/^1\.0|^1\.2|^1\.4\.1|^1\.4\.20|^1\.4\.21/i",$asterisk_version))
				{$do_nothing=1;}
			else
				{
				$protocol = 'DAHDI';
				}
			}

		$SIP_user = "$protocol/$extension";
		$SIP_user_DiaL = "$protocol/$extension";
		$qm_extension = "$extension";
		if ( (preg_match('/8300/',$dialplan_number)) and (strlen($dialplan_number)<5) and ($protocol == 'Local') )
			{
			$SIP_user = "$protocol/$extension$VD_login";
			$qm_extension = "$extension$VD_login";
			}

		# If a park extension is not set, use the default one
		if ( (strlen($park_ext)>0) && (strlen($park_file_name)>0) )
			{
			$VICIDiaL_park_on_extension = "$park_ext";
			$VICIDiaL_park_on_filename = "$park_file_name";
			echo "<!-- CAMPAIGN CUSTOM PARKING:  |$VICIDiaL_park_on_extension|$VICIDiaL_park_on_filename| -->\n";
			}
		echo "<!-- CAMPAIGN DEFAULT PARKING: |$VICIDiaL_park_on_extension|$VICIDiaL_park_on_filename| -->\n";

		# If a web form address is not set, use the default one
		if (strlen($web_form_address)>0)
			{
			$VICIDiaL_web_form_address = "$web_form_address";
			echo "<!-- CAMPAIGN CUSTOM WEB FORM:   |$VICIDiaL_web_form_address| -->\n";
			}
		else
			{
			$VICIDiaL_web_form_address = "$VICIDiaL_web_URL";
			print "<!-- CAMPAIGN DEFAULT WEB FORM:  |$VICIDiaL_web_form_address| -->\n";
			$VICIDiaL_web_form_address_enc = rawurlencode($VICIDiaL_web_form_address);
			}
		$VICIDiaL_web_form_address_enc = rawurlencode($VICIDiaL_web_form_address);

		# If a web form address two is not set, use the first one
		if (strlen($web_form_address_two)>0)
			{
			$VICIDiaL_web_form_address_two = "$web_form_address_two";
			echo "<!-- CAMPAIGN CUSTOM WEB FORM 2:   |$VICIDiaL_web_form_address_two| -->\n";
			}
		else
			{
			$VICIDiaL_web_form_address_two = "$VICIDiaL_web_form_address";
			echo "<!-- CAMPAIGN DEFAULT WEB FORM 2:  |$VICIDiaL_web_form_address_two| -->\n";
			$VICIDiaL_web_form_address_two_enc = rawurlencode($VICIDiaL_web_form_address_two);
			}
		$VICIDiaL_web_form_address_two_enc = rawurlencode($VICIDiaL_web_form_address_two);

		# If a web form address three is not set, use the first one
		if (strlen($web_form_address_three)>0)
			{
			$VICIDiaL_web_form_address_three = "$web_form_address_three";
			echo "<!-- CAMPAIGN CUSTOM WEB FORM 3:   |$VICIDiaL_web_form_address_three| -->\n";
			}
		else
			{
			$VICIDiaL_web_form_address_three = "$VICIDiaL_web_form_address";
			echo "<!-- CAMPAIGN DEFAULT WEB FORM 3:  |$VICIDiaL_web_form_address_three| -->\n";
			$VICIDiaL_web_form_address_three_enc = rawurlencode($VICIDiaL_web_form_address_three);
			}
		$VICIDiaL_web_form_address_three_enc = rawurlencode($VICIDiaL_web_form_address_three);

		# If closers are allowed on this campaign
		if ($allow_closers=="Y")
			{
			$VICIDiaL_allow_closers = 1;
			echo "<!-- CAMPAIGN ALLOWS CLOSERS:    |$VICIDiaL_allow_closers| -->\n";
			}
		else
			{
			$VICIDiaL_allow_closers = 0;
			echo "<!-- CAMPAIGN ALLOWS NO CLOSERS: |$VICIDiaL_allow_closers| -->\n";
			}


		$session_ext = preg_replace("/[^a-z0-9]/i", "", $extension);
		if (strlen($session_ext) > 10) {$session_ext = substr($session_ext, 0, 10);}
		$session_rand = (rand(1,9999999) + 10000000);
		$session_name = "$StarTtimE$US$session_ext$session_rand";

		if ($webform_sessionname)
			{$webform_sessionname = "&session_name=$session_name";}
		else
			{$webform_sessionname = '';}

		$stmt="DELETE from web_client_sessions where start_time < '$past_month_date' and extension='$extension' and server_ip = '$server_ip' and program = 'vicidial';";
		if ($DB) {echo "|$stmt|\n";}
		$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01029',$VD_login,$server_ip,$session_name,$one_mysql_log);}

		$stmt="INSERT INTO web_client_sessions values('$extension','$server_ip','vicidial','$NOW_TIME','$session_name');";
		if ($DB) {echo "|$stmt|\n";}
		$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01030',$VD_login,$server_ip,$session_name,$one_mysql_log);}

		if ( ( ($campaign_allow_inbound == 'Y') and ($dial_method != 'MANUAL') ) || ($campaign_leads_to_call > 0) || (preg_match('/Y/',$no_hopper_leads_logins)) )
			{
			##### check to see if the user has a conf extension already, this happens if they previously exited uncleanly
			$stmt="SELECT conf_exten FROM vicidial_conferences where extension='$SIP_user' and server_ip = '$server_ip' LIMIT 1;";
			$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01032',$VD_login,$server_ip,$session_name,$one_mysql_log);}
			if ($DB) {echo "$stmt\n";}
			$prev_login_ct = mysqli_num_rows($rslt);
			$i=0;
			while ($i < $prev_login_ct)
				{
				$row=mysqli_fetch_row($rslt);
				$session_id =$row[0];
				$i++;
				}
			if ($prev_login_ct > 0)
				{echo "<!-- USING PREVIOUS MEETME ROOM - $session_id - $NOW_TIME - $SIP_user -->\n";}
			else
				{
				##### grab the next available vicidial_conference room and reserve it
				$stmt="SELECT count(*) FROM vicidial_conferences where server_ip='$server_ip' and ((extension='') or (extension is null));";
				// die("ab");
				if ($DB) {echo "$stmt\n";}
				$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01033',$VD_login,$server_ip,$session_name,$one_mysql_log);}
				$row=mysqli_fetch_row($rslt);
				if ($row[0] > 0)
					{
					$stmt="UPDATE vicidial_conferences set extension='$SIP_user', leave_3way='0' where server_ip='$server_ip' and ((extension='') or (extension is null)) limit 1;";
						if ($format=='debug') {echo "\n<!-- $stmt -->";}
					$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01034',$VD_login,$server_ip,$session_name,$one_mysql_log);}

					$stmt="SELECT conf_exten from vicidial_conferences where server_ip='$server_ip' and ( (extension='$SIP_user') or (extension='$VD_login') );";
						if ($format=='debug') {echo "\n<!-- $stmt -->";}
					$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01035',$VD_login,$server_ip,$session_name,$one_mysql_log);}
					$row=mysqli_fetch_row($rslt);
					$session_id = $row[0];
					}
				echo "<!-- USING NEW MEETME ROOM - $session_id - $NOW_TIME - $SIP_user -->\n";
				}

			### mark leads that were not dispositioned during previous calls as ERI
			$stmt="UPDATE vicidial_list set status='ERI', user='' where status IN('QUEUE','INCALL') and user ='$VD_login';";
			if ($DB) {echo "$stmt\n";}
			$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01036',$VD_login,$server_ip,$session_name,$one_mysql_log);}
			$vlERIaffected_rows = mysqli_affected_rows($link);
			echo "<!-- old QUEUE and INCALL reverted list:   |$vlERIaffected_rows| -->\n";

			$stmt="DELETE from vicidial_hopper where status IN('QUEUE','INCALL','DONE') and user ='$VD_login';";
			if ($DB) {echo "$stmt\n";}
			$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01037',$VD_login,$server_ip,$session_name,$one_mysql_log);}
			$vhICaffected_rows = mysqli_affected_rows($link);
			echo "<!-- old QUEUE and INCALL reverted hopper: |$vhICaffected_rows| -->\n";

			$stmt="DELETE from vicidial_live_agents where user ='$VD_login';";
			if ($DB) {echo "$stmt\n";}
			$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01038',$VD_login,$server_ip,$session_name,$one_mysql_log);}
			$vlaLIaffected_rows = mysqli_affected_rows($link);
			echo "<!-- old vicidial_live_agents records cleared: |$vlaLIaffected_rows| -->\n";

			$stmt="DELETE from vicidial_live_inbound_agents where user ='$VD_login';";
			if ($DB) {echo "$stmt\n";}
			$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01039',$VD_login,$server_ip,$session_name,$one_mysql_log);}
			$vliaLIaffected_rows = mysqli_affected_rows($link);
			echo "<!-- old vicidial_live_inbound_agents records cleared: |$vliaLIaffected_rows| -->\n";

			$stmt="UPDATE routing_initiated_recordings set processed='2' where user='$VD_login' and processed='0';";
			if ($DB) {echo "$stmt\n";}
			$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01086',$VD_login,$server_ip,$session_name,$one_mysql_log);}
			$RIRaffected_rows = mysqli_affected_rows($link);
			echo "<!-- routing_initiated_recordings invalidated:   |$RIRaffected_rows| -->\n";

			$VULhostname = php_uname('n');
			$VULservername = $_SERVER['SERVER_NAME'];
			if (strlen($VULhostname)<1) {$VULhostname='X';}
			if (strlen($VULservername)<1) {$VULservername='X';}

			$stmt="SELECT webserver_id FROM vicidial_webservers where webserver='$VULservername' and hostname='$VULhostname' LIMIT 1;";
			$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01080',$VD_login,$server_ip,$session_name,$one_mysql_log);}
			if ($DB) {echo "$stmt\n";}
			$webserver_id_ct = mysqli_num_rows($rslt);
			if ($webserver_id_ct > 0)
				{
				$row=mysqli_fetch_row($rslt);
				$webserver_id = $row[0];
				}
			else
				{
				##### insert webserver entry
				$stmt="INSERT INTO vicidial_webservers (webserver,hostname) values('$VULservername','$VULhostname');";
				if ($DB) {echo "$stmt\n";}
				$rslt=mysql_to_mysqli($stmt, $link);
						if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01081',$VD_login,$server_ip,$session_name,$one_mysql_log);}
				$affected_rows = mysqli_affected_rows($link);
				$webserver_id = mysqli_insert_id($link);
				echo "<!-- vicidial_webservers record inserted: |$affected_rows|$webserver_id| -->\n";
				}
  

			$stmt="SELECT url_id FROM vicidial_urls where url='$agcPAGE' LIMIT 1;";
			$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01082',$VD_login,$server_ip,$session_name,$one_mysql_log);}
			if ($DB) {echo "$stmt\n";}
			$url_id_ct = mysqli_num_rows($rslt);
			if ($url_id_ct > 0)
				{
				$row=mysqli_fetch_row($rslt);
				$url_id = $row[0];
				}
			else
				{
				##### insert url entry
				$stmt="INSERT INTO vicidial_urls (url) values('$agcPAGE');";
				if ($DB) {echo "$stmt\n";}
				$rslt=mysql_to_mysqli($stmt, $link);
						if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01083',$VD_login,$server_ip,$session_name,$one_mysql_log);}
				$affected_rows = mysqli_affected_rows($link);
				$url_id = mysqli_insert_id($link);
				echo "<!-- vicidial_urls record inserted: |$affected_rows|$url_id| -->\n";
				}

				

			### insert an entry into the user log for the login event
			$vul_data = "$vlERIaffected_rows|$vhICaffected_rows|$vlaLIaffected_rows|$vliaLIaffected_rows";
			$stmt = "INSERT INTO vicidial_user_log (user,event,campaign_id,event_date,event_epoch,user_group,session_id,server_ip,extension,computer_ip,browser,data,phone_login,server_phone,phone_ip,webserver,login_url,browser_width,browser_height) values('$VD_login','LOGIN','$VD_campaign','$NOW_TIME','$StarTtimE','$VU_user_group','$session_id','$server_ip','$protocol/$extension','$ip','$browser','$vul_data','$original_phone_login','$phone_login','LOOKUP','$webserver_id','$url_id','$JS_browser_width','$JS_browser_height');";
			if ($DB) {echo "|$stmt|\n";}
			$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01031',$VD_login,$server_ip,$session_name,$one_mysql_log);}

        #   echo "<b>You have logged in as user: $VD_login on phone: $SIP_user to campaign: $VD_campaign</b><br />\n";
			$VICIDiaL_is_logged_in=1;

			### set the callerID for manager middleware-app to connect the phone to the user
			$SIqueryCID = "S$CIDdate$session_id";

			#############################################
			##### START SYSTEM_SETTINGS LOOKUP #####
			$stmt = "SELECT enable_queuemetrics_logging,queuemetrics_server_ip,queuemetrics_dbname,queuemetrics_login,queuemetrics_pass,queuemetrics_log_id,vicidial_agent_disable,allow_sipsak_messages,queuemetrics_loginout,queuemetrics_addmember_enabled,queuemetrics_pe_phone_append,queuemetrics_pause_type FROM system_settings;";
			
			$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01040',$VD_login,$server_ip,$session_name,$one_mysql_log);}
			if ($DB) {echo "$stmt\n";}
			$qm_conf_ct = mysqli_num_rows($rslt);
			if ($qm_conf_ct > 0)
				{
				$row=mysqli_fetch_row($rslt);
				$enable_queuemetrics_logging =		$row[0];
				$queuemetrics_server_ip	=			$row[1];
				$queuemetrics_dbname =				$row[2];
				$queuemetrics_login	=				$row[3];
				$queuemetrics_pass =				$row[4];
				$queuemetrics_log_id =				$row[5];
				$vicidial_agent_disable =			$row[6];
				$allow_sipsak_messages =			$row[7];
				$queuemetrics_loginout =			$row[8];
				$queuemetrics_addmember_enabled =	$row[9];
				$queuemetrics_pe_phone_append =		$row[10];
				$queuemetrics_pause_type =			$row[11];
				}
			##### END QUEUEMETRICS LOGGING LOOKUP #####
			###########################################

			if ( ($enable_sipsak_messages > 0) and ($allow_sipsak_messages > 0) and (preg_match("/SIP/i",$protocol)) )
				{
				$extension = preg_replace("/\'|\"|\\\\|;/","",$extension);
				$phone_ip = preg_replace("/\'|\"|\\\\|;/","",$phone_ip);
				$SIPSAK_prefix = 'LIN-';
				echo "<!-- sending login sipsak message: $SIPSAK_prefix$VD_campaign -->\n";
				passthru("/usr/local/bin/sipsak -M -O desktop -B \"$SIPSAK_prefix$VD_campaign\" -r 5060 -s sip:$extension@$phone_ip > /dev/null");
				$SIqueryCID = "$SIPSAK_prefix$VD_campaign$DS$CIDdate";
				}

			$WebPhonEurl='';
			$webphone_content='';
			$TEMP_SIP_user_DiaL = $SIP_user_DiaL;
			if ($on_hook_agent == 'Y')
				{$TEMP_SIP_user_DiaL = 'Local/8300@default';}
			### insert a NEW record to the vicidial_manager table to be processed
			$agent_login_data="||$NOW_TIME|NEW|N|$server_ip||Originate|$SIqueryCID|Channel: $SIP_user_DiaL|Context: $login_context|Exten: $session_id|Priority: 1|Callerid: \"$SIqueryCID\" <$campaign_cid>|||||";
			$agent_login_stmt="INSERT INTO vicidial_manager values('','','$NOW_TIME','NEW','N','$server_ip','','Originate','$SIqueryCID','Channel: $TEMP_SIP_user_DiaL','Context: $login_context','Exten: $session_id','Priority: 1','Callerid: \"$SIqueryCID\" <$campaign_cid>','','','','','');";
			$is_phone_session_available_stmt = "SELECT status FROM vicidial_manager WHERE status='UPDATED' AND server_ip='$server_ip' AND action='Originate' AND cmd_line_d='Exten: $session_id' AND cmd_line_b='Channel: $TEMP_SIP_user_DiaL'";
			$is_phone_session_available_rslt = mysql_to_mysqli($is_phone_session_available_stmt,$link);
			$is_phone_session_available_num_row = mysqli_num_rows($is_phone_session_available_rslt);

			if ( ($is_webphone != 'Y') and ($is_webphone != 'Y_API_LAUNCH') )
				{
					if($is_phone_session_available_num_row==0){
						if ($DB) {echo "$agent_login_stmt\n";}
						$rslt=mysql_to_mysqli($agent_login_stmt, $link);
							if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01041',$VD_login,$server_ip,$session_name,$one_mysql_log);}
						$affected_rows = mysqli_affected_rows($link);
						echo "<!-- call placed to session_id: $session_id from phone: $SIP_user $SIP_user_DiaL -->\n";
					}
				}
			else
				{
				### build Iframe variable content for webphone here
				$codecs_list = preg_replace("/ /",'',$codecs_list);
				$codecs_list = preg_replace("/-/",'',$codecs_list);
				$codecs_list = preg_replace("/&/",'',$codecs_list);
				$webphone_server_ip = $server_ip;
				if ($use_external_server_ip=='Y')
					{
					##### find external_server_ip if enabled for this phone account
					$stmt="SELECT external_server_ip FROM servers where server_ip='$server_ip' LIMIT 1;";
					$rslt=mysql_to_mysqli($stmt, $link);
						if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01065',$VD_login,$server_ip,$session_name,$one_mysql_log);}
					if ($DB) {echo "$stmt\n";}
					$exip_ct = mysqli_num_rows($rslt);
					if ($exip_ct > 0)
						{
						$row=mysqli_fetch_row($rslt);
						$webphone_server_ip =$row[0];
						}
					}
				if (strlen($webphone_url) < 6)
					{
					##### find webphone_url in system_settings and generate IFRAME code for it #####
					$stmt="SELECT webphone_url FROM system_settings LIMIT 1;";
					$rslt=mysql_to_mysqli($stmt, $link);
						if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01066',$VD_login,$server_ip,$session_name,$one_mysql_log);}
					if ($DB) {echo "$stmt\n";}
					$wu_ct = mysqli_num_rows($rslt);
					if ($wu_ct > 0)
						{
						$row=mysqli_fetch_row($rslt);
						$webphone_url =$row[0];
						}
					}
				if (strlen($system_key) < 1)
					{
					##### find system_key in system_settings if populated #####
					$stmt="SELECT webphone_systemkey FROM system_settings LIMIT 1;";
					$rslt=mysql_to_mysqli($stmt, $link);
						if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01068',$VD_login,$server_ip,$session_name,$one_mysql_log);}
					if ($DB) {echo "$stmt\n";}
					$wsk_ct = mysqli_num_rows($rslt);
					if ($wsk_ct > 0)
						{
						$row=mysqli_fetch_row($rslt);
						$system_key =$row[0];
						}
					}
				$webphone_options='INITIAL_LOAD';
				if ($webphone_dialpad == 'Y') {$webphone_options .= "--DIALPAD_Y";}
				if ($webphone_dialpad == 'N') {$webphone_options .= "--DIALPAD_N";}
				if ($webphone_dialpad == 'TOGGLE') {$webphone_options .= "--DIALPAD_TOGGLE";}
				if ($webphone_dialpad == 'TOGGLE_OFF') {$webphone_options .= "--DIALPAD_OFF_TOGGLE";}
				if ($webphone_auto_answer == 'Y') {$webphone_options .= "--AUTOANSWER_Y";}
				if ($webphone_auto_answer == 'N') {$webphone_options .= "--AUTOANSWER_N";}
				if ($webphone_dialbox == 'Y') {$webphone_options .= "--DIALBOX_Y";}
				if ($webphone_dialbox == 'N') {$webphone_options .= "--DIALBOX_N";}
				if ($webphone_mute == 'Y') {$webphone_options .= "--MUTE_Y";}
				if ($webphone_mute == 'N') {$webphone_options .= "--MUTE_N";}
				if ($webphone_volume == 'Y') {$webphone_options .= "--VOLUME_Y";}
				if ($webphone_volume == 'N') {$webphone_options .= "--VOLUME_N";}
				if ($webphone_debug == 'Y') {$webphone_options .= "--DEBUG";}
				if (strlen($web_socket_url) > 5) {$webphone_options .= "--WEBSOCKETURL$web_socket_url";}
				if (strlen($webphone_layout) > 0) {$webphone_options .= "--WEBPHONELAYOUT$webphone_layout";}
				$webphone_url = preg_replace("/LOCALFQDN/",$FQDN,$webphone_url);

				### base64 encode variables
				$b64_phone_login =		base64_encode($extension);
				$b64_phone_pass =		base64_encode($conf_secret);
				$b64_session_name =		base64_encode($session_name);
				$b64_server_ip =		base64_encode($webphone_server_ip);
				$b64_callerid =			base64_encode($outbound_cid);
				$b64_protocol =			base64_encode($protocol);
				$b64_codecs =			base64_encode($codecs_list);
				$b64_options =			base64_encode($webphone_options);
				$b64_system_key =		base64_encode($system_key);

				$WebPhonEurl = "$webphone_url?phone_login=$b64_phone_login&phone_login=$b64_phone_login&phone_pass=$b64_phone_pass&server_ip=$b64_server_ip&callerid=$b64_callerid&protocol=$b64_protocol&codecs=$b64_codecs&options=$b64_options&system_key=$b64_system_key";

				if ($is_webphone == 'Y')
					{
					if ($webphone_location == 'bar')
						{
						$webphone_content = "<iframe src=\"$WebPhonEurl\" style=\"width:" . $webphone_width . "px;height:" . $webphone_height . "px;background-color:transparent;z-index:17;\" scrolling=\"no\" frameborder=\"0\" allowtransparency=\"true\" id=\"webphone\" name=\"webphone\" width=\"" . $webphone_width . "px\" height=\"" . $webphone_height . "px\" allow=\"microphone *; speakers *;\"> </iframe>";
						}
					else
						{
						$webphone_content = "<iframe src=\"$WebPhonEurl\" style=\"width:" . $webphone_width . "px;height:" . $webphone_height . "px;background-color:transparent;z-index:17;\" scrolling=\"auto\" frameborder=\"0\" allowtransparency=\"true\" id=\"webphone\" name=\"webphone\" width=\"" . $webphone_width . "px\" height=\"" . $webphone_height . "px\" allow=\"microphone *; speakers *;\"> </iframe>";
						}
					}
				}

			$stmt="DELETE from vicidial_session_data where user='$VD_login';";
			if ($DB) {echo "|$stmt|\n";}
			$rslt=mysql_to_mysqli($stmt, $link);
					if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01078',$VD_login,$server_ip,$session_name,$one_mysql_log);}

			$stmt="INSERT INTO vicidial_session_data SET session_name='$session_name',user='$VD_login',campaign_id='$VD_campaign',server_ip='$server_ip',conf_exten='$session_id',extension='$extension',login_time='$NOW_TIME',webphone_url='$WebPhonEurl',agent_login_call='$agent_login_data';";
			if ($DB) {echo "|$stmt|\n";}
			$rslt=mysql_to_mysqli($stmt, $link);
					if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01079',$VD_login,$server_ip,$session_name,$one_mysql_log);}

			##### grab the campaign_weight and number of calls today on that campaign for the agent
			$stmt="SELECT campaign_weight,calls_today,campaign_grade FROM vicidial_campaign_agents where user='$VD_login' and campaign_id = '$VD_campaign';";
			$rslt=mysql_to_mysqli($stmt, $link);
			if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01042',$VD_login,$server_ip,$session_name,$one_mysql_log);}
			if ($DB) {echo "$stmt\n";}
			$vca_ct = mysqli_num_rows($rslt);
			if ($vca_ct > 0)
				{
				$row=mysqli_fetch_row($rslt);
				$campaign_weight =	$row[0];
				$calls_today =		$row[1];
				$campaign_grade =	$row[2];
				$i++;
				}
			else
				{
				$campaign_weight =	'0';
				$calls_today =		'0';
				$campaign_grade =	'1';
				$stmt="INSERT INTO vicidial_campaign_agents (user,campaign_id,campaign_rank,campaign_weight,calls_today,campaign_grade) values('$VD_login','$VD_campaign','0','0','$calls_today','$campaign_grade');";
				if ($DB) {echo "$stmt\n";}
				$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01043',$VD_login,$server_ip,$session_name,$one_mysql_log);}
				$affected_rows = mysqli_affected_rows($link);
				echo "<!-- new vicidial_campaign_agents record inserted: |$affected_rows| -->\n";
				}

			if ($auto_dial_level > 0)
				{
				echo "<!-- campaign is set to auto_dial_level: $auto_dial_level -->\n";

				$closer_chooser_string='';
				$stmt="INSERT INTO vicidial_live_agents (user,server_ip,conf_exten,extension,status,lead_id,campaign_id,uniqueid,callerid,channel,random_id,last_call_time,last_update_time,last_call_finish,closer_campaigns,user_level,campaign_weight,calls_today,last_state_change,outbound_autodial,manager_ingroup_set,on_hook_ring_time,on_hook_agent,last_inbound_call_time,last_inbound_call_finish,campaign_grade,pause_code,last_inbound_call_time_filtered,last_inbound_call_finish_filtered) values('$VD_login','$server_ip','$session_id','$SIP_user','READY','','$VD_campaign','','','','$random','$NOW_TIME','$tsNOW_TIME','$NOW_TIME','$closer_chooser_string','$user_level','$campaign_weight','$calls_today','$NOW_TIME','Y','N','$phone_ring_timeout','$on_hook_agent','$NOW_TIME','$NOW_TIME','$campaign_grade','LOGIN','$NOW_TIME','$NOW_TIME');";
				if ($DB) {echo "$stmt\n";}
				$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01044',$VD_login,$server_ip,$session_name,$one_mysql_log);}
				$affected_rows = mysqli_affected_rows($link);
				echo "<!-- new vicidial_live_agents record inserted: |$affected_rows| -->\n";

				if ($enable_queuemetrics_logging > 0)
					{
					$QM_LOGIN = 'AGENTLOGIN';
					$QM_PHONE = "$VD_login@agents";
					if ( ($queuemetrics_loginout=='CALLBACK') or ($queuemetrics_loginout=='NONE') )
						{
						$QM_LOGIN = 'AGENTCALLBACKLOGIN';
						$QM_PHONE = "$SIP_user_DiaL";
						}
					$linkB=mysqli_connect("$queuemetrics_server_ip", "$queuemetrics_login", "$queuemetrics_pass");
					if (!$linkB) {die(_QXZ("Could not connect: ")."$queuemetrics_server_ip|$queuemetrics_login" . mysqli_connect_error());}
					mysqli_select_db($linkB, "$queuemetrics_dbname");

					if ( ($queuemetrics_pe_phone_append > 0) and (strlen($qm_phone_environment)>0) )
						{$qm_phone_environment .= "-$qm_extension";}

					if ($queuemetrics_loginout!='NONE')
						{
						$stmt = "INSERT INTO queue_log SET `partition`='P01',time_id='$StarTtimE',call_id='NONE',queue='NONE',agent='Agent/$VD_login',verb='$QM_LOGIN',data1='$QM_PHONE',serverid='$queuemetrics_log_id',data4='$qm_phone_environment';";
						if ($DB) {echo "$stmt\n";}
						$rslt=mysql_to_mysqli($stmt, $linkB);
						if ($mel > 0) {mysql_error_logging($NOW_TIME,$linkB,$mel,$stmt,'01045',$VD_login,$server_ip,$session_name,$one_mysql_log);}
						$affected_rows = mysqli_affected_rows($linkB);
						echo "<!-- queue_log $QM_LOGIN entry added: $VD_login|$affected_rows|$QM_PHONE -->\n";
						}

					$pause_typeSQL='';
					if ($queuemetrics_pause_type > 0)
						{$pause_typeSQL=",data5='AGENT'";}
					$stmt = "INSERT INTO queue_log SET `partition`='P01',time_id='$StarTtimE',call_id='NONE',queue='NONE',agent='Agent/$VD_login',verb='PAUSEALL',serverid='$queuemetrics_log_id',data4='$qm_phone_environment' $pause_typeSQL;";
					if ($DB) {echo "$stmt\n";}
					$rslt=mysql_to_mysqli($stmt, $linkB);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$linkB,$mel,$stmt,'01046',$VD_login,$server_ip,$session_name,$one_mysql_log);}
					$affected_rows = mysqli_affected_rows($linkB);
					echo "<!-- queue_log PAUSE entry added: $VD_login|$affected_rows -->\n";

					if ($queuemetrics_addmember_enabled > 0)
						{
						$stmt = "INSERT INTO queue_log SET `partition`='P01',time_id='$StarTtimE',call_id='NONE',queue='$VD_campaign',agent='Agent/$VD_login',verb='ADDMEMBER2',data1='$QM_PHONE',serverid='$queuemetrics_log_id',data4='$qm_phone_environment';";
						if ($DB) {echo "$stmt\n";}
						$rslt=mysql_to_mysqli($stmt, $linkB);
					if ($mel > 0) {mysql_error_logging($NOW_TIME,$linkB,$mel,$stmt,'01069',$VD_login,$server_ip,$session_name,$one_mysql_log);}
						$affected_rows = mysqli_affected_rows($linkB);
						echo "<!-- queue_log ADDMEMBER2 entry added: $VD_login|$affected_rows -->\n";
						}
						

					mysqli_close($linkB);
					mysqli_select_db($link, "$VARDB_database");
					}


				if ( ($campaign_allow_inbound == 'Y') and ($dial_method != 'MANUAL') )
					{
					print "<!-- CLOSER-type campaign -->\n";
					}
				}
			else
				{
				print "<!-- campaign is set to manual dial: $auto_dial_level -->\n";

				$stmt="INSERT INTO vicidial_live_agents (user,server_ip,conf_exten,extension,status,lead_id,campaign_id,uniqueid,callerid,channel,random_id,last_call_time,last_update_time,last_call_finish,user_level,campaign_weight,calls_today,last_state_change,outbound_autodial,manager_ingroup_set,on_hook_ring_time,on_hook_agent,campaign_grade,last_inbound_call_time_filtered,last_inbound_call_finish_filtered) values('$VD_login','$server_ip','$session_id','$SIP_user','READY','','$VD_campaign','','','','$random','$NOW_TIME','$tsNOW_TIME','$NOW_TIME','$user_level', '$campaign_weight', '$calls_today','$NOW_TIME','N','N','$phone_ring_timeout','$on_hook_agent','$campaign_grade','$NOW_TIME','$NOW_TIME');";
				if ($DB) {echo "$stmt\n";}
				$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01047',$VD_login,$server_ip,$session_name,$one_mysql_log);}
				$affected_rows = mysqli_affected_rows($link);
				echo "<!-- new vicidial_live_agents record inserted: |$affected_rows| -->\n";

				if ($enable_queuemetrics_logging > 0)
					{
					$QM_LOGIN = 'AGENTLOGIN';
					$QM_PHONE = "$VD_login@agents";
					if ( ($queuemetrics_loginout=='CALLBACK') or ($queuemetrics_loginout=='NONE') )
						{
						$QM_LOGIN = 'AGENTCALLBACKLOGIN';
						$QM_PHONE = "$SIP_user_DiaL";
						}
					$linkB=mysqli_connect("$queuemetrics_server_ip", "$queuemetrics_login", "$queuemetrics_pass");
					if (!$linkB) {die(_QXZ("Could not connect: ")."$queuemetrics_server_ip|$queuemetrics_login" . mysqli_connect_error());}
					mysqli_select_db($linkB, "$queuemetrics_dbname");

					if ($queuemetrics_loginout!='NONE')
						{
						$stmt = "INSERT INTO queue_log SET `partition`='P01',time_id='$StarTtimE',call_id='NONE',queue='$VD_campaign',agent='Agent/$VD_login',verb='$QM_LOGIN',data1='$QM_PHONE',serverid='$queuemetrics_log_id',data4='$qm_phone_environment';";
						if ($DB) {echo "$stmt\n";}
						$rslt=mysql_to_mysqli($stmt, $linkB);
						if ($mel > 0) {mysql_error_logging($NOW_TIME,$linkB,$mel,$stmt,'01048',$VD_login,$server_ip,$session_name,$one_mysql_log);}
						$affected_rows = mysqli_affected_rows($linkB);
						echo "<!-- queue_log $QM_LOGIN entry added: $VD_login|$affected_rows|$QM_PHONE -->\n";
						}

					$pause_typeSQL='';
					if ($queuemetrics_pause_type > 0)
						{$pause_typeSQL=",data5='AGENT'";}
					$stmt = "INSERT INTO queue_log SET `partition`='P01',time_id='$StarTtimE',call_id='NONE',queue='NONE',agent='Agent/$VD_login',verb='PAUSEALL',serverid='$queuemetrics_log_id',data4='$qm_phone_environment' $pause_typeSQL;";
					if ($DB) {echo "$stmt\n";}
					$rslt=mysql_to_mysqli($stmt, $linkB);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$linkB,$mel,$stmt,'01049',$VD_login,$server_ip,$session_name,$one_mysql_log);}
					$affected_rows = mysqli_affected_rows($linkB);
					echo "<!-- queue_log PAUSE entry added: $VD_login|$affected_rows -->\n";

					if ($queuemetrics_addmember_enabled > 0)
						{
						$stmt = "INSERT INTO queue_log SET `partition`='P01',time_id='$StarTtimE',call_id='NONE',queue='$VD_campaign',agent='Agent/$VD_login',verb='ADDMEMBER2',data1='$QM_PHONE',serverid='$queuemetrics_log_id',data4='$qm_phone_environment';";
						if ($DB) {echo "$stmt\n";}
						$rslt=mysql_to_mysqli($stmt, $linkB);
					if ($mel > 0) {mysql_error_logging($NOW_TIME,$linkB,$mel,$stmt,'01072',$VD_login,$server_ip,$session_name,$one_mysql_log);}
						$affected_rows = mysqli_affected_rows($linkB);
						echo "<!-- queue_log ADDMEMBER2 entry added: $VD_login|$affected_rows -->\n";
						}

					mysqli_close($linkB);
					mysqli_select_db($link, "$VARDB_database");
					}
				}
			}
		else
			{
			echo "<title>"._QXZ("Agent web client: Campaign Login")."</title>\n";
			echo "</head>\n";
            echo "<body onresize=\"browser_dimensions();\" onload=\"browser_dimensions();\">\n";
			if ($hide_timeclock_link < 1)
                {echo "<a href=\"./timeclock.php?referrer=agent&amp;pl=$phone_login&amp;pp=$phone_pass&amp;VD_login=$VD_login&amp;VD_pass=$VD_pass\"> <font class=\"sb_text\">"._QXZ("Timeclock")."</font></a>$grey_link<br />\n";}
            echo "<table width=\"100%\"><tr><td></td>\n";
			echo "<!-- INTERNATIONALIZATION-LINKS-PLACEHOLDER-VICIDIAL -->\n";
            echo "</tr></table>\n";
            echo "<b><font class=\"skb_text\">"._QXZ("Sorry, there are no leads in the hopper for this campaign")."</b>\n";
            echo "<form action=\"$PHP_SELF\" method=\"post\">\n";
            echo "<input type=\"hidden\" name=\"DB\" value=\"$DB\" />\n";
            echo "<input type=\"hidden\" name=\"JS_browser_height\" id=\"JS_browser_height\" value=\"\" />\n";
            echo "<input type=\"hidden\" name=\"JS_browser_width\" id=\"JS_browser_width\" value=\"\" />\n";
            echo "<input type=\"hidden\" name=\"phone_login\" value=\"$phone_login\" />\n";
            echo "<input type=\"hidden\" name=\"phone_pass\" value=\"$phone_pass\" />\n";
			echo "<input type=\"hidden\" name=\"LOGINvarONE\" id=\"LOGINvarONE\" value=\"$LOGINvarONE\" />\n";
			echo "<input type=\"hidden\" name=\"LOGINvarTWO\" id=\"LOGINvarTWO\" value=\"$LOGINvarTWO\" />\n";
			echo "<input type=\"hidden\" name=\"LOGINvarTHREE\" id=\"LOGINvarTHREE\" value=\"$LOGINvarTHREE\" />\n";
			echo "<input type=\"hidden\" name=\"LOGINvarFOUR\" id=\"LOGINvarFOUR\" value=\"$LOGINvarFOUR\" />\n";
			echo "<input type=\"hidden\" name=\"LOGINvarFIVE\" id=\"LOGINvarFIVE\" value=\"$LOGINvarFIVE\" />\n";
            echo "<font class=\"skb_text\">"._QXZ("Login:")." <input type=\"text\" name=\"VD_login\" size=\"10\" maxlength=\"20\" value=\"$VD_login\" />\n<br />";
            echo "<font class=\"skb_text\">"._QXZ("Password:")." <input type=\"password\" name=\"VD_pass\" size=\"10\" maxlength=\"20\" value=\"$VD_pass\" /><br />\n";
            echo "<font class=\"skb_text\">"._QXZ("Campaign:")." <span id=\"LogiNCamPaigns\">$camp_form_code</span><br />\n";
            echo "<input type=\"submit\" name=\"SUBMIT\" value=\""._QXZ("SUBMIT")."\" /> &nbsp; \n";
			echo "<span id=\"LogiNReseT\"></span>\n";
            echo "</form>\n\n";
			echo "</body>\n\n";
			echo "</html>\n\n";
			exit;
			}
		if (strlen($session_id) < 1)
			{
			echo "<title>"._QXZ("Agent web client: Campaign Login")."</title>\n";
			echo "</head>\n";
            echo "<body onresize=\"browser_dimensions();\" onload=\"browser_dimensions();\">\n";
			if ($hide_timeclock_link < 1)
                {echo "<a href=\"./timeclock.php?referrer=agent&amp;pl=$phone_login&amp;pp=$phone_pass&amp;VD_login=$VD_login&amp;VD_pass=$VD_pass\"> <font class=\"sb_text\">"._QXZ("Timeclock")."</font></a>$grey_link<br />\n";}
            echo "<table width=\"100%\"><tr><td></td>\n";
			echo "<!-- INTERNATIONALIZATION-LINKS-PLACEHOLDER-VICIDIAL -->\n";
            echo "</tr></table>\n";
            echo "<b><font class=\"skb_text\">"._QXZ("Sorry, there are no available sessions")."</b>: |$session_id|$server_ip|$extension|$SIP_user|\n";
            echo "<form action=\"$PHP_SELF\" method=\"post\" />\n";
            echo "<input type=\"hidden\" name=\"DB\" value=\"$DB\" />\n";
            echo "<input type=\"hidden\" name=\"JS_browser_height\" id=\"JS_browser_height\" value=\"\" />\n";
            echo "<input type=\"hidden\" name=\"JS_browser_width\" id=\"JS_browser_width\" value=\"\" />\n";
            echo "<input type=\"hidden\" name=\"phone_login\" value=\"$phone_login\" />\n";
            echo "<input type=\"hidden\" name=\"phone_pass\" value=\"$phone_pass\" />\n";
			echo "<input type=\"hidden\" name=\"LOGINvarONE\" id=\"LOGINvarONE\" value=\"$LOGINvarONE\" />\n";
			echo "<input type=\"hidden\" name=\"LOGINvarTWO\" id=\"LOGINvarTWO\" value=\"$LOGINvarTWO\" />\n";
			echo "<input type=\"hidden\" name=\"LOGINvarTHREE\" id=\"LOGINvarTHREE\" value=\"$LOGINvarTHREE\" />\n";
			echo "<input type=\"hidden\" name=\"LOGINvarFOUR\" id=\"LOGINvarFOUR\" value=\"$LOGINvarFOUR\" />\n";
			echo "<input type=\"hidden\" name=\"LOGINvarFIVE\" id=\"LOGINvarFIVE\" value=\"$LOGINvarFIVE\" />\n";
            echo "<font class=\"skb_text\">"._QXZ("Login:")." <input type=\"text\" name=\"VD_login\" size=\"10\" maxlength=\"20\" value=\"$VD_login\" />\n<br />";
            echo "<font class=\"skb_text\">"._QXZ("Password:")." <input type=\"password\" name=\"VD_pass\" size=\"10\" maxlength=\"20\" value=\"$VD_pass\" /><br />\n";
            echo "<font class=\"skb_text\">"._QXZ("Campaign:")." <span id=\"LogiNCamPaigns\">$camp_form_code</span><br />\n";
            echo "<input type=\"submit\" name=\"SUBMIT\" value=\""._QXZ("SUBMIT")."\" /> &nbsp; \n";
			echo "<span id=\"LogiNReseT\"></span>\n";
			echo "</FORM>\n\n";
			echo "</body>\n\n";
			echo "</html>\n\n";
			exit;
			}

		if (preg_match('/MSIE/',$browser)) 
			{
			$useIE=1;
			echo "<!-- client web browser used: MSIE |$browser|$useIE| -->\n";
			}
		else 
			{
			$useIE=0;
			echo "<!-- client web browser used: W3C-Compliant |$browser|$useIE| -->\n";
			}

		$StarTtimE = date("U");
		$NOW_TIME = date("Y-m-d H:i:s");
		##### Agent is going to log in so insert the vicidial_agent_log entry now
		$stmt="INSERT INTO vicidial_agent_log (user,server_ip,event_time,campaign_id,pause_epoch,pause_sec,wait_epoch,user_group,sub_status,pause_type) values('$VD_login','$server_ip','$NOW_TIME','$VD_campaign','$StarTtimE','0','$StarTtimE','$VU_user_group','LOGIN','AGENT');";
		if ($DB) {echo "$stmt\n";}
		$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01050',$VD_login,$server_ip,$session_name,$one_mysql_log);}
		$affected_rows = mysqli_affected_rows($link);
		$agent_log_id = mysqli_insert_id($link);
		echo "<!-- vicidial_agent_log record inserted: |$affected_rows|$agent_log_id| -->\n";

		##### update vicidial_campaigns to show agent has logged in
		$stmt="UPDATE vicidial_campaigns set campaign_logindate='$NOW_TIME' where campaign_id='$VD_campaign';";
		if ($DB) {echo "$stmt\n";}
		$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01064',$VD_login,$server_ip,$session_name,$one_mysql_log);}
		$VCaffected_rows = mysqli_affected_rows($link);
		echo "<!-- vicidial_campaigns campaign_logindate updated: |$VCaffected_rows|$NOW_TIME| -->\n";

		if ($enable_queuemetrics_logging > 0)
			{
			$StarTtimEpause = ($StarTtimE + 1);
			$linkB=mysqli_connect("$queuemetrics_server_ip", "$queuemetrics_login", "$queuemetrics_pass");
			if (!$linkB) {die(_QXZ("Could not connect: ")."$queuemetrics_server_ip|$queuemetrics_login" . mysqli_connect_error());}
			mysqli_select_db($linkB, "$queuemetrics_dbname");

			$pause_typeSQL='';
			if ($queuemetrics_pause_type > 0)
				{$pause_typeSQL=",data5='AGENT'";}

			$stmt = "INSERT INTO queue_log SET `partition`='P01',time_id='$StarTtimEpause',call_id='NONE',queue='NONE',agent='Agent/$VD_login',verb='PAUSEREASON',data1='LOGIN',data3='$QM_PHONE',serverid='$queuemetrics_log_id'$pause_typeSQL;";
			if ($DB) {echo "$stmt\n";}
			$rslt=mysql_to_mysqli($stmt, $linkB);
		if ($mel > 0) {mysql_error_logging($NOW_TIME,$linkB,$mel,$stmt,'01063',$VD_login,$server_ip,$session_name,$one_mysql_log);}
			$affected_rows = mysqli_affected_rows($linkB);
			echo "<!-- queue_log PAUSEREASON LOGIN entry added: $VD_login|$affected_rows|$QM_PHONE -->\n";

			mysqli_close($linkB);
			mysqli_select_db($link, "$VARDB_database");
			}

		$stmt="UPDATE vicidial_live_agents SET agent_log_id='$agent_log_id' where user='$VD_login';";
		if ($DB) {echo "$stmt\n";}
		$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01061',$VD_login,$server_ip,$session_name,$one_mysql_log);}
		$VLAaffected_rows_update = mysqli_affected_rows($link);

		$stmt="UPDATE vicidial_users SET shift_override_flag='0' where user='$VD_login' and shift_override_flag='1';";
		if ($DB) {echo "$stmt\n";}
		$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01057',$VD_login,$server_ip,$session_name,$one_mysql_log);}
		$VUaffected_rows = mysqli_affected_rows($link);

		$S='*';
		$D_s_ip = explode('.', $server_ip);
		if (strlen($D_s_ip[0])<2) {$D_s_ip[0] = "0$D_s_ip[0]";}
		if (strlen($D_s_ip[0])<3) {$D_s_ip[0] = "0$D_s_ip[0]";}
		if (strlen($D_s_ip[1])<2) {$D_s_ip[1] = "0$D_s_ip[1]";}
		if (strlen($D_s_ip[1])<3) {$D_s_ip[1] = "0$D_s_ip[1]";}
		if (strlen($D_s_ip[2])<2) {$D_s_ip[2] = "0$D_s_ip[2]";}
		if (strlen($D_s_ip[2])<3) {$D_s_ip[2] = "0$D_s_ip[2]";}
		if (strlen($D_s_ip[3])<2) {$D_s_ip[3] = "0$D_s_ip[3]";}
		if (strlen($D_s_ip[3])<3) {$D_s_ip[3] = "0$D_s_ip[3]";}
		$server_ip_dialstring = "$D_s_ip[0]$S$D_s_ip[1]$S$D_s_ip[2]$S$D_s_ip[3]$S";

		##### grab the datails of all active scripts in the system
		$stmt="SELECT script_id,script_name FROM vicidial_scripts WHERE active='Y' order by script_id limit 1000;";
		$rslt=mysql_to_mysqli($stmt, $link);
				if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01051',$VD_login,$server_ip,$session_name,$one_mysql_log);}
		if ($DB) {echo "$stmt\n";}
		$MM_scripts = mysqli_num_rows($rslt);
		$e=0;
		while ($e < $MM_scripts)
			{
			$row=mysqli_fetch_row($rslt);
			$MMscriptid[$e] =$row[0];
			$MMscriptname[$e] = urlencode($row[1]);
			$MMscriptids = "$MMscriptids'$MMscriptid[$e]',";
			$MMscriptnames = "$MMscriptnames'$MMscriptname[$e]',";
			$e++;
			}
		$MMscriptids = substr("$MMscriptids", 0, -1); 
		$MMscriptnames = substr("$MMscriptnames", 0, -1); 


		##### BEGIN vicidial_list FIELD LENGTH LOOKUP #####
		$MAXvendor_lead_code =		'20';
		$MAXphone_code =			'10';
		$MAXphone_number =			'18';
		$MAXtitle =					'4';
		$MAXfirst_name =			'30';
		$MAXmiddle_initial =		'1';
		$MAXlast_name =				'30';
		$MAXaddress1 =				'100';
		$MAXaddress2 =				'100';
		$MAXaddress3 =				'100';
		$MAXcity =					'50';
		$MAXstate =					'2';
		$MAXprovince =				'50';
		$MAXpostal_code =			'10';
		$MAXalt_phone =				'12';
		$MAXemail =					'70';
		$MAXsecurity_phrase =		'100';

		$stmt = "SHOW COLUMNS FROM vicidial_list;";
		$rslt=mysql_to_mysqli($stmt, $link);
			if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01087',$VD_login,$server_ip,$session_name,$one_mysql_log);}
		if ($DB) {echo "$stmt\n";}
		$scvl_ct = mysqli_num_rows($rslt);
		$s=0;
		while ($scvl_ct > $s)
			{
			$row=mysqli_fetch_row($rslt);
			$vl_field =	$row[0];
			$vl_type = preg_replace("/[^0-9]/",'',$row[1]);
			if (strlen($vl_type) > 0)
				{
				if ( ($vl_field == 'vendor_lead_code') and ($MAXvendor_lead_code != $vl_type) )
					{$MAXvendor_lead_code = $vl_type;}
				if ( ($vl_field == 'phone_code') and ($MAXphone_code != $vl_type) )
					{$MAXphone_code = $vl_type;}
				if ( ($vl_field == 'phone_number') and ($MAXphone_number != $vl_type) )
					{$MAXphone_number = $vl_type;}
				if ( ($vl_field == 'title') and ($MAXtitle != $vl_type) )
					{$MAXtitle = $vl_type;}
				if ( ($vl_field == 'first_name') and ($MAXfirst_name != $vl_type) )
					{$MAXfirst_name = $vl_type;}
				if ( ($vl_field == 'middle_initial') and ($MAXmiddle_initial != $vl_type) )
					{$MAXmiddle_initial = $vl_type;}
				if ( ($vl_field == 'last_name') and ($MAXlast_name != $vl_type) )
					{$MAXlast_name = $vl_type;}
				if ( ($vl_field == 'address1') and ($MAXaddress1 != $vl_type) )
					{$MAXaddress1 = $vl_type;}
				if ( ($vl_field == 'address2') and ($MAXaddress2 != $vl_type) )
					{$MAXaddress2 = $vl_type;}
				if ( ($vl_field == 'address3') and ($MAXaddress3 != $vl_type) )
					{$MAXaddress3 = $vl_type;}
				if ( ($vl_field == 'city') and ($MAXcity != $vl_type) )
					{$MAXcity = $vl_type;}
				if ( ($vl_field == 'state') and ($MAXstate != $vl_type) )
					{$MAXstate = $vl_type;}
				if ( ($vl_field == 'province') and ($MAXprovince != $vl_type) )
					{$MAXprovince = $vl_type;}
				if ( ($vl_field == 'postal_code') and ($MAXpostal_code != $vl_type) )
					{$MAXpostal_code = $vl_type;}
				if ( ($vl_field == 'alt_phone') and ($MAXalt_phone != $vl_type) )
					{$MAXalt_phone = $vl_type;}
				if ( ($vl_field == 'email') and ($MAXemail != $vl_type) )
					{$MAXemail = $vl_type;}
				if ( ($vl_field == 'security_phrase') and ($MAXsecurity_phrase != $vl_type) )
					{$MAXsecurity_phrase = $vl_type;}
				}
			$s++;
			}
		##### END vicidial_list FIELD LENGTH LOOKUP #####
		}
		
		function is_xlite_login($VD_login){
			exec('asterisk -r "sip show peers"',$data);
			$total_count = count($data);
			for($i=1;$i<($total_count-1);$i++){
				$user = explode(" ", $data[$i]);
				if($user[0] == $VD_login.'/'.$VD_login){
					if($user[17]=="(Unspecified)"){
						return 0;
					}
					else{
						return 1;
					}
				}
			}
		}