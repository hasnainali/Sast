<link rel="stylesheet" type="text/css" href="./css/vicidial_stylesheet.css">
<!-- css for [kpticket type dropdown -->
<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
?>
<style type="text/css">
  body{
    overflow:hidden;
  }
  .header-pers-detail .font-weight-bold2{
    font-weight: bold !important;
    font-size: 12px;
    padding: 10px;
    line-height: 1.5;
    display: inline;
    margin-right: 8px !important;
  }
  td.table-dash-css.btn-margin font {
    margin: 0px !important;
  }
  th.logo-css {
    position: relative;
    height: 85px;
    width: 18%;
  }
  th.logo-css img {
    position: absolute;
    top: 8px;
    left: 15px;
  }
  /*td.header-pers-detail br {
    display: none;
  }*/

  .purple-bg {
    color: #872f6f !important;
    border: 1px solid #872f6f;
  }
  .blue-bg{
    /*background: #036ac1;*/
    color: #036ac1 !important;
    border-right: 2px solid #036ac1;
  }
  .green-bg{
    /*background: #00825e;*/
    color: #00825e !important;
    border: 1px solid #00825e;
  }
  tr.bottom-header td {
    padding-right: 6px;
  }
  #Tabs font {
      margin-right: 0%;
      cursor: pointer;
  }
  #searchby {
    padding: 5px;
    
  }
  
  #MainTable tr, td {
    padding: .5% .5% 0px .5%;
  }

 /*.logout-btn{
    position: relative;
    right: 8px;
    top: 7px;
 }*/
 td.header-pers-detail {
    height: 50px;
    padding: 0px;
 }
 .width-css{
    width: 15%;
 }
  #dashboard {
    width: unset;
  }
  
   @media screen and (max-width: 1024px) {
    table#Tabs td {
      display: block;
      width: 100%;
      border-left: 0px !important;
      padding: 2px 15px;
    }
    .width-name-css {
      width: 100% !important;
    }
    td.width-css img {
      float: left;
      padding-right: 10px !important;
    }
    .logout-btn {
      position: relative;
      right: unset;
      top: 0px;
      left: 0px;
      float: left;
    }

  }
</style>
</head>
<?php

$zi=2;

?>

<body onload="begin_all_refresh();show();"  onunload="BrowserCloseLogout();">
  <form name=vicidial_form id=vicidial_form onsubmit="return false;">
    <span style="position:absolute;left:0px;top:0px;z-index:300;width: 100%; height: 100vh;" id="LoadingBox">
      <table border="0" bgcolor="white" width="<?php echo $JS_browser_width ?>px" height="100%">
        <tr>
          <td align="center" valign="top">
            <font class="loading_text"><?php echo _QXZ("Loading..."); ?></font>
            <img src="./images/<?php echo _QXZ("agent_loading_animation.gif"); ?>" height="206px" width="206px" alt="<?php echo _QXZ("Loading..."); ?>" />
          </td>
        </tr>
      </table>
    </span>

    <!-- ZZZZZZZZZZZZ  header -->
    <span style="z-index:<?php $zi++; echo $zi ?>;" id="Header">
        <table border="0" cellpadding="10" cellspacing="0" bgcolor="#fff" width="<?php echo $MNwidth ?>px" marginwidth="0" marginheight="0" leftmargin="0" topmargin="0" valign="top" align="left" id="Header" style=" color: #333;">
          <tr valign="top" align="left">
            <td colspan="3" valign="top" align="left" style="padding:0px;">
              
            </td>
            <td colspan="3" valign="top" align="right" style="padding:0px;">
              
            </td>
          </tr>
        </table>
    </span>

    <!-- ZZZZZZZZZZZZ  tabs  -->


    <!--tekdial-side-panel-->
    <span class="tekdial-sidepanel-overlay"></span>
    <div class="tekdial-sidepanel">
      <span class="tekdial-sidepanel-close"><img src="https://tcob.vyapaar-vistaar.in/img/mtz/cross.svg"></span>
      <img src="./images/tekDial_logo.gif" alt="MAIN" border="0" style="width:100%;">
      <div class="agent-name-campaign text-center">
        <p><?php echo $VD_login ?> | <?php  echo $VUuser_full_name ?></p>
   
   <!--- Comment on 20 jun 2022 due to show by default RURAL if campaign page not found by ajay after said by hasnain sir-------> 
   
   


        <p><?php //echo $VD_campaign ?></p>
        <p>RURAL</p>
      </div>
      <!-- <div class="tekdial-status">
        <span id="breakStatus">Status: <abbr id="break-status"></abbr></span>
      </div> -->
      <div class="tekdial-buttons">
        <span id="myBtn"><button type="button">Break</button></span>
        <span><button type="button" style="display:none" id="endbreak">End Break</button></span>
        <span><button type="button" onclick="makeCall()">Make Call</button></span>

        <span id="HangupControl"><button type="button">Release</button></span>

        <span id="ParkControl">
          <button type="button">Hold</button>
          <span id="ivrParkControl" style="display: none;"></span>
        </span>
        <span id="ParkCounterSpan"></span>
        <span id="XferControl"><button type="button">Consult</button></span>

        <!-- <span><button type="button" class="dial-nextBtn" onclick="ManualDialNext('','','','','','0','','','YES');">Dial Next</button></span> -->
        <!-- <span id="listPreview"><button type="button" class="list-previewBtn" onclick="preview_dial_html();">List Preview</button></span> -->
        <input type="hidden" name="extension" id="extension">
        <input type="hidden" name="custom_field_values" id="custom_field_values" value="">
        <input type="hidden" name="FORM_LOADED" id="FORM_LOADED" value="0">
        <font color="#872f6f" class="font-weight-bold2 f">
          <span id="agentchannelSPAN"></span>   
        </font>
        <input type="hidden" id="breakid" name="break_id">
          
      </div>
      <div class="tekdial-side-footer">
        <abbr>
          <a id="dashboard" class="agentTicketCount" agent="<?php echo $VD_login ?>"><img src="./images/clock.png" style="width:20px;"></a>
          <!-- <a id="refresh_search"><img src="./images/refresh-new.png" style="width:20px;"></a> -->
          <span class="logout-btn">
            <a onclick="NormalLogout();return false;needToConfirmExit = false;">
              <img src="./images/logout.png" style="width:20px;">
            </a>
          </span>
        </abbr>
        <h4>Copyright © 2021 <br> <a href="https://vyapaar-vistaar.in/" target="_blank">vyapaar-vistaar Technologies Pvt. Ltd.</a></h4>
      </div>
    </div>
    <!--/tekdial-side-panel-->


    <!--tekdial-top-panel-->
    <div class="body-content">
      <span style="position:fixed;top:-3px;left:0px;width:100%;background:#fff;box-shadow:0px 0px 7px rgb(0 0 0 / 40%);" id="Tabs">
        <table border="0" width="<?php echo $MNwidth ?>px" id="Tabs" cellspacing="0">
          <tr style="padding:4px 10px;display:flex;justify-content:space-between;align-items:center;"> 
            <td style="padding:0px;">
              <a class="clickOpenTekdialSidebar"><img src="./images/tekDial-icon.png" width="32" /></a>
            </td>
            <td style="padding:0px;">
                <font class="body_text" style="font-weight:600;">
                  <?php echo _QXZ("Agent ID:"); ?> 
                  <span style="font-weight:500;"><?php echo $VD_login ?></span>
                </font>                 
              </td>
            <td style="padding:0px;display:none;">
              <span id="ReQueueCall"></span>
            </td>
            <td style="padding:0px;">
              <?php if ($webphone_location == 'bar')
                {
                  echo "<img src=\"./images/"._QXZ("pixel.gif")."\" width=\"1px\" height=\"".$webphone_height."px\" /><br />\n";
                }
                $alt_phone_selected='';
                if ( ($alt_number_dialing=='SELECTED') or ($alt_number_dialing=='SELECTED_TIMER_ALT') or ($alt_number_dialing=='SELECTED_TIMER_ADDR3') )
                  {$alt_phone_selected='CHECKED';}
              ?>
              <span id="post_phone_time_diff_span">
                <b><font color="red"><span id="post_phone_time_diff_span_contents"></span></font></b>
              </span>
              <font class="body_text"> 
                <span id="breakStatus">Status: <span id="break-status"></span></span>
              </font>
            </td>
              <?php if ($SSenable_second_script > 0)
                  {echo "<td align=\"left\" width=\"67px\"><a href=\"#\" onclick=\"ScriptPanel2ToFront('YES');\"><img src=\"./images/"._QXZ("vdc_tab_script2.gif")."\" alt=\"SCRIPT 2\" width=\"67px\" height=\"30px\" border=\"0\" /></a></td>\n";}
              ?>
              <?php if ($custom_fields_enabled > 0)
                  {echo "<td align=\"left\" width=\"67px\"><a href=\"#\" onclick=\"FormPanelToFront('YES');\"><img src=\"./images/"._QXZ("vdc_tab_form.gif")."\" alt=\"FORM\" width=\"67px\" height=\"30px\" border=\"0\" /></a></td>\n";}
              ?>
              <?php if ($email_enabled > 0)
                  {echo "<td align=\"left\" width=\"67px\"><a href=\"#\" onclick=\"EmailPanelToFront('YES');\"><img src=\"./images/"._QXZ("vdc_tab_email.gif")."\" alt=\"EMAIL\" width=\"67px\" height=\"30px\" border=\"0\" /></a></td>\n";}
              ?>
              <?php if ($chat_enabled > 0)
                  {
                # INTERNAL CHAT
                echo "<td align=\"left\" width=\"67px\"><a href=\"#\" onclick=\"InternalChatContentsLoad('YES');\"><img src=\"./images/"._QXZ("vdc_tab_chat_internal.gif")."\" name='InternalChatImg' alt=\"CHAT\" width=\"67px\" height=\"30px\" border=\"0\"/></a></td>\n";

                # CUSTOMER CHAT
                echo "<td align=\"left\" width=\"67px\"><a href=\"#\" onclick=\"CustomerChatPanelToFront('1', 'YES');\"><img src=\"./images/"._QXZ("vdc_tab_chat_customer.gif")."\" name='CustomerChatImg' alt=\"CHAT\" width=\"67px\" height=\"30px\" border=\"0\"/></a></td>\n";
                }
              ?>
            <td style="padding:0px;">
              <span style="z-index:<?php $zi++; echo $zi ?>;" id="SecondSspan">
                <div class="body_text" style="font-weight:600;">Start Time: <span id="display" style="font-weight:500;"></span></div>
                  <input style="display: none;" type="button" value="start" onclick="start();">
                  <input style="display: none;" type="button" value="stop" onclick="stop();">
                    <input style="display: none;" type="button" value="reset" onclick="reset()"> 
               <!--  <font class="body_text"> <?php echo _QXZ("Start Time:"); ?> 
                 <span id="display">
                      00:00:00
                  </span>         -->       
                <!-- </font> -->
              </span>
            </td>
            <td style="padding:0px;">
              <font class="body_tiny" style="display: none;">
                <span id="status"><?php echo _QXZ("LIVE"); ?></span><?php echo _QXZ("session ID:"); ?> <span id="sessionIDspan"></span>
              </font>
              <font class="body_text">
                <span id="AgentStatusCalls"></span>
                <span id="AgentStatusEmails"></span>
              </font>
            </td> 
            <span class="text_input" id="MainPanelCustInfo">
              <td style="padding:0px;">
                <font class="body_text">
                  <?php echo _QXZ("Customer No:"); ?> 
                  <span id="phone_numberDISP"></span>
                </font>                 
              </td>
              <td style="padding:0px;">
                <font class="body_text" style="font-weight:600;">
                  <?php echo _QXZ("Skill:"); ?> 
                  <span id="ag_skills" style="font-weight:500;"><?php echo $agent_skill; ?></span>
                </font>
              </td>
              <!-- <td style="padding:0px;">
                <font class="body_text" style="font-weight:600;">
                  <//?php echo _QXZ("Skill:"); ?> 
                  <span id="skills" style="font-weight:500;"></span>
                </font>                 
              </td> -->
              <!-- <td style="padding:0px;">
                <font class="body_text">
                  Customer No: 
                  <span id="phonenumber" style="font-weight:500;"></span>
                </font>                 
              </td> -->
              <!-- <td style="padding:0px;">
                <font class="body_text">
                  Caller Name: 
                  <span id="callerName" style="font-weight:500;"></span>
                </font>                 
              </td> -->

              <td style="margin-top:-3px;"><font id="CusTInfOSpaN"></td>

             <td style="padding:0px;">
                <font class="body_text" style="display: none;">
                  <?php echo _QXZ("Channel:"); ?> 
                  <span name="callchannel" id="callchannel" class="cust_form"> </span>
                  <span id="MainStatuSSpan"></span>
                  <span id=timer_alt_display></span>
                  <span id=manual_auto_next_display></span>
                </font>
              </td>     
            </span>
            <td style="padding:0px;">
              <img src="./images/agc_live_call_OFF.gif" name="livecall" alt="Live Call" border="0" width="90">
            </td>
            <!-- <td style="padding:0px;display:flex;">
              <span id="dashboard" class="logout-btn agentTicketCount" agent="<?php echo $VD_login;?>">
                <img src="./images/clock.png" style="width:20px;object-fit:scale-down;">
              </span>
              <span id="refresh_search" style="margin:0px 5px 0px 10px;">
                <img src="./images/refresh-new.png" style="width:20px;object-fit:scale-down;">
              </span>
              <span class="logout-btn">
                <a href="#" onclick="NormalLogout();return false;needToConfirmExit = false;">
                  <img src="./images/logout.png" style="width:20px;object-fit:scale-down;">
                </a>
              </span>
            </td> -->
          </tr>
        </table>
      </span>
    </div>
    <!--/tekdial-top-panel-->
 
    <!--not-connected-call-summary-->

    <?php include('tc_performance.php'); ?>

    <script>
      $(document).ready(function(){
        setInterval(function(){
          let getBreakStatus = document.getElementById("break-status").innerHTML;
          
          if(getBreakStatus == "Idle"){
            document.getElementById("onNotConnectedCall").style.display='flex'; 
          }else{
            document.getElementById("onNotConnectedCall").style.display='none'; 
          }
        },1000);
      });
    </script>
    <!--/not-connected-call-summary-->

    <style>
      iframe{
        width: 100%;
        height: calc(100vh - 42px);
        margin: 36px 0px 0px;
      }
    </style>
    <span id="open_iframe"></span>

    <table style="background:#fff">
      <tr>
          <!-- <span id="dashboard" class="agentTicketCount" agent="<?php echo $VD_login;?>"> -->
        <!-- <td>
            <img src="./images/dashboard-btn-icon-new.gif" width="35">
            <span style="padding-left: 3%;font-size: 14px;font-weight: 600;color: #333;">Dashboard</span>
          </span>
        </td> -->
        <td style="padding:0px;">
          <span style="position:relative;float:right;right:11px;display: none; z-index:<?php $zi++; echo $zi ?>;" id="AgentMuteSpan"></span>
        </td>
      </tr>
    </table>
    
    <!-- <button id="dashboard">Dashboard</button> -->
    <span style="position:absolute;left:0px;top:0px;z-index:<?php $zi++; echo $zi ?>;" id="WelcomeBoxA">
        <table border="0" bgcolor="#FFFFFF" width="<?php echo $CAwidth ?>px" height="<?php echo $HKwidth ?>px">
          <tr>
            <td align="center">
              <span id="WelcomeBoxAt"><?php echo _QXZ("Agent Screen"); ?></span>
            </td>
          </tr>
        </table>
    </span>


    <!-- BEGIN *********   Here is the main VICIDIAL display panel -->
    <!-- <span style="z-index:<//?php $zi++; echo $zi ?>;" id="MainPanel"> -->
    <span id="MainPanel1" style="display:none;">
        <table style="border-spacing:0;" bgcolor="<?php echo $MAIN_COLOR ?>" width="<?php echo $MNwidth ?>px" id="MainTable">
                    
          <tr>
            <!-- bgcolor="#edf5ff" -->
            
            <td colspan="8" width="<?php echo $SDwidth ?>px" align="left" valign="top">
              <input type="hidden" name="lead_id" id="lead_id" value="" />
              <input type="hidden" name="list_id" id="list_id" value="" />
              <input type="hidden" name="entry_list_id" id="entry_list_id" value="" />
              <input type="hidden" name="list_name" id="list_name" value="" />
              <input type="hidden" name="list_description" id="list_description" value="" />
              <input type="hidden" name="called_count" id="called_count" value="" />
              <input type="hidden" name="rank" id="rank" value="" />
              <input type="hidden" name="owner" id="owner" value="" />
              <input type="hidden" name="gmt_offset_now" id="gmt_offset_now" value="" />
              <input type="hidden" name="gender" id="gender" value="" />
              <input type="hidden" name="date_of_birth" id="date_of_birth" value="" />
              <input type="hidden" name="country_code" id="country_code" value="" />
              <input type="hidden" name="uniqueid" id="uniqueid" value="" />
              <input type="hidden" name="callserverip" id="callserverip" value="" />
              <input type="hidden" name="SecondS" id="SecondS" value="" />
              <input type="hidden" name="email_row_id" id="email_row_id" value="" />
              <input type="hidden" name="chat_id" id="chat_id" value="" />
              <input type="hidden" name="customer_chat_id" id="customer_chat_id" value="" />
          </td>
          <!-- ZZZZZZZZZZZZ  customer info -->
          </tr>
          <tr>
            
          </tr>
          <tr>
          <!--============================= Customer Form Start Here   ============================== -->
          <table border="0" cellpadding="2" cellspacing="0"  style="background-color:#FFFFF7; display: none; padding-bottom:.5%;" id="customer_form" >
              <input type="hidden" name="callStatus" id="callStatus" value="">
              <tr>
                <!-- <td colspan="8" align="center" class="table_heading"> <font class="body_text"><?php echo _QXZ("Customer Information:"); ?></font> <span id="CusTInfOSpaN"></span> -->
                <td colspan="8" align="center">
                  <span id="CusTInfOSpaN"></span>
                  <?php 
                  // if ( ($agent_lead_search == 'ENABLED') or ($agent_lead_search == 'LIVE_CALL_INBOUND') or ($agent_lead_search == 'LIVE_CALL_INBOUND_AND_MANUAL') )
                    // {echo "<font class=\"body_text\"><a href=\"#\" onclick=\"OpeNSearcHForMDisplaYBox();return false;\">"._QXZ("LEAD SEARCH")."</a></font>";}
                ?>
              </td>
            </tr>
            <?php $stmt1="SELECT * from caller_types where status='1' AND `deleted_at` is null;";
                  $rslt1=mysql_to_mysqli($stmt1, $link); ?>
            <tr>
              <td><font>Customer No:</font></td>
              <td><input type="text" name="phone_number" id="phonenumber"  class="form-control" readonly /></td>
              <!-- <td><font>Alt No:</font></td>
              <td><input type="text" name="alt_no" id="altNo" class="form-control number" /></td> -->
              <td><font>Caller Name:</font></td>
              <td><input type="text" name="name" id="callerName" class="form-control" /></td>
              <td>
                <font>
                  <span id="XferControl">
                    
                  </span>
                </font>
              </td>
            </tr>
            <tr style="display: none;">
                <td><font>City:</font></td>
                <td><input type="text" name="city" id="cityField" class="form-control" /></td>
                <td><font>State:</font></td>
                <td><input type="text" name="state" id="stateField" class="form-control" /></td>
            </tr>              
            <!-- <tr> -->
              <!-- <td><font>Caller Type:</font></td> -->
              <!-- <td>
                <select name="caller_type" id="caller_type" class="form-control" required="">
                <option value="">Select Caller Type</option>
                <?php while ($option = mysqli_fetch_row($rslt1)) { 
                      // echo "<option value=".$option[0].">".$option[1]."</option>";
                    } ?>
                </select>
              </td> -->
              
            <!-- </tr> -->
             
            <!-- <tr style="display: none;">
              <td align="left">
                <font class="body_text">
                <?php $required_fields = '|'; 
                if ( $label_phone_number == '---HIDE---' ){ ?>
                <input type="hidden" name="phone_number" id="phone_number" value="" />
                  <font class="body_text">
                    <span id="phone_numberDISP"></span>
                  </font>
                <?php } else{  echo "$label_phone_number:" ?>
              </td>
              <td align="left">
                <font class="body_text">
                  <?php if ( (preg_match('/Y/',$disable_alter_custphone)) or (preg_match('/HIDE/',$disable_alter_custphone)) )
                  { ?>
                    <font class="body_text">
                      <span id="phone_numberDISP"></span>
                    </font>
                    <input type="hidden" name="customer_no" id="phone_number" value="" />
                  <?php } else{ ?>
                    <input type="text" size="20" name="phone_number" id="phone_number" maxlength="$MAXphone_number" class="cust_form form-control" value="" />
                  <?php }
                } ?>
              </td>
            </tr> -->
           
            
            <tr style="display: none;">
              <td align="left">
                <font class="body_text">
                  <?php if ($label_title == '---HIDE---')
                  { ?>
              </td>
              <td align="left" colspan="5"><input type="hidden" class="form-control" name="title" id="title" value="" />
                <?php } else{ 
                  $title_readonly='';
                  if (preg_match("/---READONLY---/",$label_title))
                    {
                      $title_readonly='readonly="readonly"';
                      $label_title = preg_replace("/---READONLY---/","",$label_title);
                    } else{
                      if (preg_match("/---REQUIRED---/",$label_title))
                        {$required_fields .= "title|";   $label_title = preg_replace("/---REQUIRED---/","",$label_title);}
                    } 
                  echo "$label_title: "; ?>
              </td>
              <td align="left">
                <font class="body_text">
                  <input type="text" size="4" name="title" id="title" maxlength="$MAXtitle" class="cust_form form-control" value="" $title_readonly />
                </td>
                <td>
                  <?php }
                  if ($label_first_name == '---HIDE---'){ ?>
                    <input type="hidden" name="first_name" id="first_name" value="" />
                  <?php } else{
                    $first_name_readonly='';
                    if (preg_match("/---READONLY---/",$label_first_name)){
                      $first_name_readonly='readonly="readonly"';
                      $label_first_name = preg_replace("/---READONLY---/","",$label_first_name);
                    }else{
                      if (preg_match("/---REQUIRED---/",$label_first_name)){
                        $required_fields .= "first_name|";   
                        $label_first_name = preg_replace("/---REQUIRED---/","",$label_first_name);
                      }
                    }
                  echo " $label_first_name: "?>
                </td>
                <td align="left">
                  <font class="body_text">
                    <input type="text" size="17" name="first_name" id="first_name" maxlength="$MAXfirst_name" class="cust_form form-control" value="" $first_name_readonly />
                  </td>
                  <td>
                    <?php }
                    if ($label_middle_initial == '---HIDE---'){ ?>
                      <input type="hidden" name="middle_initial" id="middle_initial" value="" />
                    <?php }else{
                      $middle_initial_readonly='';
                      if (preg_match("/---READONLY---/",$label_middle_initial)){
                        $iddle_initial_readonly='readonly="readonly"';   
                        $label_middle_initial = preg_replace("/---READONLY---/","",$label_middle_initial);
                      }else{
                        if (preg_match("/---REQUIRED---/",$label_middle_initial)){
                          $required_fields .= "middle_initial|";   
                          $label_middle_initial = preg_replace("/---REQUIRED---/","",$label_middle_initial);
                        }
                      }
                    echo " $label_middle_initial: "?>
                  </td>
                  <td align="left">
                    <font class="body_text">
                      <input type="text" size="1" name="middle_initial" id="middle_initial" maxlength="$MAXmiddle_initial" class="cust_form form-control" value="" $middle_initial_readonly />
                    </font>
                  </td>
                  <td>
                  <?php } 
                    if ($label_last_name == '---HIDE---'){ ?>
                      <input type="hidden" name="last_name" id="last_name" value="" />
                    <?php }else{
                      $last_name_readonly='';
                      if (preg_match("/---READONLY---/",$label_last_name)){
                        $last_name_readonly='readonly="readonly"';   
                        $label_last_name = preg_replace("/---READONLY---/","",$label_last_name);
                      }else{
                        if (preg_match("/---REQUIRED---/",$label_last_name)){
                          $required_fields .= "last_name|";   $label_last_name = preg_replace("/---REQUIRED---/","",$label_last_name);
                        }
                      }
                    echo " $label_last_name: "?>
                  </td>
                  <td align="left">
                    <font class="body_text">
                      <input type="text" size="23" name="last_name" id="last_name" maxlength="$MAXlast_name" class="cust_form form-control" value="" $last_name_readonly />
                    </font>
                  </td>
                  <td>
                  <?php } ?>
                  </td>
                </tr>
                <tr style="display: none;">
                  <td align="left">
                    <font class="body_text">
                      <?php if ($label_address1 == '---HIDE---'){ ?>
                  </td>
                  <td align="left">
                    <input type="hidden" name="address1" id="address1" value="" />
                    <?php }else{
                      $address1_readonly='';
                      if (preg_match("/---READONLY---/",$label_address1)){
                        $address1_readonly='readonly="readonly"';   
                        $label_address1 = preg_replace("/---READONLY---/","",$label_address1);
                      }else{
                        if (preg_match("/---REQUIRED---/",$label_address1)){
                          $required_fields .= "address1|";   
                          $label_address1 = preg_replace("/---REQUIRED---/","",$label_address1);
                        }
                      }
                    echo "$label_address1: "?>
                  </td>
                  <td align="left">
                    <font class="body_text">
                      <input type="text" size="85" name="address1" id="address1" maxlength="$MAXaddress1" class="cust_form form-control" value="" $address1_readonly />
                    </font>
                  </td>
                  <?php } ?>
                  <td align="left">
                    <font class="body_text">
                      <?php if ($label_address2 == '---HIDE---'){ ?>
                    </font>
                  </td>
                  <td align="left">
                    <input type="hidden" name="address2" id="address2" value="" />
                  <?php }else{
                    $address2_readonly='';
                    if (preg_match("/---READONLY---/",$label_address2)){
                      $address2_readonly='readonly="readonly"';   
                      $label_address2 = preg_replace("/---READONLY---/","",$label_address2);
                    }else{
                      if (preg_match("/---REQUIRED---/",$label_address2)){
                        $required_fields .= "address2|";   
                        $label_address2 = preg_replace("/---REQUIRED---/","",$label_address2);
                      }
                    }
                  echo "$label_address2: "?>
                  </td>
                  <td align="left">
                    <font class="body_text">
                      <input type="text" size="20" name="address2" id="address2" maxlength="$MAXaddress2" class="cust_form form-control" value="" $address2_readonly />
                    </font>
                  </td>
                  <?php } ?>
                  <td align="left">
                    <font class="body_text">
                    <?php if ($label_address3 == '---HIDE---'){ ?>
                  </td>
                  <td align="left" colspan="3">
                    <input type="hidden" name="address3" id="address3" value="" />
                  <?php }else{
                    $address3_readonly='';
                    if (preg_match("/---READONLY---/",$label_address3)){
                      $address3_readonly='readonly="readonly"';   
                      $label_address3 = preg_replace("/---READONLY---/","",$label_address3);
                    }else{
                      if (preg_match("/---REQUIRED---/",$label_address3)){
                        $required_fields .= "address3|";   
                        $label_address3 = preg_replace("/---REQUIRED---/","",$label_address3);
                      }
                    } 
                echo "$label_address3: ";?>
              </td>
              <td align="left">
                <font class="body_text">
                  <input type="text" size="45" name="address3" id="address3" maxlength="$MAXaddress3" class="cust_form form-control" value="" $address3_readonly />
                </font>
              </td>
              <?php } ?>
              <td align="left">
                <font class="body_text">
                <?php if ($label_city == '---HIDE---'){ ?>
              </td>
              <td align="left">
                <input type="hidden" name="city" id="city" value="" />
                <?php } else{
                  $city_readonly='';
                  if (preg_match("/---READONLY---/",$label_city)){
                    $city_readonly='readonly="readonly"';   
                    $label_city = preg_replace("/---READONLY---/","",$label_city);
                  }
                else{
                  // if (preg_match("/---REQUIRED---/",$label_city)){
                  //   $required_fields .= "city|";   
                  //   $label_city = preg_replace("/---REQUIRED---/","",$label_city);
                  //   }
                  }
                echo "$label_city: ";?>
              </td>
              <!-- <td align="left">
                <font class="body_text">
                  <input type="text" size="20" name="city" id="city" maxlength="$MAXcity" class="cust_form form-control" value="" $city_readonly />
                </font>
              </td> -->
              <?php } ?>
              </tr>
              <tr style="display: none;">
                <!-- <td align="left"><font class="body_text">
                  <?php if ($label_state == '---HIDE---'){ ?>
                </td>
                <td align="left">
                  <input type="hidden" name="state" id="state" value="" />
                  <?php } else{
                  $state_readonly='';
                  if (preg_match("/---READONLY---/",$label_state))
                  {
                    $state_readonly='readonly="readonly"';
                    $label_state = preg_replace("/---READONLY---/","",$label_state);
                  }
                else{
                  if (preg_match("/---REQUIRED---/",$label_state))
                    {$required_fields .= "state|";   $label_state = preg_replace("/---REQUIRED---/","",$label_state);}
                  }
                echo "$label_state: " ?>
              </td>
              <td align="left">
                <font class="body_text">
                  <input type="text" size="4" name="state" id="state" maxlength="$MAXstate" class="cust_form form-control" value="" $state_readonly />
                </font>
              </td>
                <?php } ?> -->
              <td align="left">
                <font class="body_text">
                <?php
              if ($label_postal_code == '---HIDE---')
                    {echo " </td><td align=\"left\"><input type=\"hidden\" name=\"postal_code\" id=\"postal_code\" value=\"\" />";}
              else
                    {
                $postal_code_readonly='';
                if (preg_match("/---READONLY---/",$label_postal_code))
                  {$postal_code_readonly='readonly="readonly"';   $label_postal_code = preg_replace("/---READONLY---/","",$label_postal_code);}
                else
                  {
                  if (preg_match("/---REQUIRED---/",$label_postal_code))
                    {$required_fields .= "postal_code|";   $label_postal_code = preg_replace("/---REQUIRED---/","",$label_postal_code);}
                  }
                echo "$label_postal_code: </td><td align=\"left\"><font class=\"body_text\"><input type=\"text\" size=\"14\" name=\"postal_code\" id=\"postal_code\" maxlength=\"$MAXpostal_code\" class=\"cust_form form-control\" value=\"\" $postal_code_readonly />";
                }

                echo "</td><td align=\"left\"><font class=\"body_text\">";

              if ($label_province == '---HIDE---')
                    {echo " </td><td align=\"left\"><input type=\"hidden\" name=\"province\" id=\"province\" value=\"\" />";}
              else
                    {
                $province_readonly='';
                if (preg_match("/---READONLY---/",$label_province))
                  {$province_readonly='readonly="readonly"';   $label_province = preg_replace("/---READONLY---/","",$label_province);}
                else
                  {
                  if (preg_match("/---REQUIRED---/",$label_province))
                    {$required_fields .= "province|";   $label_province = preg_replace("/---REQUIRED---/","",$label_province);}
                  }
                echo "$label_province: </td><td align=\"left\"><font class=\"body_text\"><input type=\"text\" size=\"20\" name=\"province\" id=\"province\" maxlength=\"$MAXprovince\" class=\"cust_form form-control\" value=\"\" $province_readonly />";
                }

                echo "</td><td align=\"left\"><font class=\"body_text\">";

              if ($label_vendor_lead_code == '---HIDE---')
                    {echo " </td><td align=\"left\"><input type=\"hidden\" name=\"vendor_lead_code\" id=\"vendor_lead_code\" value=\"\" />";}
              else
                    {
                $vendor_lead_code_readonly='';
                if (preg_match("/---READONLY---/",$label_vendor_lead_code))
                  {$vendor_lead_code_readonly='readonly="readonly"';   $label_vendor_lead_code = preg_replace("/---READONLY---/","",$label_vendor_lead_code);}
                else
                  {
                  if (preg_match("/---REQUIRED---/",$label_vendor_lead_code))
                    {$required_fields .= "vendor_lead_code|";   $label_vendor_lead_code = preg_replace("/---REQUIRED---/","",$label_vendor_lead_code);}
                  }
                echo "$label_vendor_lead_code: </td><td align=\"left\"><font class=\"body_text\"><input type=\"text\" size=\"15\" name=\"vendor_lead_code\" id=\"vendor_lead_code\" maxlength=\"$MAXvendor_lead_code\" class=\"cust_form form-control\" value=\"\" $vendor_lead_code_readonly />";
                }

                echo "</td></tr><tr style=\"display: none;\"><td align=\"left\"><font class=\"body_text\">";

              if ($label_gender == '---HIDE---')
                {
                echo "</td><td align=\"left\"><font class=\"body_text\"><span id=\"GENDERhideFORie\"><input type=\"hidden\" name=\"gender_list\" id=\"gender_list\" value=\"\" /></span>";
                }
              else
                    {
                echo "$label_gender: </td><td align=\"left\"><font class=\"body_text\"><span id=\"GENDERhideFORie\"><select size=\"1\" name=\"gender_list\" class=\"form-control\" id=\"gender_list\"><option value=\"U\">"._QXZ("U - Undefined")."</option><option value=\"M\">"._QXZ("M - Male")."</option><option value=\"F\">"._QXZ("F - Female")."</option></select></span>";
                }

                echo "</td><td align=\"left\"><font class=\"body_text\">";


              if ($label_phone_code == '---HIDE---')
                    {echo " </td><td align=\"left\"><input type=\"hidden\" name=\"phone_code\" id=\"phone_code\" value=\"\" />";}
              else
                    {
                $phone_code_readonly='';
                if (preg_match("/---READONLY---/",$label_phone_code))
                  {$phone_code_readonly='readonly="readonly"';   $label_phone_code = preg_replace("/---READONLY---/","",$label_phone_code);}
                else
                  {
                  if (preg_match("/---REQUIRED---/",$label_phone_code))
                    {$required_fields .= "phone_code|";   $label_phone_code = preg_replace("/---REQUIRED---/","",$label_phone_code);}
                  }
                echo "$label_phone_code: </td><td align=\"left\"><font class=\"body_text\"><input type=\"text\" size=\"4\" name=\"phone_code\" id=\"phone_code\" maxlength=\"$MAXphone_code\" class=\"cust_form form-control\" value=\"\" $phone_code_readonly />";
                }

                echo "</td><td align=\"left\"><font class=\"body_text\">";

              if ($label_alt_phone == '---HIDE---')
                    {echo " </td><td align=\"left\"><input type=\"hidden\" name=\"alt_phone\" id=\"alt_phone\" value=\"\" />";}
              else
                    {
                $alt_phone_readonly='';
                if (preg_match("/---READONLY---/",$label_alt_phone))
                  {$alt_phone_readonly='readonly="readonly"';   $label_alt_phone = preg_replace("/---READONLY---/","",$label_alt_phone);}
                else
                  {
                  if (preg_match("/---REQUIRED---/",$label_alt_phone))
                    {$required_fields .= "alt_phone|";   $label_alt_phone = preg_replace("/---REQUIRED---/","",$label_alt_phone);}
                  }
                echo "$label_alt_phone: </td><td align=\"left\"><font class=\"body_text\"><input type=\"text\" size=\"14\" name=\"alt_phone\" id=\"alt_phone\" maxlength=\"$MAXalt_phone\" class=\"cust_form form-control\" value=\"\" $alt_phone_readonly />";
                }

                echo "</td><td align=\"left\"><font class=\"body_text\">";

              if ($label_security_phrase == '---HIDE---')
                    {echo " </td><td align=\"left\"><input type=\"hidden\" name=\"security_phrase\" id=\"security_phrase\" value=\"\" />";}
              else
                    {
                $security_phrase_readonly='';
                if (preg_match("/---READONLY---/",$label_security_phrase))
                  {$security_phrase_readonly='readonly="readonly"';   $label_security_phrase = preg_replace("/---READONLY---/","",$label_security_phrase);}
                else
                  {
                  if (preg_match("/---REQUIRED---/",$label_security_phrase))
                    {$required_fields .= "security_phrase|";   $label_security_phrase = preg_replace("/---REQUIRED---/","",$label_security_phrase);}
                  }
                echo "$label_security_phrase: </td><td align=\"left\"><font class=\"body_text\"><input type=\"text\" size=\"20\" name=\"security_phrase\" id=\"security_phrase\" maxlength=\"$MAXsecurity_phrase\" class=\"cust_form form-control\" value=\"\" $security_phrase_readonly />";
                }

                echo "</td></tr><tr style=\"display:none;\"><td align=\"left\"><font class=\"body_text\">";

              if ($label_email == '---HIDE---')
                    {echo " <input type=\"hidden\" name=\"email\" id=\"email\" value=\"\" />";}
              else
                    {
                $email_readonly='';
                if (preg_match("/---READONLY---/",$label_email))
                  {$email_readonly='readonly="readonly"';   $label_email = preg_replace("/---READONLY---/","",$label_email);}
                else
                  {
                  if (preg_match("/---REQUIRED---/",$label_email))
                    {$required_fields .= "email|";   $label_email = preg_replace("/---REQUIRED---/","",$label_email);}
                  }
                echo "$label_email: </td><td align=\"left\"><font class=\"body_text\"><input type=\"text\" size=\"45\" name=\"email\" id=\"email\" maxlength=\"$MAXemail\" class=\"cust_form form-control\" value=\"\" $email_readonly />";
                }

              if (strlen($agent_display_fields) > 3)
                {
                  echo "</td></tr><tr><td align=\"left\" colspan=\"5\"><font class=\"body_text\">";

                if (preg_match("/entry_date/",$agent_display_fields))
                  {
                  echo _QXZ("Entry Date").": &nbsp; <font class=\"body_text\"><span id=\"entry_dateDISP\"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </span> &nbsp; </font>";
                  }
                if (preg_match("/source_id/",$agent_display_fields))
                  {
                  echo _QXZ("Source ID").": &nbsp; <font class=\"body_text\"><span id=\"source_idDISP\"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </span> &nbsp; </font>";
                  }
                if (preg_match("/date_of_birth/",$agent_display_fields))
                  {
                  echo _QXZ("Date of Birth").": &nbsp; <font class=\"body_text\"><span id=\"date_of_birthDISP\"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </span> &nbsp; </font>";
                  }
                if (preg_match("/rank/",$agent_display_fields))
                  {
                  echo _QXZ("Rank").": &nbsp; <font class=\"body_text\"><span id=\"rankDISP\"> &nbsp; &nbsp; </span> &nbsp; </font>";
                  }
                if (preg_match("/owner/",$agent_display_fields))
                  {
                  echo _QXZ("Owner").": &nbsp; <font class=\"body_text\"><span id=\"ownerDISP\"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </span> &nbsp; </font>";
                  }
                if (preg_match("/last_local_call_time/",$agent_display_fields))
                  {
                  echo _QXZ("Last Call").": &nbsp; <font class=\"body_text\"><span id=\"last_local_call_timeDISP\"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </span> &nbsp; </font>";
                  }
                }

                echo "</td>
                </tr><tr><td align=\"left\"><font class=\"body_text\">\n";

              if ($per_call_notes == 'ENABLED')
                {
                    echo _QXZ("Call Notes: ");
                if ($agent_call_log_view == '1')
                  {echo "<br /><span id=\"CallNotesButtons\"><a href=\"#\" onclick=\"VieWNotesLoG();return false;\">"._QXZ("view notes")."</a></span> ";}
                    echo "</td><td align=\"left\" colspan=\"5\"><font class=\"body_text\">";
                echo "<textarea name=\"call_notes\" id=\"call_notes\" rows=\"2\" cols=\"85\" class=\"cust_form_text\" value=\"\"></textarea>\n";
                }
              else
                {
                    echo " </td><td align=\"left\" colspan=5><input type=\"hidden\" name=\"call_notes\" id=\"call_notes\" value=\"\" /><span id=\"CallNotesButtons\"></span>\n";
                }

              echo "<input type=\"hidden\" name=\"required_fields\" id=\"required_fields\" value=\"$required_fields\" />\n";

              ?>
              </font>
              </td>
            </tr>
            <tr style="display: none;">
              <td style="text-align:left;">
                <a style="cursor: pointer;" class="large-font script" id="new_ticket"> <img src="./images/<?php echo _QXZ("new-ticket.gif"); ?>" width="110px" height="25px" border="0" alt="New - Ticket" /></a>
              </td>              
              <td align="right">
                <font>          
                  <span id="XferControl">
                    <img src="./images/<?php echo _QXZ("vdc_LB_transferconf.gif"); ?>" width="110px" height="25px" border="0" alt="Transfer - Conference" />
                  </span>
                </font>    
              </td>
            </tr>
          </table>
          

           <!--============================= Ticket Form Start Here   ============================== -->
           <table id="ticket_form_header" style="display: none;">             
              <tr bgcolor="#4682b4" style="color: white">
                <td colspan=""> 
                  <font class="body_text"><?php echo _QXZ("Ticket Number :"); ?></font>                  
                  <span id="ticket_number">0</span>
                  <font class="body_text"><?php echo _QXZ("Source :"); ?></font>                  
                  <span id="source">Voice</span>
                  <font class="body_text"><?php echo _QXZ("Campaign :"); ?></font>
                  <span id="campaign"><?php echo $VD_campaign; ?></span>
                  <font class="body_text"><?php echo _QXZ("Action No :"); ?></font>
                  <span id="action_no">1</span>
                </td>              
              </tr>            
           </table>           
          <table id="ticket_form" border="0" cellspacing="0" cellpadding="2" style="display:none; background: #95b38636;padding-bottom:1%;">            
            <tr>
              <td id="current_ticketTime" align="right" style="display: none;">
                <font>TicketTime: </font>
              </td>
              <td style="min-width: 180px;" id="display_ticketTime" style="display: none;">
                <span name="custdatetime" id="custdatetime" style="display: none;"> </span>
                <span name="custdatetime" id="custdatetime_view" style="font: 400 13.3333px Arial;"> </span>
                <input type="hidden" name="ticketAt" id="ticketAt">
                <input type="hidden" name="vicidial_log_id" id="vicidial_log_id">
                <input type="hidden" name="skill_set" id="skill_set">
                <input type="hidden" name="holdTime" id="holdTime">
              </td>
               <td align="right" width="25%">
                <span id="ticketStart">
                  <font>Ticket Start Time: </font>
                </span>
              </td>
              <td align="left" width="25%">
                <span id="ticket_start" style="font-size: 12px;"></span>
              </td>
              <td align="right" width="25%">
                <span id="endticket">
                  <font>Ticket End Time: </font>
                </span>
              </td>
              <td align="left" width="25%">
                <span id="endticket_time" style="font-size: 12px;"></span>
              </td>
              <td align="right" width="17%">
                <font>Duration: </font>
              </td>
              <td id="duration_span">
                <span id="lastCallDuration" style="font: 400 13.3333px Arial"></span>
              </td>
              <td>
                <span id="SecondSDISP" style="display: none;"></span>
                <span class="ticketCall_time_view" style="font: 400 13.3333px Arial; display: none;"></span>
                <input type="hidden" name="ticketCall_time" class="ticketCall_time">
              </td>
              <input type="hidden" id="acw_time" name="acw_time">
            </tr>
            <tr>
              <td align="right">
                <font>Assign BY: </font>
              </td>
              <td>
                <input type="text" id="assign_by_disable" name="assign_by" value="<?php echo $VD_login.' ('.$VUuser_full_name.')'; ?>" readonly class="form-control">
                <input type="hidden" name="assign_agent" value="<?php echo $VD_login; ?>" readonly class="form-control">
              </td>
              <td align="right" class="ticket-type-field">
                  <font>Ticket Type: </font>
              </td>
              <td width="25%">
                <?php $stmt="SELECT tp.id,tm.ticket_name from ticket_mapping as tp left JOIN tickets_management as tm on tp.ticket_type_id=tm.id where tp.campaign_id = '$VD_campaign' AND tp.map_type='1' AND tm.status='1' AND tp.is_visible=1;";
                  $rslt=mysql_to_mysqli($stmt, $link); ?>
                <div id="slider" style="z-index: 0; position: relative;">
                    <!-- <span class="next_span"> -->
                    <a href="javascript:void(0)" class="control_next" state_set="">></a> 
                    <a href="javascript:void(0)" class="newClass" style="display: none;">></a> 
                    <!-- </span> -->
                    <a href="javascript:void(0)" class="control_prev"><</a>
                    <ul id="tickets_status">
                      <li id="ticketroot">
                        <select name="tickettype" class="form-control active" id="ticket_type" required>
                          <option value="">Select Ticket Type</option>
                        <?php while ($option = mysqli_fetch_assoc($rslt)){ ?>
                          <option value="<?php echo $option['id'];?>"><?php echo $option['ticket_name'];?></option>
                      <?php } ?>
                        </select>
                    </li>
                    <li id="tickettypeparent"></li>
                    <li id="tickettypechild"></li>       
                    </ul> 
                  </div>                    
              </td>              
            </tr>           
            <tr>
              <td align="right">
                <font>Ticket Status:</font>
              </td>
              <td>
                <?php $stmt1="SELECT * from ticket_status_manager where status='1' AND deleted_at is null;";
                $rslt1=mysql_to_mysqli($stmt1, $link); ?>
                <select name="ticketstatus" id="ticketstatus" class="form-control" style="width: 18%;" required="">
                  <option value="">Select Ticket Status </option>
                    <?php while ($option = mysqli_fetch_row($rslt1)) { 
                      echo "<option value=".$option[0].">".$option[1]."</option>";
                    } ?>
                </select> 
              </td>
              <td align="right" style="display: none; margin-left:40px;" id="clouserlabel">
                <font>Clouser type: </font>
              </td>
              <td style="display: none;" id="clouserinput">
                <select name="clousertype" id="clouseroptions" class="form-control" >
                  <option value="">Select Clouser type </option>
                </select> 
              </td>
              <td><span style="font-size: 12px; display: none;" id="closed_clouser"></span></td>
            </tr>
            <?php 
              // $form_id = 2;
                $stmt="SELECT label,name,field_type,table_name,is_required,status from form_manager where form_id = '2' and status='1';";
              $rslt=mysql_to_mysqli($stmt, $link);
              $tr = 0;
              $a = 1;
              while ($row=mysqli_fetch_assoc($rslt)) {
                $b = $tr%$a;
                  if($b=='0'){ echo "</tr><tr>"; }
                if($row['field_type']=='text' || $row['field_type']=='number')
                { 
                   ?>
                  <td>
                    <font><?php echo $row['label']; ?>: </font>
                  </td>
                  <td>
                    <input type="<?php echo $row['field_type']; ?>" name="<?php echo $row['name']; ?>" class="form-control" <?php if($row['is_required']=='1'){ echo "required"; } ?>>
                  </td>                  
                  <?php }
                
                if($row['field_type']=='checkbox')
                { 
                  $table_name = $row['table_name']; 
                  $stmt="SELECT name,description,status from $table_name where status='1';";
                  $rslt=mysql_to_mysqli($stmt, $link);
                  ?>
                  <td align="right">
                    <font><?php echo $row['label'].': '; ?>: </font>
                  </td>
                  <td>
                    <?php while ($option = mysqli_fetch_assoc($rslt)) { ?>
                    <input type="<?php echo $row['field_type']; ?>" name="<?php echo $row['name']; ?>" class="form-control" <?php if($row['is_required']=='1'){ echo "required"; } ?>><?php echo $option['name'];
                  } ?>
                  </td>
                  <?php }
                  if($row['field_type']=='textarea')
                  { ?>
                    <td align="right">
                      <font><?php echo $row['label'].': '; ?>: </font>
                    </td>
                    <td>
                      <textarea name="<?php echo $row['name']; ?>" class="form-control" <?php if($row['is_required']=='1'){ echo "required"; } ?>></textarea>
                    </td>
                  <?php }
                  $tr++; 
              }
              ?>
            </tr>   
             <tr><td></td   ><td id="tickettype_detail" colspan="4" style="font-size:12px;"></td></tr>         
            <tr>
              <td align="right">
                <font class="body_text">
                <?php if ($label_comments == '---HIDE---'){ ?>                
                      <input type="hidden" name="comments" id="comments" value="" />
                      <input type="hidden" name="other_tab_comments" id="other_tab_comments" value="" />
                      <input type="hidden" name="dispo_comments" id="dispo_comments" value="" />
                      <input type="hidden" name="callback_comments" id="callback_comments" value="" />
                      <span id='viewcommentsdisplay' style="display: none;">
                        <input type='button' id='ViewCommentButton' onClick="ViewComments('ON','','','YES')" value='-"._QXZ("History")."-'/>
                      </span>
                      <span id='otherviewcommentsdisplay' style="display: none;">
                        <input type='button' id='OtherViewCommentButton' onClick="ViewComments('ON','','','YES')" value='-"._QXZ("History")."-'/>
                      </span>
                <?php } else{
                    echo "$label_comments: ";?>
                      <span id='viewcommentsdisplay' style="display: none;">
                        <input type='button' id='ViewCommentButton' onClick="ViewComments('ON','','','YES')" value='-"._QXZ("History")."-'/>
                      </span>
                    </font>
              </td>
              <td align="left" colspan="3" class="textarea-td">
                <font class="body_text">
                <?php if ( ($multi_line_comments) ){ ?>
                  <textarea name="comments" id="comments" rows="4" class="cust_form_text" value="" style="width: 700px;" required=""></textarea>
                  <?php } else{ ?>
                    <input type="text" size="65" name="comments" id="comments" maxlength="255" class="cust_form" value="" />
                  <?php }
                } ?>
                </font>
              </td>                            
            </tr>
            <tr>
              <input type="hidden" id="ticket_id" >  
              <td  id="save_button" class="btn" style="padding-top:1%;">
                <input type="button" class="update_ticket" value="Update" style="display: none;">
                <input type="button" value="Save" id="save_ticket">
              </td>
              <td class="btn" style="padding-top:1%;">
                <a style="cursor: pointer;" class="history large-font script" data-type="view_history" id="view_history">View History</a>
              </td>
            </tr>
          </table>
          <span id="disposeButton" style="display: none; text-align:center; color:green; font-weight:600; background: #e8efe6; border-bottom: 1px solid; padding:1%;">
            <span id="ticketmessage"></span>
            <input type="hidden" id="callBack" value="">
          <input type="button" onclick="dispoaseCall()" value="<?php echo _QXZ("Dispose"); ?>">
          </span>
        </span>
    <!--============================= Dashboard Ticket Count Start Here ========================= -->
    <span id="dash1" style="display: none;">
      <style type="text/css">
        .gj-icon{
          margin-right: 30px;
        }
        .gj-textbox-md{
          width: 80%;
        }
      </style>
      <table cellpadding="0" cellspacing="5" class="date-time">
        <tr >
          <td align="left" width="15%">
            <div class="profile-info">
              <i class='fas fa-user-circle' style='font-size:36px'></i><br>
              <p><?php echo $VUuser_full_name; ?></p>
              
            </div>
          </td>
          <td align="left" width="50%">
            <p style="color:black;" id="loginHour"></p>
            <i class="fas fa-sync agentTicketCount"></i>
            <!-- <span ></span> -->
          </td>
          <td align="left" width="35%" style="box-shadow: 3px 3px 5px rgba(0,0,0,0.7);">
            <table cellpadding="0" cellspacing="5" class="date-time">
              <?php $fdate = date('Y-m-d 00:00'); 
              $tdate = date('Y-m-d 23:59'); ?>
              <tr>
                <td>Start Date Time</td>
                <td>End Date Time</td>
                <td rowspan="2">
                  <!-- <input type="button" class="agentTicketCount" id="searchTicketCount" value="Search"> -->
                  <img class="agentTicketCount" id="searchTicketCount" src="./images/reload.gif" width="110" height="25">
                </td>
              </tr>
              <tr>
                <td><input id="fromDate" value="<?php echo $fdate; ?>" min='2018-01-01' max='2020-04-01'></td>
                <td><input id="toDate" value="<?php echo $tdate; ?>" min='2018-01-01' max='2020-04-01'></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
     
      <table cellpadding="0" cellspacing="10" class="dashboar-tkt" style="text-align: center; margin-bottom: 10px;">
        <tr>
          <td width="10%" height="80px" class="dashboar-block">
            <div class="dashboar-block-img">
              <img src="./images/<?php echo _QXZ("open.png"); ?>" width="40px" border="0" />
            </div>
            <div  class="dashboar-block-content history" status="1" data-type="agent_ticket_history">
              <p style="text-align: center; margin: 0;">Open</p>
              <span style="text-align: center; margin: 0; font-weight: 600; font-size: 20px;" id="opentickets"></span>
            </div>
          </td>
          <td width="10%" height="80px" class="dashboar-block">
            <div class="dashboar-block-img">
              <img src="./images/<?php echo _QXZ("escalated.png"); ?>" width="40px" border="0" />
            </div>
            <div  class="dashboar-block-content history" status="3" data-type="agent_ticket_history">
              <p style="text-align: center; margin: 0;">Escalated</p>
            <span style="text-align: center; margin: 0; font-weight: 600; font-size: 20px;" id="escalatedtickets"></span>
            </div>
          </td>
          <td width="10%" height="80px" class="dashboar-block">
            <div class="dashboar-block-img">
              <img src="./images/<?php echo _QXZ("closed.png"); ?>" width="40px" border="0" />
            </div>
            <div  class="dashboar-block-content history" status="2" data-type="agent_ticket_history">
              <p style="text-align: center; margin: 0;">Closed</p>
            <span style="text-align: center; margin: 0; font-weight: 600; font-size: 20px;" id="closedtickets"></span>
            </div>
          </td>
          <td width="10%" height="80px" class="dashboar-block">
            <div class="dashboar-block-img">
              <img src="./images/<?php echo _QXZ("others.png"); ?>" width="40px" border="0" />
            </div>
            <div  class="dashboar-block-content history" status="4" data-type="agent_ticket_history">
              <p style="text-align: center; margin: 0;">Others</p>
             <span style="text-align: center; margin: 0; font-weight: 600; font-size: 20px;" id="otherstickets"></span>
            </div>
          </td>                                                                                
        </tr>
      </table>
         

      <?php include('footer.php'); ?>
    </span>
      </font>
    </td>
    <td width="1" align="center"></td>
  </tr>
    <tr>
      <td align="left" colspan="3" height="<?php echo $BPheight ?>px">
    &nbsp;</td>
  </tr>
    <tr>
      <td align="left" colspan="3">
    &nbsp;</td>
  </tr>
</table>
  </td></tr>
 </table>
</span>
<!-- END *********   Here is the main VICIDIAL display panel -->



  <!--Modal for ticket Action  -->
  <div id="ticketAction" class="modal break-modal">
    <!-- Modal content -->
    <div class="modal-content view-history-tbl">
      <div class="modal-header">
        <span class="close" id="actionClose">&times;</span>
        <h5>Ticket Action History</h5>
      </div>      
        <div id="actiontb"></div>      
    </div>
  </div>


   <!-- Modal for login hour -->
<div id="loginHourModel" class="modal break-modal" style="display: none;">
  <!-- Modal content -->
  <div class="modal-content">
     <div class="modal-body" style="padding:0px;">
      <div class="modal-cont-field">
      <?php
       $totalcallbackCount=0;
       $totalhoppCount=0;
       $totallistCount=0;
        $rem_array=array();
        $report_date = date('Y-m-d');
        $stmt_cllbk = "SELECT count(vcb.lead_id) FROM vicidial_callbacks vcb left join vicidial_list vl on vl.lead_id = vcb.lead_id WHERE vcb.status= 'ACTIVE' AND vcb.entry_time >= '$report_date 00:00:00' AND vcb.entry_time<='$report_date 23:59:59' AND vl.user='$VD_login'";
        $rslt_cllbk = mysql_to_mysqli($stmt_cllbk,$link);
        $callbk_num_rows  = mysqli_num_rows($rslt_cllbk);
              if($callbk_num_rows>0){
                  $totalcallbkrow     = mysqli_fetch_row($rslt_cllbk);
                  $totalcallbackCount = $totalcallbkrow[0]; 
              }
    

        $stmt="SELECT count(vh.lead_id) from vicidial_hopper vh left join vicidial_list vl on vl.lead_id = vh.lead_id WHERE vh.campaign_id='$VD_login'  ";
        $rslt=mysql_to_mysqli($stmt, $link);
        $hopp_num_rows  = mysqli_num_rows($rslt);
              if($hopp_num_rows>0){
                  $totalhopprow     = mysqli_fetch_row($rslt);
                  $totalhoppCount = $totalhopprow[0]; 
              }  

        $stmtB="SELECT count(lead_id) FROM vicidial_list WHERE status ='NEW' AND ( owner='$VD_login' OR user='$VD_login' ) ";
        $rsltB=mysql_to_mysqli($stmtB, $link);
        $list_num_rows  = mysqli_num_rows($rsltB);
            if($list_num_rows>0){
                $totallistrow     = mysqli_fetch_row($rsltB);
                $totallistCount = $totallistrow[0]; 
            }  
          
     
      ?>
        <span class="close" style="position: absolute; top: 0px; right: 8px; font-size: 25px;">&times;</span>
        <table class="login-hour-table">
          <tr>
            <th>Login Details</th>
            <th>Time(HH:MM:SS)</th>
          </tr>
          <tr>
            <td>Net Login Hour: </td>
            <td id="net_login"></td>
          </tr>
          <tr>
            <td>Total Break Duration: </td>
            <td id="break_duration"></td>
          </tr>
          <tr>
            <td>Total Shift Hour: </td>
            <td id="shift_hour"></td>
          </tr>
           <tr>
            <td>Total Pending Calls </td>
            <td id="pending_calls"><?php echo $totallistCount + $totalhoppCount + $totalcallbackCount ?></td>
          </tr>
          <tr>
            <td colspan ='2' style='text-align:center;'><a id="summaryAgent" href="agentSummary.php?campaign_id=<?php echo $VD_campaign; ?>&agent_id=<?php echo $VD_login; ?>" class="blue-btn-new" target="_blank">View Summary</a></td>
          
          </tr>
        </table>

      </div>
    </div>
  </div>
</div>
<style type="text/css">
  #summaryAgent{
    background: #000 !important;
    padding: 7px 14px !important;
    border-radius: 5px !important;
    color: #fff !important;
    font-size: 13px;
}
</style>
<script>
var clsStopwatch = function() {
    // Private vars
    var startAt = 0;  // Time of last start / resume. (0 if not running)
    var lapTime = 0;  // Time on the clock when last stopped in milliseconds

    var now = function() {
            return (new Date()).getTime(); 
        }; 

    // Public methods
    // Start or resume
    this.start = function() {
            startAt = startAt ? startAt : now();
        };

    // Stop or pause
    this.stop = function() {
            // If running, update elapsed time otherwise keep it
            lapTime = startAt ? lapTime + now() - startAt : lapTime;
            startAt = 0; // Paused
        };

    // Reset
    this.reset_stopwatch = function() {
            lapTime = startAt = 0;
        };

    // Duration
    this.time = function() {
            return lapTime + (startAt ? now() - startAt : 0); 
        };
};

var x = new clsStopwatch();
var time;
var clocktimer;

function pad(num, size) {
var s = "0000" + num;
return s.substr(s.length - size);
}

function formatTime(time) {
var h = m = s = ms = 0;
var newTime = '';

h = Math.floor( time / (60 * 60 * 1000) );
time = time % (60 * 60 * 1000);
m = Math.floor( time / (60 * 1000) );
time = time % (60 * 1000);
s = Math.floor( time / 1000 );
// ms = time % 1000;

newTime = pad(h, 2) + ':' + pad(m, 2) + ':' + pad(s, 2) ;
return newTime;
}

function show() {
$time = document.getElementById('display');
update();
}

function update() {
$time.innerHTML = formatTime(x.time());
}

function start() {
clocktimer = setInterval("update()", 1);
x.start();
}

function stop() {
x.stop();
clearInterval(clocktimer);
}

function reset_stopwatch() {
stop();
x.reset_stopwatch();
update();
}

</script>
<?php 
 
 $query      = "SELECT user,user_id,pass FROM `vicidial_users` WHERE user='$VD_login'";
  $result = mysql_to_mysqli($query, $link); 
  $num_rows  = mysqli_num_rows($result);
  if($num_rows>0){
      $row     = mysqli_fetch_row($result);
      //if($row[1]!=null){
        $password = $row[2];    
        // print_r($row[2]);  
        if(!empty($password)){
        ?>
        <input type="hidden" name="user_pass" id ="user_pass" value="<?php echo $password ?>">

        <?php }
     // }

  }

?> 
</body>

<script type="text/javascript">
  $(document).ready(function() {
   
    // $('#ticket_type1').on('change',function(e){
    //   var country_id = $(this).val();
      // var state_list = '<select style="width: 80%; height: 30px;" id="state" stateset=""><option value="">Select State</option><option>UP</option><option>MP</option><option>Delhi</option></select>';
      // $('#state_list').html(state_list); 
      // if(country_id!=''){
        // moveRight();
        // $('#parent').attr('stateset','yes');
        // $(".control_next").css("display", "none");
        // $(".newClass").css("display", "flex");
      // }     
      
      // $('.control_next').attr('state_set','yes');
      // $(".control_next").addClass("bbox");
      // $('a.control_next').removeClass('control_next').addClass('newClass');
      // $("a").removeClass("intro");
      // $(".control_next").attr('class', 'newClass');
      /*var str = '<a class="newClass">></a>';
      $('.next_span').html(str);*/
      

    // });
    $(document).on('change','#parents',function(e){      
      var state_id = $(this).val();
      
      // var city_list = '<select style="width: 80%; height: 30px;"><option>City</option><option>Indore</option><option>Bhopal</option><option>Bina</option></select>';
      // var state_list = '<select style="width: 80%; height: 30px;"><option>Select State</option><option>UP</option><option>MP</option><option>Delhi</option></select>';
      // $('#city_list').html(city_list);
        moveRight();
      
      
    });

  $('#checkbox').change(function(){
    // setInterval(function () {
    //     moveRight();
    // }, 3000);
  });
  
  var slideCount = $('#slider ul li').length;
  var slideWidth = $('#slider ul li').width();
  var slideHeight = $('#slider ul li').height();
  var sliderUlWidth = slideCount * slideWidth;
  var position = 0;
  $('#slider').css({ width: slideWidth, height: slideHeight });
  
  $('#slider ul').css({ width: sliderUlWidth, marginLeft: - slideWidth });
  
    $('#slider ul li:last-child').prependTo('#slider ul');

});    

function customer_details(mobilenumber,from='',calltype=''){
      var user = <?php echo $VD_login ?>;
      console.log("df");
      console.log(<?php  ?>);
      console.log("df2");
      var lastTenNumber = mobilenumber.substr(mobilenumber.length - 10);
      var customer_mobile = mobilenumber;
      var SLCode = '';
      $("#phonenumber").text(mobilenumber);
      // $("#phonenumber1").val(val);

      console.log("yessss");
      console.log(customer_mobile);
      console.log(mobilenumber);
      var password =$("#user_pass").val();
      console.log(password);
      showDiv('MainPanel');
      $.ajax({
        type:'POST',
        dataType:'JSON',
        data:{'mobilenumber':customer_mobile,'action':'getCustomerDetails'},
        url:'ajaxRequest.php',
        success:function(res){          
          var ticketAt = getTicketDateTime();
          $("#customer_form").css("display", "");               
            $('#custdatetime_view').html(ticketAt);
            $('#ticketAt').val(ticketAt);
            $('#skills').html(res.skill);
            $('#vicidial_log_id').val(res.vicidial_closer_log_id);
            $('#skill_set').val(res.skill);
            $('#phone_numberDISP').html(mobilenumber);
          html = '';
          // if(res.data.length>0){
            console.log("===============================");
            console.log(mobilenumber);
            $("#phonenumber").text(mobilenumber);
            jQuery.each( res.data, function( i, val ) {
              if(i==6){
                $('#phonenumber').text(val);
                console.log(val);
              } if(i==7){
                $('#altNo').val(val);
              } if(i==4){
                $('#callerName').text(val);
              } if(i==3){
                SLCode = val;
                // alert("SLCode "+SLCode);
              }
            //  if(val.key!='customer_no') {              
            //    $('#customer_form input[name = "'+val.key+'"]').val(val.value);
            //    if(val.key=='caller_type'){
            //      $("#caller_type").val(val.value)
                // .find("option[value=" + val.value +"]").attr('selected', true);
            //    }               
            //  }                     
              });
            var NSLCode = "";
            
            // kloudq API call on dialed call
            if(calltype!='INBND'){
              NSLCode = SLCode ;
              calltype="OUTBOND";
            }else{
              calltype="INBOUND";
            } 
            console.log("NSLCode======="+NSLCode);
            console.log("calltype======="+calltype);
            logWebLinkResponse("Get Web Link Called");
            logWebLinkResponse("Input LOGINID "+user);
            logWebLinkResponse("Input Password "+password);
            logWebLinkResponse("Input MobileNo "+lastTenNumber);
            logWebLinkResponse("Input RetailerCode "+NSLCode);
            logWebLinkResponse("Call Type "+calltype);
            $.ajax({
              type:'GET',
              dataType:'JSON',
              data:{'LoginID':user,'Password':password,'MobileNo':lastTenNumber,'RetailerCode':NSLCode,'TCAgentKey':user,'calltype':calltype},
              // url:'https://beatvistaartest.kloudqapps.net/Account/GetRetailerWebLink',
              // url:'https://beatvistaar.kloudqapps.com/Account/GetRetailerWebLink',
              // url:'https://dms.vyapaar-vistaar.in/dms_telecalling/getWebLink',
              url:'https://tcob.vyapaar-vistaar.in/dms_telecalling/getWebLink',
              beforeSend: function(){
               $("#open_iframe").html("<img src='./images/img_loader.gif' width='100%' height='80%'>");
             },
            success:function(res){
                console.log("esuccesss");
                console.log(res);
                logWebLinkResponse(res);
                if(res.success==true){
                    is_web_link_called =1;
                    console.log("GetRetailerWebLink :");
                    console.log(res);
                    var webLink = res.message;
                    console.log(webLink);
                    $("#open_iframe").html("<iframe src='"+webLink+"' onload='displayMessage("+user+","+mobilenumber+")' name='myFrame' width='100%' height='100'></iframe>");
                }else{
                    logWebLinkResponse("Error =>"+res);
                }
              },
            error:function(res){
              console.log("erorrrrrrrrrrrrrrr");
              console.log(res);
              console.log("eror ajay");
              logWebLinkResponse(res);
              console.log("GetRetailerWebLink : "+res);
              console.log('LoginID '+user+' Password'+' '+' MobileNo '+lastTenNumber+' SLCode '+NSLCode+' TCAgentKey '+user);
              $("#open_iframe").html("<p>"+res+"</p>");
            }

            });         
        },
        error:function(res){
          logWebLinkResponse(res);
          console.log(res);
        }
      });

    }

     function logWebLinkResponse(res){
      $.ajax({
        type:'POST',
        dataType:'JSON',
        data:{'res':res},
        url:'ajaxGetWebLinkRequest.php',
        success:function(resnew){
            console.log('===called===');
            console.log(resnew);
        }
      })
    }


    function displayMessage(user,mobilenumber) {
      console.log(user);
      logWebLinkResponse("IFRAME OPEN => "+user);
      logWebLinkResponse("IFRAME mobilenumber =>"+mobilenumber);

    }

    function getTicketDateTime(){
      var fullDate = new Date();
      var day   = fullDate.getDate()+"";if(day.length==1) day="0" +day;
      var month = fullDate.getMonth()+parseInt(1) +"";if(month.length==1)  month="0" +month;
      var year  = fullDate.getFullYear();
      // var date  = year+'-'+month+'-'+day;
      var date  = day+'-'+month+'-'+year;
      var hour = fullDate.getHours();
      var minute = fullDate.getMinutes();
      var second = fullDate.getSeconds();
      if(hour < 10){
        hour = "0"+hour;
      }
      if(minute < 10){
        minute = "0"+minute;
      }
      if(second < 10){
        second = "0"+second;
      }
      var time  = hour + ":" + minute + ":" + second;
      return date+' '+time;
    }

    function showDynamicFields(field_data){         
      // console.log(field_data);
      field_data_replacement = field_data.replace(/(\r\n|\n|\r)/g," ");
      $('#ticket_form .dynamicFields').remove();  
      var dynamic_keys = [];
      var ticket_data = JSON.parse(field_data_replacement);
      var root_id=0;
      var parent_id=0;
      var child_id=0;
      $.each(ticket_data,function(index,key_data){   
       // console.log(index+'===='+key_data);
        if(index=='tickettype'){                          
          console.log('root '+root_id+'=== Parent '+parent_id+' child id '+child_id);           
          // $('#ticket_type option[value="'+key_data+'"]').attr("selected");
          root_id = key_data
          if(parent_id>0){
            appendParent(root_id,parent_id);                      
          }
          if(child_id>0){
            appendChild(parent_id,child_id);                      
          }
        }
        if(index=='parent'){ 
          parent_id=key_data;         
        }
        if(index=='child'){    
          child_id = key_data;            
        }

          if(index.indexOf('ty_') != -1){
            dynamic_keys.push(index);
      }                     
        });
        if(dynamic_keys.length>0){
          showDynamicFieldsList(dynamic_keys,ticket_data);

        }
        // $('#ticket_form .dynamicFields').find(':input').attr('disabled',true);
        $(".update_ticket").css('display','block');  
        $("#save_ticket").css('display','none');                       
    }
    
     function customer_opned_tickets(mobilenumber){
     
      var customer_mobile = mobilenumber;
      var html = '';
      $.ajax({
        type:'POST',
        dataType:'JSON',
        data:{'phone_number':customer_mobile,'ticket_type':1},
        url:'view_history.php',
        success:function(res){
                //var result = JSON.parse(data);
                if(res.data!=null){
            var html = '<h3>Open Ticket History</h3><table border="0" cellpadding="5" cellspacing="0" width="90%" style="margin: 20px 0;background-color: #f5f5f5;" class=history-table>';
                  var html  =html+'<tr><td>Ticket Id</td><td>Assign By</td><td>Child Node</td><td>Ticket Status</td> <td>Created Date</td> <td>Action</td></tr>';    
                  var j=1;
                        
                  jQuery.each( res.data, function( i, val ) {
                      // console.log(i);
                      // console.log(val);
                       //console.log(val.id);
                      html = html+'<tr class="tr-open" data-ticketId='+val.id+' ticket-status='+val.ticket_status+'><td>'+val.id+'</td><td>'+val.agent+'</td><td>'+val.child_node+'</td><td>'+val.ticket_status+'</td><td>'+val.creat_at+'</td> <td>'+val.action+'</td></tr>';
                    j++;
                  });
                var html2 = html+'</table>';
                document.getElementById("history_span").innerHTML = html2;
                document.getElementById("history_span").style.display = "block";
              }  
              else{
                $('#history_span').css('display','none');
              } 
        }
      });
    }

    function is_call_hangedup(mobilenumber,lead_id){
      $.ajax({
        type:'POST',
        dataType:'JSON',
        data:{'mobile_no':mobilenumber,'lead_id':lead_id,'action':'getCallStatusByLead'},
        url:'ajaxRequest.php',
        success:function(res){
          if(res.success==1){              
            if(res.row>0){
              previois_call_ticket = 2; //for status OnCall
              document.getElementById("HangupControl").innerHTML = "<a href=\"#\" onclick=\"dialedcall_send_hangup('','','','','YES');\"><button type=\"button\">Release</button></a>";
            }
            else{
              previois_call_ticket = 1; // for status ACW
            }
            return previois_call_ticket;
          }
        }
      });
    }

    function update_vicidial_live_agents(user,status){
      $.ajax({
        type:'POST',
        dataType:'JSON',
        data:{'user':user,'status':status,'action':'updateVicidiaLiveAgents'},
        url:'ajaxRequest.php',
        success:function(res){
         // console.log("======================================|||||");
         // console.log(res);
        },
        error:function(res){
          // console.log(res);
        }
      });
    }

    function update_status_in_db(current_status="",last_number=""){
      $.ajax({
        type:'POST',
        dataType:'JSON',
        data:{'user':user,'status':current_status,'last_number':last_number,'action':'updateCurrentStatus'},
        url:'ajaxRequest.php',
        success:function(res){
      // console.log(res);
        },
        error:function(res){
          // console.log(res);
        }
      });
    }

    function kloudq_api_call_agentStatusChanged(current_status){
      var currentdate = new Date(); 
    var date = currentdate.getDate(); if(date.length==1) date="0"+date;
    var year = currentdate.getFullYear(); 
    var month = currentdate.toLocaleString('default', { month: 'long' });
    // var month = (currentdate.getMonth()+1); if(month.length==1) month="0"+month;
    
    
    var datetime =   date + "-" + month + "-" + year;
      $.ajax({
        type:'GET',
        dataType:'JSON',
        data:{'LOGINID':user,'STATUSID':'0','STATUSDESC':current_status,'STATUSDATETIME':datetime,'Token':'PSLAdm!n2@2@'},
        // url:'https://beatvistaartest.kloudqapps.net/api/TeleIntegration/agentStatusChanged',
        // url:'https://beatvistaar.kloudqapps.com/api/TeleIntegration/agentStatusChanged',
         // url:'https://dms.vyapaar-vistaar.in/dms_telecalling/getWebLink',
         url:'https://tcob.vyapaar-vistaar.in/dms_telecalling/getWebLink',


        // url:'https://beatvistaar.kloudqapps.com/api/TeleIntegration/agentStatusChanged',
        // headers: { 'x-auth-token': 'PSLAdm!n2@2@' },
        success:function(res){
      // console.log("agentStatusChanged_API : ");
      // console.log(res);
        },
        error:function(res){
          // console.log("agentStatusChanged_API : ");
          // console.log(res);
        }
      });
    }

    function kloudq_api_call_toggleSetBreak(break_id,break_desc){
      var currentdate = new Date(); 
    var date = currentdate.getDate(); if(date.length==1) date="0"+date;
    var year = currentdate.getFullYear();
    // var month = (currentdate.getMonth()+1);
    var month = currentdate.toLocaleString('default', { month: 'long' })
    var hours = currentdate.getHours(); if(hours < 10){ hours = "0"+hours; }
    var minutes = currentdate.getMinutes(); if(minutes < 10){ minutes = "0"+minutes; }
    var seconds =  currentdate.getSeconds(); if(seconds < 10){ seconds = "0"+seconds; }

    var datetime =   date + "-" + month + "-" + year + " " + hours + ":" + minutes + ":" + seconds;
      $.ajax({
        type:'GET',
        dataType:'JSON',
        crossDomain: true,
        data:{'LOGINID':user,'BREAKID':break_id,'BREAKDESC':break_desc,'BREAKDATETIME':datetime,'Token':'PSLAdm!n2@2@'},
      //   headers: {
      //    'x-auth-token': 'PSLAdm!n2@2@',
        // 'Content-Type': 'application/json',
        // 'Access-Control-Allow-Origin': 'X-Requested-With'
        // },
        // url:'https://beatvistaartest.kloudqapps.net/api/TeleIntegration/toggleSetBreak',
        

        // url:'https://beatvistaar.kloudqapps.com/api/TeleIntegration/toggleSetBreak',
        // url:'https://dms.vyapaar-vistaar.in/dms_telecalling/getWebLink',
        url:'https://tcob.vyapaar-vistaar.in/dms_telecalling/getWebLink',

        // url:'https://beatvistaar.kloudqapps.com/api/TeleIntegration/toggleSetBreak',
        success:function(res){
      // console.log("toggleSetBreak : ");
      // console.log(res);
        },
        error:function(res){
          // console.log("toggleSetBreak : ");
          // console.log(res);
        }
      });
    }

    function update_vicidial_list(phone){
      $.ajax({
        type:'POST',
        dataType:'JSON',
        data:{'phone':phone,'action':'updateVicidialStatus'},
        url:'ajaxRequest.php',
        success:function(res){
      console.log("update vicidial_list "+res);
      console.log(res);
      console.log(phone);

        },
        error:function(res){
          console.log("update vicidial_list "+res);
          console.log(res);
        }
      });
    }

    //tekdial-sidepanel-show-hide
    $(".clickOpenTekdialSidebar").click(function(){
      $(".tekdial-sidepanel").addClass("tekdial-sidepanel-show");
      $(".tekdial-sidepanel-overlay").addClass("tekdial-sidepanel-overlay-show");
    });
    $(".tekdial-sidepanel-overlay, .tekdial-sidepanel-close").click(function(){
      $(".tekdial-sidepanel").removeClass("tekdial-sidepanel-show");
      $(".tekdial-sidepanel-overlay").removeClass("tekdial-sidepanel-overlay-show");
    });
    //tekdial-sidepanel-show-hide

</script>
</html>
