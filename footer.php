    <table bgcolor="<?php echo $MAIN_COLOR ?>" id="MaiNfooter"  width="100%" >
      <tr style="display: none;" height="32px">
        <td height="32px">
          <font face="Arial,Helvetica" size="1">
            <?php echo _QXZ("VERSION:"); ?> <?php echo $version ?> &nbsp; <?php echo _QXZ("BUILD:"); ?> <?php echo $build ?> &nbsp; &nbsp; <?php echo _QXZ("Server:"); ?> <?php echo $server_ip ?>  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
          </font><br />
          <font class="body_small">
           <span id="busycallsdisplay"><a href="#"  onclick="conf_channels_detail('SHOW');"><?php echo _QXZ("Show conference call channel information"); ?></a>
            <br /><br />&nbsp;
          </span>
          </font>
        </td>
      <td align="right" height="32px">
      </td>
    </tr>
    <tr style="display: none;">
      <td colspan="3"><span id="outboundcallsspan"></span></td>
    </tr>
    <tr style="display: none;">
      <td colspan="3"><font class="body_small">
        <span id="AgentAlertSpan">
      <?php
      if ( (preg_match('/ON/',$VU_alert_enabled)) and ($AgentAlert_allowed > 0) )
        {echo "<a href=\"#\" onclick=\"alert_control('OFF');return false;\">"._QXZ("Alert is ON")."</a>";}
      else
        {echo "<a href=\"#\" onclick=\"alert_control('ON');return false;\">"._QXZ("Alert is OFF")."</a>";}
      ?>
       </span>
     </font>
   </td>
 </tr>
    <tr style="display: none;">
      <td colspan="3">
  <font class="body_small">
  </font>
    </td>
  </tr>
  <!-- -------------------------OLD buttons-------------------------------- -->
  <tr style="display: none;">
    <td align="left">
      <font class="body_text">
        <span id="DiaLControl">
          <a href="#" onclick="ManualDialNext('','','','','','0','','','YES');"><img src="./images/<?php echo _QXZ("vdc_LB_dialnextnumber_OFF.gif"); ?>" border="0" alt="Dial Next Number" /></a>
        </span>
        <span id="ManualQueueNotice"></span>
        <span id="ManualQueueChoice"></span>
        <span id="DiaLLeaDPrevieW">
          <font class="preview_text"> <input type="checkbox" name="LeadPreview" size="1" value="0" /> <?php echo _QXZ("LEAD PREVIEW"); ?></font>
        </span>
        <span id="DiaLDiaLAltPhonE">
          <font class="preview_text">
          <input type="checkbox" name="DiaLAltPhonE" size="1" value="0" <?php echo $alt_phone_selected ?>/><?php echo _QXZ(" ALT PHONE DIAL"); ?>
          </font>
        </span>
        <font class="skb_text">
          <span id="NexTCalLPausE"> <a href="#" onclick="next_call_pause_click();return false;"><?php echo _QXZ("Next Call Pause"); ?></a> </span>
        </font>
      </font>
    </td>
    <td>
      <font>
        <!-- <?php if ( ($manual_dial_preview) and ($auto_dial_level==0) )
            {echo "<font class=\"preview_text\"> <input type=\"checkbox\" name=\"LeadPreview\" size=\"1\" value=\"0\" /> LEAD PREVIEW<br /></font>";}
            if ( ($alt_phone_dialing) and ($auto_dial_level==0) )
            {echo "<font class=\"preview_text\"> <input type=\"checkbox\" name=\"DiaLAltPhonE\" size=\"1\" value=\"0\" /> ALT PHONE DIAL<br /></font>";}
            ?> -->
      </font>
    </td>
    <td>
      <font>
        <?php echo _QXZ("RECORDING FILE:"); ?>
        <font class="body_tiny">
          <span id="RecorDingFilename"></span>
        </font>
      </font>
    </td>
    <td>
      <font>
        <?php echo _QXZ("RECORD ID:"); ?>
        <font class="body_small">
          <span id="RecorDID"></span>
        </font>
      </font>
    </td>
    <td>
      <font>
        <span id="RecorDControl">
          <a href="#" onclick="conf_send_recording('MonitorConf',session_id,'','','','YES');return false;"><img src="./images/<?php echo _QXZ("$start_recording_GIF"); ?>" border="0" width="110px" height="25px" alt="Start Recording" />
          </a>
        </span>
      </font>
    </td> 
    <td>
      <font>
        <?php if (!preg_match("/NOGAP/",$SSrecording_buttons))
                {
                  // echo "<span id=\"SpacerSpanA\"><img src=\"./images/"._QXZ("blank.gif")."\" width=\"145px\" height=\"16px\" width=\"110px\" height=\"25px\" border=\"0\" /></span>\n";
                }
                // echo "</font></td><td><font>";
            if ($SSenable_first_webform > 0)
              {echo "<span id=\"WebFormSpan\"><img src=\"./images/"._QXZ("vdc_LB_webform_OFF.gif")."\" width=\"110px\" height=\"25px\" border=\"0\" alt=\"Web Form\" /></span>";}
            if ($enable_second_webform > 0)
              {echo "<span id=\"WebFormSpanTwo\"><img src=\"./images/"._QXZ("vdc_LB_webform_two_OFF.gif")."\" width=\"110px\" height=\"25px\" border=\"0\" alt=\"Web Form 2\" /></span>";}
            if ($enable_third_webform > 0)
              {echo "<span id=\"WebFormSpanThree\"><img src=\"./images/"._QXZ("vdc_LB_webform_three_OFF.gif")."\" width=\"110px\" height=\"25px\" border=\"0\" alt=\"Web Form 3\" /></span>";}
        ?>
      </font>
    </td> 
    <td align="left" width="67px" valign="middle">
      <a href="#" onclick="ScriptPanelToFront('YES');"><img src="./images/<?php echo _QXZ("vdc_tab_script.gif"); ?>" alt="SCRIPT" width="67px" height="30px" border="0" /></a>
    </td>
    <td>
      <font>
        <span id="SpacerSpanC"><img src="./images/<?php echo _QXZ("blank.gif"); ?>" width="110px" height="25px" border="0" /></span><br />
      </font>
    </td>
    <td>
      <font>
        <span style="background-color: <?php echo $MAIN_COLOR ?>" id="RecorDMute"></span>
      </font>
    </td>
    <td>
      <font>
        <center>
          <div class="text_input" id="SendDTMFdiv"><span id="SendDTMF"><a href="#" onclick="SendConfDTMF(session_id,'YES');return false;"><img src="./images/<?php echo _QXZ("vdc_LB_senddtmf.gif"); ?>" border="0" width="90px" height="20px" alt="Send DTMF" align="bottom" /></a>  <input type="text" size="5" name="conf_dtmf" class="cust_form" value="" maxlength="50" /></span></div>
        </center>
      </font>
    </td>
  </tr>
</table>