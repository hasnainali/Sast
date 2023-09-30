<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src='https://kit.fontawesome.com/a076d05399.js'></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<!-- <link href="https://ericjgagnon.github.io/wickedpicker/wickedpicker/wickedpicker.min.css" rel="stylesheet" type="text/css" /> -->
<!-- <script src="https://ericjgagnon.github.io/wickedpicker/wickedpicker/wickedpicker.min.js" type="text/javascript"></script> -->

<script>

$(document).ready(function(){
    var agent_status = $("#break-status").html();
    if(agent_status!=null){
       startStop(); //start idle time clock      
    }        
    $("#endbreak").click(function(e){
      e.preventDefault();
      var break_id = $("#breakid").val();
      if(break_id>0){
        $.ajax({
          type: "POST",
          url: "<?php echo 'end_break.php'; ?>",
          data: {break_id:break_id},
          cache: false,
          success: function(data){
            CFAI_sent=0;
            is_onbreak=0;
            break_text='';
            $("#myBtn").show();
            $("#endbreak").hide();
            $("#breakid").val("");
            //$("#breakStatus").hide();
            //$("#break-status").html("Idle");        
            $('#break_type').prop('selectedIndex',0);
            kloudq_api_call_toggleSetBreak('0','End break');
          }
        }); 	
        AutoDial_ReSume_PauSe('VDADready','','','','','','','YES');
      }
    });
$('.close').click(function(){
  $('#makeCall').css('display','none');
  $('#transferCall').css('display','none');
  $('#loginHourModel').css('display','none');
  $('#myModal').css('display','none');
});

$('.close-btn').click(function(){
  $('#makeCall').css('display','none');
  $('#transferCall').css('display','none');
});
  
$('#endbreak').click(function(){
  $('#my-btn').attr('disabled','true');
});


	$(".frm").change(function(){
     var  user = $("#VD_login").val(); 
     var VD_pass = $("#VD_pass").val();
     $.ajax({
		  type: "POST",
		  url: "<?php echo 'vdc_db_query.php'; ?>",
		  data: {user:user,ACTION:'LogiNCamPaigns_new',pass:VD_pass,format:"html" },
		  cache: false,
		  success: function(data){
		   console.log(data);
		   $("#campaignShow").show();
		    //$("#LogiNCamPaigns2").html("");
		   $("#campaignShow").html(data).trigger("create");
		}
		});   
	});

   /* $("#history_span").on("click","#actionClose",function(e){
    	alert();
    	 //$("#ticketAction").hide();
    }); */	

     $("#actionClose").click(function(){
    	alert();
    	$("#ticketAction").hide();
    }); 


	// $("#history_span").on("click",".ticket-action",function(e){
 //    e.preventDefault();
 //    $("#ticketAction").show();
 //    var ticket_id =  $(this).data("ticket_id");
 //    var phone_no = $(this).data("phone_no");
 //    $.ajax({
	// 	  type: "POST",
	// 	  url: "<?php echo 'ticket_action.php'; ?>",
	// 	  data: {ticket_id:ticket_id,phone_no:phone_no},
	// 	  cache: false,
 //      success: function(data){
 //        // console.log(data);
 //        var result = JSON.parse(data);
 //        var count = Object.keys(result.data).length;
 //        var j=count;  
 //        var html = "";                  
 //        jQuery.each( result.data, function( i, val ) {   
 //          var action_id =val.id;
 //          // html = html+'<tr class="actr"><td>'+val.ticket_id+'</td><td>'+val.ticket_status+'</td><td>'+val.ticket_type+val.ticket_parent+val.ticket_child+'</td> <td>'+val.ticket_closure+'</td> <td>'+val.seconds+'</td> <td>'+val.comments+'</td> <td>'+val.creat_at+'</td></tr>';
 //          html += '<table  id="ticketactionid'+val.id+'" cellpadding="5" cellspacing="0" border="0" width="100%" style="text-align: center; width: 95% !important;    margin: 15px auto; border: 1px solid #cccccc;" class="history-details-table"><thead><tr style="background-color:#244a76 !important"><th width="20%">Ticket No: '+val.ticket_id+'</th><th width="20%">Source: voice</th><th width="20%">Type: '+val.source+'</th><th width="20%">Campaign: L1</th><th width="20%">Action No.: '+j+'</th><th></th></tr></thead>';

 //          html+='<tbody><tr class="actr"><td colspan="2">Ticket Start Time: '+val.ticketAt+'</td><td colspan="2">Ticket End Time: '+val.creat_at+'</td><td>Duration: '+val.duration+'</td></tr><tr><td colspan="2">Status: <select disabled><option>'+val.ticket_status+'</option></select></td><td colspan="2">Assigned By: <select disabled><option>'+val.agent+'</option></select></td><td>Updated By: <select disabled=""><option>'+val.agent+'</option></select></td></tr><tr><td>Clouser Type:</td><td colspan="4" align="left">'+val.ticket_closure+'</td></tr><tr><td>Tree:</td><td colspan="4" align="left">'+val.ticket_type+'->'+val.ticket_parent+'->'+val.ticket_child+'</td></tr><tr><td>Agent Remark:</td><td colspan="4" align="left"><textarea disabled="" rows="4" style="width:700px;">'+val.comments+'</textarea></td></tr>';
 //            $.each(val.clouserfields, function( key, value ) {
 //              html = html+'<tr><td>'+key+'</td><td colspan=4 align=left><input type=text value='+value+' disabled></td></tr>';
 //            });  
 //            var field_data = val.ticket_data;        
 //            showDynamicFieldsInTicketHistory(field_data,action_id)
 //            html = html+'</tbody><table>';
 //            j--;
 //        }); 
 //        $('#actiontb').html(html);                 
 //      }
 //    });
 //  });


  // function showDynamicFieldsInTicketHistory(field_data,action_id){    
  //   field_data_replacement = field_data.replace(/(\r\n|\n|\r)/g," ");
  //     // console.log('function call');
  //     $('#ticketactionid'+action_id+' .dynamicFieldsHistory').remove();  
  //     var dynamic_keys = [];
  //     var ticket_data = JSON.parse(field_data_replacement);
  //     $.each(ticket_data,function(index,key_data){          
  //         if(index.indexOf('ty_') != -1){
  //           dynamic_keys.push(index);
  //         // console.log(index+'===='+key_data);
  //     }                     
  //       });
  //       if(dynamic_keys.length>0){          
  //         showHistoryDynamicFieldsList(dynamic_keys,ticket_data,action_id);
  //       }
  //   }

  // function showHistoryDynamicFieldsList(dynamic_keys,ticket_data,action_id){     
  //   $.ajax({
  //       type:'POST',
  //       dataType:'JSON',
  //       // data:{'customer_form':customer_data,'ticket_form':ticket_data,'action':'saveAgentRequest'},
  //       data:{action:'getDynamicFields',form_fields:dynamic_keys},
  //       url:'ajaxRequest.php',
  //       success:function(res){
  //         // console.log(res);
  //         if(res.success==1){              
  //           appendHistoryField(res,ticket_data,action_id);
  //           // setFieldvalue(ticket_data);
  //         }else{
  //           alert(res.message);
  //           return false;
  //         }
  //       }
  //   });      
  // }

   //Dynamic fields of ticket types 
  function appendHistoryField(res,ticket_data,action_id){
    var ticketField='';
    var field_name ='ty_name';      
    $('#ticketactionid'+action_id+' .dynamicFieldsHistory').remove();
    jQuery.each(res.ticketFields, function( i, val ) { 
        var required ="";
        if(val.is_required==1){
          required = 'required';
        }        
         var field_name = val.name;         
        if(val.field_type=='text' || val.field_type=='calender') {
          ticketField += '<tr class="dynamicFieldsHistory"><td><font>'+val.label+'</font></td><td colspan="4" align="left"><input type='+val.field_type+' name="'+val.name+'" '+required+' value="'+ticket_data[field_name]+'" disabled class="form-control"></td></tr>';
           
        } 
        else if(val.field_type=='select'){
          ticketField += '<tr class="dynamicFieldsHistory"><td><font>'+val.label+'</font></td><td colspan="4" align="left"><select name ="'+val.name+'" class="form-control" '+required+' disabled><option value="" >Select '+val.label+'</option>';
          // console.log(val.fieldvalues);
          var fields = JSON.parse(val.fieldvalues);
          jQuery.each(fields, function(j, k){
            if(k.is_visible==1){  
              if(k.value==ticket_data[field_name]){
                ticketField += '<option value="'+k.value+'" selected >'+k.key+'</option>';
              } else{                
                ticketField += '<option value="'+k.value+'"  >'+k.key+'</option>';
              }               
            }
          });
          ticketField += '</select></td></tr>';
        }
        else if(val.field_type=='checkbox'){
          ticketField += '<tr class="dynamicFieldsHistory"><td><font>'+val.label+'</font></td><td colspan="4" align="left">';
          // console.log(ticket_data[field_name]);
          var fields = JSON.parse(val.fieldvalues);          
          jQuery.each(fields, function(j, k){              
            if(k.is_visible==1){             

              if(jQuery.inArray(k.value,ticket_data[field_name]) !== -1){              
                ticketField += '<input type="'+val.field_type+'" name="'+val.name+'[]" value="'+k.value+'" class="'+required+' is_required" checked disabled>'+k.key;
              }else{
                ticketField += '<input type="'+val.field_type+'" name="'+val.name+'[]" value="'+k.value+'" class="'+required+' is_required" disabled>'+k.key;
              }
            }
          });           
          ticketField +='</td></tr>';
        }
        else if(val.field_type=='radio'){
          ticketField += '<tr class="dynamicFieldsHistory"><td><font>'+val.label+'</font></td><td colspan="4" align="left">';
          // console.log(val.fieldvalues);
          var fields = JSON.parse(val.fieldvalues);          
          jQuery.each(fields, function(j, k){              
            if(k.is_visible==1){      
              // console.log(k.value+'=='+ticket_data[field_name]);
              if(k.value==ticket_data[field_name]){
                ticketField += '<input type="'+val.field_type+'" name="'+val.name+'" value="'+k.value+'" '+required+' checked disabled>'+k.key;
              }else{
                ticketField += '<input type="'+val.field_type+'" name="'+val.name+'" value="'+k.value+'" '+required+' disabled>'+k.key;
              } 
            }
          });           
          ticketField +='</td></tr>';
        }
        else if(val.field_type=='textarea'){
          ticketField += '<tr class="dynamicFieldsHistory"><td><font>'+val.label+'</font></td>';
          ticketField += '<td colspan="4" align="left"><textarea name="'+val.name+'" '+required+' disabled>'+ticket_data[field_name]+'</textarea>';
          ticketField +='</td></tr>';
        }
        else if(val.field_type=='time'){
           ticketField += '<tr class="dynamicFieldsHistory"><td><font>'+val.label+'</font></td><td colspan="4" align="left"><input type="text" id="time_picker" name="'+val.name+'" '+required+' value="'+ticket_data[field_name]+'" disabled class="form-control"></td></tr>';
        }
        else if(val.field_type=='date'){
          ticketField += '<tr class="dynamicFieldsHistory"><td><font>'+val.label+'</font></td><td colspan="4" align="left"><input type='+val.field_type+' name="'+val.name+'" '+required+' value="'+ticket_data[field_name]+'" disabled class="form-control"></td></tr>';
        }                                   
      }); 
      $('#ticketactionid'+action_id+' tr:last').before(ticketField);           
  }



   //$("#ticket_form").click(function(){
         //customer_opned_tickets("9889520019");
         //customer_opned_tickets_field("9889520019");
   //});
   
   
  $(document).on("click",".tr-open",function(e){  
   //var id = $(this).closest("tr").find('td:eq(3)').text();
    var ticket_id = $(this).data("ticketid");
    var ticket_status = $(this).attr("ticket-status"); 
    if(ticket_status=='Close'){
      // alert('Ticket has already closed');
      return false;
    }
    $.ajax({
		  type: "POST",
		  url: "<?php echo 'ticket_detail.php'; ?>",
		  data: {ticket_id:ticket_id},
		  cache: false,
		  success: function(data){
        var result = JSON.parse(data);
        // console.log(result);
        jQuery.each(result.data, function( i, val ) {
          //$('[name="assign_by"]').val(val.agent);           
          showDynamicFields(val.ticket_data);
          $('#clouserlabel').css('display','none');
          $('#clouserinput').css('display','none');
          $("#comments").val(val.comments);
          $("#ticket_id").val(val.ticket_id);
          $('#ticket_number').html(val.ticket_id);
          $('#action_no').html(val.total_actions);
          $('#ticketstatus').val(val.ticket_status);
          $('#tickettype_detail').html('Tree: '+val.ticket_tree);
          // $('[name="tickettype"]').val(val.tickettype);          
          
		    }); 
        $("#save_ticket").css('display','none');                      
        $(".update_ticket").css('display','block');                     
        $('#ticket_form').closest('table').find(':input').attr('readonly',true);
        $('#ticket_form').closest('table').find('select').attr('disabled',true);
        $(".update_ticket").removeAttr('readonly');
      }
    });       
  }); 
  
  $("#break_type").change(function(){
   var id = $(this).val();
  
        if(id.length>0){
       
       $("#my-btn").attr("disabled",false);
   }
   else{
       $("#my-btn").attr("disabled",true);
   }
    
  });


  $('#transfer').on('click',function(){
    var agent_status = document.getElementById("break-status").textContent;
    var status = '';
    if(agent_status=='Oncall'){
      // ShoWTransferMain('ON','','YES');
      $('#transferCall').css('display','block');
    }else{
      alert_box('You can transfer call OnCall state');
    }
  })

  $('#transfer_hang').click(function(){
    mainxfer_send_redirect('FROMParK',lastcustchannel,'','','','','YES');
    // dialedcall_send_hangup('','','','','YES');
    leave_3way_call('FIRST','YES');
  });

  $('#transfer_call').on('click',function(){
    is_makeCall = 0;
    var agent_status = document.getElementById("break-status").textContent;
    var status = '';
    if(agent_status=='Oncall'){
      console.log('lastcustchannel '+lastcustchannel+' lastcustserverip '+lastcustserverip);
      var XfeRSelecT = document.getElementById("XfeRGrouP");
      console.log(XfeRSelecT);
      var XfeR_GrouP = XfeRSelecT.value;
      var skills = document.getElementById("skillTransfer");
      var selected_skill = skills.value;
      $('#transfer_call').css('display','block');
      $('#transfer_hang').css('display','none');
      // $('#xfernumber_transfer').val('');
      $('#xfernumber').val('');

      if(XfeR_GrouP!=''){
        // alert("campaign transfer: "+XfeR_GrouP);
        $.ajax({
              type: "POST",
              dataType:'JSON',
              url: "skill_transfer.php",
              data: {inbound_group:XfeR_GrouP},
              cache: false,
              success: function(result){
                if(result.data>0){
                  document.vicidial_form.consultativexfer.checked=true;
                  xfer_park_dial('YES');
                }
                else{
                  alert_box("Sorry! No agent available now, Please try again later !");
                  return false;
                }
              }
            }); 
      }
      else if(selected_skill!=''){
        $('#xfernumber_transfer').val(selected_skill);
        // -----------------------------checking user availability---------------------------------------
        $.ajax({
              type: "POST",
              dataType:'JSON',
              url: "skill_transfer.php",
              data: {extension:selected_skill},
              cache: false,
              success: function(result){
                if(result.data>0){
                  document.vicidial_form.consultativexfer.checked=true;
                  var inbound_group = $("#inbound_group").val();
                  // alert("skill transfer: "+inbound_group);
                  xfer_park_dial('YES');
                }
                else{
                  alert_box("Sorry! No agent available now, Please try again later !");
                  return false;
                }
              }
            }); 
        // ------------------------------------------------------------------------------------------------------------------------
      }
      else{
       var manual_number = document.getElementById('xfernumber_transfer').value;
        document.vicidial_form.consultativexfer.checked=false;
        xfer_park_dial('YES');
        // mainxfer_send_redirect('XfeRBLIND',lastcustchannel,lastcustserverip,'','','','YES');
      }
    }else{
      alert_box('You can transfer call on call state');
    }
  });


  $('#HangupXferLine').on('click',function(){
    $('#xfernumber').val('');
    $('#HangupXferLine').css('display','none');
    $('#HangupBothLines').css('display','none');
    $('#xfernumber_transfer').val('');
  });

  $('#HangupBothLines').on('click',function(){
    $('#xfernumber').val('');
    $('#HangupXferLine').css('display','none');
    $('#HangupBothLines').css('display','none');
    $('#xfernumber_transfer').val('');
  });



  $('#expert_hang').on('click',function(){
      $('#transfer_hang').css('display','none');
      $('#transfer_call').css('display','block');
  });

     $('.history').click(function() {
      var html = "";
      var form = document.getElementById("history-form");
      // document.getElementsByClassName("history").addEventListener("click", function () {

        
        var type = $(this).attr('data-type');
        if(type=='view_history'){
          var phone_number = document.getElementById("phonenumber").value;
          var status = '';
        }
        if(type=='search_history'){
          var phone_number = document.getElementById("searchby").value;
          var agent_status = document.getElementById("break-status").textContent;
          var status = '';
          if(agent_status=='Oncall' || agent_status=='ACW'){
            alert('You can not Search! You are still on call');
            return false;        
          }
        }
        var agc_id= "<?php echo $VD_login; ?>";
        if(type=='agent_ticket_history'){
          var status = $(this).attr('status');
          var phone_number = '';
          var agc_id = $('#dashboard').attr('agent');
          var fromDate = $('#fromDate').val();
          var toDate = $('#toDate').val();
        }
        // alert(phone_number);
        // var phone_number = "9889520019";
        
         
       // Create our XMLHttpRequest object
        var hr = new XMLHttpRequest();        
        // Create some variables we need to send to our PHP file
        var url = "view_history.php";
        //var  = document.getElementById("fname").value;
        //var ln = document.getElementById("lname").value;
        var vars = "phone_number="+phone_number+"&agc_id="+agc_id+"&status="+status+"&fromDate="+fromDate+"&toDate="+toDate;
        hr.open("POST", url, true);
        hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        // Access the onreadystatechange event for the XMLHttpRequest object
        hr.onreadystatechange = function() {          
            if(hr.readyState == 4 && hr.status == 200) {
                // var return_data = hr.responseText;  
                var result = JSON.parse(hr.responseText);
                // console.log(result);
              
                if(result.data==''||result.data==null){
                  // alert("No data");
                  html = '<table border="0" cellpadding="5" cellspacing="0" width="90%" style="margin: 20px 0;background-color: #f5f5f5;" class=history-table><h3>History</h3><p>No Data Found !</p></table>';
                  document.getElementById("history_span").innerHTML = html;
                  $('#history_span').css('display','');
                }
            else{
              var html = '<table border="0" cellpadding="5" cellspacing="0" width="90%" style="margin: 20px 0;background-color: #f5f5f5;" class=history-table><h3>History</h3>';
              var html  =html+'<tr><td>Ticket Id</td><td>Assign By</td><td>Child Node</td><td>Ticket Status</td> <td>Created Date</td> <td>Action</td></tr>';    
              var j=1;
                      
              jQuery.each( result.data, function( i, val ) {
                    // console.log(i);
                    // console.log(val);
                     //console.log(val.id);
                    html = html+'<tr class="tr-open" data-ticketId='+val.id+' ticket-status='+val.ticket_status+'><td>'+val.id+'</td><td>'+val.agent+'</td><td>'+val.child_node+'</td><td>'+val.ticket_status+'</td><td>'+val.creat_at+'</td> <td>'+val.action+'</td></tr>';
                 j++;
              });
              html2 = html+'</table>';
              document.getElementById("history_span").innerHTML = html2;
              $('#history_span').css('display','block');
                }
            }
        }
          hr.send(vars); // Actually execute the request  
      }); 
      $('.update_ticket').on('click',function(){          

        var ticket_status = $('#ticketstatus').val();
        if(ticket_status==2){
          alert_box('Closed ticket can not update');
          return false;
        }
        $("#save_ticket").css('display','block');                       
        $(".update_ticket").css('display','none');                 
        $('#ticket_form').closest('table').find(':input').attr('readonly',false);
        $('#ticket_form').closest('table').find('select').attr('disabled',false);
        $('#ticket_form').closest('table').find('textarea').attr('disabled',false);
        $('#assign_by_disable').attr('readonly',true);
      });       
  // });
    // $('#searchTicket').click(function() {
    //   var phone_number = document.getElementById("searchby").value;
    //   if(phone_number!=''){
    //       var agent_status = document.getElementById("break-status").textContent;
    //       var status = '';
    //         if(agent_status=='Oncall' || agent_status=='ACW'){
    //           alert('You can not Search! You are still on call');
    //           return false;        
    //         }
    //         else{
    //           $.ajax({
    //           type: "POST",
    //           url: "<?php echo 'ticket_detail.php'; ?>",
    //           data: {ticket_id:phone_number},
    //           cache: false,
    //           success: function(data){
    //             var result = JSON.parse(data);
    //             // console.log(result.data[0]);
    //             if(result.data[0]['total_actions']!=0){
    //                 phone_number = result.data[0]['mobile_number']; 
    //                 customer_details(phone_number);
    //                 $('#phonenumber').val(phone_number);
    //                 // customer_opned_tickets_field(phone_number);
    //                 jQuery.each(result.data, function( i, val ) {    
    //                 showDynamicFields(val.ticket_data);
    //                 var d = new Date(val.creat_at);
    //                 var date = d.getDate();
    //                 if(date < 10){
    //                   date = "0"+date;
    //                 }
    //                 var month = d.getMonth() + 1;
    //                 if(month < 10){
    //                   month = "0"+month;
    //                 }
    //                 var seconds = d.getSeconds();
    //                 if(seconds < 10){
    //                   seconds = "0"+seconds;
    //                 }
    //                 var time = d.getHours() + ":" + d.getMinutes() + ":" + seconds;
    //                 ticket_root = val.tickettype;
    //                   ticket_parent = val.parent;
    //                   ticket_child = val.child;
    //                 $('.ticketCall_time_view').css('display','none');
    //                 $('#lastCallDuration').html(val.total_duration);
    //                 $('#duration_span').css('display','flex'); 
    //                 $("#display_ticketTime").css('display','none');                  
    //                 $("#current_ticketTime").css('display','none');                  
    //                 $("#ticket_form").css('display','flex');
    //                 $('#ticket_type').attr('disabled',true);
    //                 $('#ticketstatus').attr('disabled',true);
    //                 $('#comments').attr('disabled',true);
    //                 $("#ticket_form_header").css('display','');
    //                 $('#ticketStart').css('display','flex');
    //                 $('#ticket_start').css('display','flex');
    //                 $('#ticket_start').html(val.ticketAt);
    //                 $('#endticket').css('display','flex');
    //                 $('#endticket_time').css('display','flex');
    //                 $('#endticket_time').html(date+'-'+month+'-'+ d.getFullYear() +' '+time);
    //                 if(val.ticket_status==2){
    //                   $('#clouserlabel').css('display','flex');
    //                   // $('#clouserinput').css('display','flex');
    //                   $(".update_ticket").css('display','none');
    //                   $('#closed_clouser').html(val.clousertype);
    //                   $('#closed_clouser').css('display','flex');
    //                   // $('select[name="clousertype"]').find('option[value="'+val.clousertype+'"]').prop('selected', 'selected');
    //                   // $('select[name^="clousertype"] option[value="'+val.clousertype+'"]').attr("selected","selected");
    //                 }  else{
    //                   $('#clouserlabel').css('display','none');
    //                   $('#clouserinput').css('display','none');
    //                   $('#closed_clouser').css('display','none');
    //                   $('#clouseroptions').removeAttr('required');
    //                 }
    //                 $('#SecondSDISP').css('display','none');
    //                 $('#tickettype_detail').html('Tree: '+val.ticket_tree);
    //                 $("#comments").val(val.comments);
    //                 $("#ticket_id").val(val.ticket_id);
    //                 $('#ticket_number').html(val.ticket_id);
    //                 $('#action_no').html(val.total_actions);
    //                 $('#ticketstatus').val(val.ticket_status);
    //                 // $('[name="tickettype"]').val(val.tickettype);
    //                 // console.log('call refresh');
    //                 refreshTicketType();
    //               });
    //             }
    //             else{
    //               customer_details(phone_number,'search');
    //               $('#phonenumber').val(phone_number);
    //               customer_opned_tickets_field(phone_number);
    //             }
    //             customer_opned_tickets(phone_number);
    //             // customer_opned_tickets_field(phone_number);
    //           }
    //         });  
    //       }
    //   }
    //   else{
    //     alert("Please enter mobile number or ticket number!");
    //     return false;
    //   }
    // });
  
    // $('#new_ticket').click(function() {
    //   moveLeft();
    //   moveLeft();
    //   $('#comments').attr('disabled',false);
    //   $('#clouserlabel').css('display','none');
    //   $('#clouserinput').css('display','none');
    //   $('#closed_clouser').css('display','none');
    //   $('#endticket').css('display','none');
    //   $('#endticket_time').html('');
    //   $("#ticket_id").val('');
    //   $('#tickettype_detail').html('');
    //   $('#comments').val('');
    //   $('#ticket_number').html('0');
    //   $('#action_no').html('1');
    //   $('#ticket_form .dynamicFields').remove();      
    //   $('#ticketstatus').prop('selectedIndex',0);
    //   $("#ticket_form").css("display", "flex");
    //   $("#ticket_form_header").css('display','inline-table');
    //   $("#save_ticket").val('Save');                
    //   $("#save_ticket").attr('type','submit');                       
    //   $('#ticket_form').closest('table').find(':input').attr('readonly',false);
    //   $('#ticket_form').closest('table').find('select').attr('disabled',false);
    //   $('#ticket_type').val('');
    //   $(".update_ticket").css('display','none');
    //   $('#save_ticket').css('display','block');
    //   $('#save_ticket').removeAttr('disabled');
    //   $('select[name="tickettype"]').find('option[value=""]').prop('selected', 'selected');
    //   $('#assign_by_disable').attr('readonly',true);
    //   $('#duration_span').css('display','none'); 
    //   $('#SecondSDISP').css('display','none');
    //   $('#ticketStart').css('display','none');
    //   $('#ticket_start').css('display','none');
    //   $('#endticket').css('display','none');
    //   $('#endticket_time').css('display','none');
    //   $('#current_ticketTime').css('display','flex');
    //   $('#display_ticketTime').css('display','flex');
    //   $('.ticketCall_time_view').css('display','flex');
    //   ticket_root = '';
    //   ticket_parent = '';
    //   ticket_child = '';
    // });

    // $('#dashboard').click(function() {
    //   var agent_status = document.getElementById("break-status").textContent;
    //   if(agent_status=='Oncall' || agent_status=='ACW'){
    //     // alert('You can not Search! You are still on call');
    //     return false;
    //   }else{
    //   var customerFormStatus = $('#customer_form').css('display');
    //   if(customerFormStatus == 'none'){
    //     $("#dash1").toggle();
    //     $('#history_span').toggle();
    //   } else{
    //     return false;
    //   }  
    //   }
    //   });
    // $('.agentTicketCount').click(function() {
    //     var agent = $('#dashboard').attr('agent');
    //     $.ajax({
    //       type:'POST',
    //       dataType:'JSON',
    //       data:{'agent_id':agent,'action':'getAgentLoginHour'},
    //       url:'ajaxRequest.php',
    //       success:function(res){
    //         // alert(res.data);
    //         // console.log(res);
    //         if(res.success){
    //           var html = '<b>Net Login Hour</b> : '+res.logintime+' <b>|| Total Break Duration</b>: '+res.breaktime+' <b>|| Total Shift Hour</b>: '+res.time;
    //           $('#loginHour').html(html);
    //         }
    //         // console.log(res.time);
    //         // $('#loginHour').html(res.open);
    //       }
    //     });
    // });  
    // $('.agentTicketCount').click(function() {

    //   var agent = $('#dashboard').attr('agent');
    //   var fromDate = $('#fromDate').val();
    //   var toDate = $('#toDate').val();
    //   $.ajax({
    //       type:'POST',
    //       dataType:'JSON',
    //       data:{'agent_id':agent,'fromDate':fromDate,'toDate':toDate,'action':'getAgentTicketDetails'},
    //       url:'ajaxRequest.php',
    //       success:function(res){
    //         // console.log(res);
    //         $('#opentickets').html(res.open);
    //         $('#closedtickets').html(res.closed);
    //         $('#escalatedtickets').html(res.escalated);
    //         $('#otherstickets').html(res.others);        
    //       }
    //     });
    // });
    $('#ticketstatus').change(function(){
      $('#closed_clouser').css('display','none');
      // alert(this.value);
      if(this.value=='2'){
        $('#clouserlabel').css('display','flex');
        $('#clouserinput').css('display','block');
        $('#clouseroptions').attr('required','required');
      }
      else{
        $('#clouserlabel').css('display','none');
        $('#clouserinput').css('display','none');
        $('#clouseroptions').removeAttr('required');
      }
    });
    var cat='';
    var parent= '';
    var child='';



    // On change Root node
  $('#ticket_form select[name="tickettype"]').on('change',function(){      
    $('#ticket_form .dynamicFields').remove();
    $('#tickettypeparent').html('');
    $('#tickettypechild').html('');
    $('#tickettype_detail').html('');
      var parent_id = $(this).val();
      cat= 'Tree : '+ $(this).find(":selected").text();
      // text = cat+parent+child;
      $('#tickettype_detail').html(cat);
      ticket_root = '';
      ticket_parent = '';
      ticket_child = '';
      
      $.ajax({        
        type:'POST',
        dataType:'JSON',
        data:{'parent_id':parent_id},
        url:'ticket_type.php',
        success:function(res){
          if(res.success==1){
            if(res.data.length>0){
              var html = '<select name="parent" id="parent" stateset="" class="form-control" required><option value="">Select Ticket Parent </option>';
              var field = res.data;
              function dynamicSort(property) {
                  var sortOrder = 1;

                  if(property[0] === "-") {
                      sortOrder = -1;
                      property = property.substr(1);
                  }

                  return function (a,b) {
                      if(sortOrder == -1){
                          return b[property].localeCompare(a[property]);
                      }else{
                          return a[property].localeCompare(b[property]);
                      }        
                  }
              }

              field.sort(dynamicSort("ticket_name"));
              // console.log(field);
              jQuery.each( res.data, function( i, val ) {               
                html += '<option value='+val.id+'>'+val.ticket_name+'</option>';
              });  
              html +='</select>';
              $('#tickettypeparent').html(html);
              moveRight();
              $('#parent').attr('stateset','yes');
              $(".control_next").css("display", "none");
              $(".newClass").css("display", "flex");
            }
                       
            
            // console.log(html);
            //-------------------------------------show parent mapping
            var clouseroptions = '<option value="">Select Clouser type </option>';
            jQuery.each( res.clouser_types, function( i, val ) {
              clouseroptions += '<option value='+val.id+'>'+val.name+'</option>';
            });
            $('#clouseroptions').html(clouseroptions);
            var ticketField='';
            appendTicketTypeFields(res);
          }
        }
      });
  });

// slider functionallity

  var slideCount = $('#slider ul li').length;
  // var slideWidth = $('#slider ul li').width();
  var slideHeight = $('#slider ul li').height();
  // var sliderUlWidth = slideCount * slideWidth;
  // var position = 0;
  function moveLeft() {
    console.log('left '+position);
    if(position>0){
        position = position-1;
        // if(position == 0){
        //   $("#ticket_type").addClass("active");
        //   $("#parent").removeClass("active");
        //   $("#child").removeClass("active");
        // } else if(position == 1){
        //   $("#ticket_type").removeClass("active");
        //   $("#parent").addClass("active");
        //   $("#child").removeClass("active");
        // } else if(position == 2){
        //   $("#ticket_type").removeClass("active");
        //   $("#parent").removeClass("active");
        //   $("#child").addClass("active");
        // }
        $('#slider ul').animate({
            // left: + slideWidth
        }, 200, function () {
            $('#slider ul li:last-child').prependTo('#slider ul');
            $('#slider ul').css('left', '');
        });
      }
    };
    $('#refresh_li').on('click',function(){
      $('#tickets_status #tickettypechild').insertBefore("#tickets_status li:nth-child(1)");  
      $('#tickets_status #ticketroot').insertAfter("#tickets_status li:nth-child(1)");  
      // $('#tickets_status #tickettypeparent').insertAfter("#tickets_status li:last-child()");  
      $('#tickets_status #tickettypeparent').last();  
      $('#tickets_status select').removeClass('active');
      $('#tickets_status #ticket_type').addClass('active');
      position=0;
    })

    function moveRight() {
      console.log('Right '+position);
      if(position<2){
        // activeval = $('.active').val();
        // alert(activeval);
        // if(activeval!=''){
      position = position+1;
      // if(position == 0){
      //     $("#ticket_type").addClass("active");
      //     $("#parent").removeClass("active");
      //     $("#child").removeClass("active");
      //   }
      //   else if(position == 1){
      //     $("#ticket_type").removeClass("active");
      //     $("#parent").addClass("active");
      //     $("#child").removeClass("active");
      //   } else if(position == 2){
      //     $("#ticket_type").removeClass("active");
      //     $("#parent").removeClass("active");
      //     $("#child").addClass("active");
      //   }
      // alert(position);
        $('#slider ul').animate({
            // left: - slideWidth
        }, 200, function () {
            $('#slider ul li:first-child').appendTo('#slider ul');
            $('#slider ul').css('left', '');
        });
      // }
    }
    };

    $('.control_prev').click(function () {
        $('#parent').attr('stateset','yes');
        var a = $('#ticket_type').val();
      // var b = $('#parent').attr('stateset');
      if(a){
        moveLeft();
      }
        $(".control_next").css("display", "flex");
        $(".newClass").css("display", "none");
    });

    $('.control_next').click(function () {
      // var a = $('.ticket_type').val();
      var a = $('#ticket_type').val();
      var b = $('#parent').attr('stateset'); 
         
      if(a){
        if(b){
             moveRight();
        }
        $(".control_next").css("display", "none");
        $(".newClass").css("display", "flex");
     
      }
    });

     $('.newClass').on('click',function(){
      var a = $('#ticket_type').val();
      var b = $('#parent').val();
      var c = $('#parent').attr('stateset'); 
      // alert(b);
      if(a){
        if(b){
           if(c!='no'){
            moveRight();
          }         
        }
      }
    })

  // On change Parent node
  $(document).on('change','#ticket_form select[name="parent"]',function(){
      $('#ticket_form .dynamicFields').remove();
      $('#tickettypechild').html('');
      var child_id = $(this).val();
      parent= '->'+ $(this).find(":selected").text();
      text = cat+parent;
      $('#tickettype_detail').html(text);      
      $.ajax({        
        type:'POST',
        dataType:'JSON',
        data:{'child_id':child_id},
        url:'ticket_type.php',
        success:function(res){
          if(res.success==1){
            if(res.data.length>0){
              var html = '<select name="child" id="child" class="form-control" required><option value="">Select Childs </option>';
              var field = res.data;
              function dynamicSort(property) {
                  var sortOrder = 1;

                  if(property[0] === "-") {
                      sortOrder = -1;
                      property = property.substr(1);
                  }

                  return function (a,b) {
                      if(sortOrder == -1){
                          return b[property].localeCompare(a[property]);
                      }else{
                          return a[property].localeCompare(b[property]);
                      }        
                  }
              }

              field.sort(dynamicSort("ticket_name"));
              jQuery.each( res.data, function( i, val ) {               
                html += '<option value='+val.id+'>'+val.ticket_name+'</option>';
              });  
              html +='</select>';
              $('#tickettypechild').html(html);
              moveRight();
            }
            else{
              $('#parent').attr('stateset','no');
            }            
            
            //-------------------------------------show parent mapping
            if(res.clouser_types!=null){
              var clouseroptions = '<option value="">Select Clouser type </option>';
              jQuery.each( res.clouser_types, function( i, val ) {
                clouseroptions += '<option value='+val.id+'>'+val.name+'</option>';
              });
              $('#clouseroptions').html(clouseroptions);
            }            
            appendTicketTypeFields(res);                          
          }
        }
      });
  });
  

  // On change Child node
  $(document).on('change','#ticket_form select[name="child"]',function(){
    child= '->'+$(this).find(":selected").text();    
    text = cat+parent+child;
    $('#tickettype_detail').html(text);

    var selected_child_id = $(this).val();
    // alert(selected_child_id);
    // var html = '<table>';
    var ticketField = '';
    $.ajax({        
      type:'POST',
      dataType:'JSON',
      data:{'selected_child_id':selected_child_id},
      url:'ticket_type.php',
      success:function(res){
        // console.log(res);
       if(res.success==1){
        var clouseroptions = '<option value="">Select Clouser type </option>';
          jQuery.each( res.clouser_types, function( i, val ) {
            clouseroptions += '<option value='+val.id+'>'+val.name+'</option>';
          });
          $('#clouseroptions').html(clouseroptions);
          appendTicketTypeFields(res);
          $('#parent').attr('stateset','no');
          // ticketField +='</table>';
        }
      }
    });
  });

  //Dynamic fields of ticket types 
  function appendTicketTypeFields(res){
    var ticketField='';
    $('#ticket_form .dynamicFields').remove();
    jQuery.each(res.ticketFields, function( i, val ) { 
        var required ="";
        if(val.is_required==1){
          required = 'required';
        }        
        if(val.field_type=='text') {
          if(val.field_controlType=='numeric'){
            ticketField += '<tr class="dynamicFields"><td><font>'+val.label+': </font></td><td><input type='+val.field_type+' name="'+val.name+'" maxlength="10" class="form-control number" '+required+'></td></tr>';
          } else if(val.field_controlType=='email'){
            ticketField += '<tr class="dynamicFields"><td><font>'+val.label+': </font></td><td><input type='+val.field_controlType+' name="'+val.name+'" '+required+' class="form-control"></td></tr>';
          } else{
          ticketField += '<tr class="dynamicFields"><td><font>'+val.label+': </font></td><td><input type='+val.field_type+' name="'+val.name+'" minlength="'+val.minLength+'" '+required+' class="form-control" ></td></tr>';
          }
        } else if(val.field_type=='date'){
          ticketField += '<tr class="dynamicFields"><td><font>'+val.label+': </font></td><td><input type='+val.field_type+' name="'+val.name+'" id="date" '+required+' class="form-control"></td></tr>';
        }
        else if(val.field_type=='select'){
          ticketField += '<tr class="dynamicFields"><td><font>'+val.label+': </font></td><td><select name ="'+val.name+'" class="form-control" '+required+' ><option value="" >Select '+val.label+'</option>';
          // console.log(val.fieldvalues);
          var fields = JSON.parse(val.fieldvalues);
          // console.log(fields);

          fields1 = fields.sort(compare); 
          function compare(a, b) {
            if (a.key< b.key)
                return -1;
            if (a.key > b.key)
                return 1;
            return 0;
        }
        console.log(fields1);
          jQuery.each(fields1, function(j, k){
            if(k.is_visible==1){                  
             ticketField += '<option value="'+k.value+'" >'+k.key+'</option>';
            }
          });
          ticketField += '</select></td></tr>';
        }
        else if(val.field_type=='checkbox'){
          ticketField += '<tr class="dynamicFields"><td><font>'+val.label+': </font></td><td>';
          // console.log(val.fieldvalues);
          var fields = JSON.parse(val.fieldvalues);          
          jQuery.each(fields, function(j, k){              
            if(k.is_visible==1){                  
              ticketField += '<input type="'+val.field_type+'" name="'+val.name+'[]" value="'+k.value+'" class="'+required+' is_required">'+k.key;
            }
          });           
          ticketField +='</td></tr>';
        }
        else if(val.field_type=='radio'){
          ticketField += '<tr class="dynamicFields"><td><font>'+val.label+': </font></td><td>';
          // console.log(val.fieldvalues);
          var fields = JSON.parse(val.fieldvalues);          
          jQuery.each(fields, function(j, k){              
            if(k.is_visible==1){                  
              ticketField += '<input type="'+val.field_type+'" name="'+val.name+'" value="'+k.value+'" '+required+'>'+k.key;
            }
          });           
          ticketField +='</td></tr>';
        }
        else if(val.field_type=='textarea'){
          ticketField += '<tr class="dynamicFields"><td><font>'+val.label+': </font></td><td><textarea name="'+val.name+'" '+required+'>';
          ticketField +='</textarea></td></tr>';
        }
        else if(val.field_type=='time'){
          ticketField += '<tr class="dynamicFields"><td><font>'+val.label+': </font></td><td><input id="time_picker" type="text" name="'+val.name+'" '+required+' class="form-control"></td></tr>';
        }                                   
      });      
      $('#ticket_form tr:last').before(ticketField);
      // $('body').on("onclick", "#time_picker", function (e) {
      //     $(this).wickedpicker();
      // });
          $('#time_picker').wickedpicker();
  }

  function validateEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
  }  

  $('#save_ticket').on('click',function(e){
    var fullDate = new Date();
      var day   = fullDate.getDate()+"";if(day.length==1) day="0" +day;
      var month = fullDate.getMonth()+parseInt(1) +"";if(month.length==1)  month="0" +month;
      var year  = fullDate.getFullYear();
      var hour = fullDate.getHours();
      var minute = fullDate.getMinutes();
      var second = fullDate.getSeconds();
      var date  = year+'-'+month+'-'+day;
      var time  = hour + ":" + minute + ":" + second;
     var creat_at = date+' '+time;

    localStorage.setItem('is_ticket_saved', 1);
    var holdTime = totalHoldMinute+":"+totalHoldSeconds;
    var holdCount = totalHoldCount;
    var ticket_id =0;
    if($("#ticket_id").length){
      ticket_id = $('#ticket_id').val();
    }    
    var items= []; 
    var is_validate=0;
      //field validation start
    $('#customer_form').closest('table').find(':input').each(function(){
      if($(this).attr('required')){
        var name = $(this).attr("name");
        if($(this).is('select') ) {
          var mySelect = $('select[name="' + name + '"]').val();
          if(mySelect==''){
            is_validate=1;
            if(name.slice(0,3)=='ty_'){
              alert_box('Please select '+name.slice(3));  
            } else{
              alert_box('Please select '+name);
            }
            return false;
          }
        } 

        if($(this).is('input') ) {
          var mySelect = $('input[name="' + name + '"]').val();
          if(mySelect==''){
            is_validate=1;
            if(name.slice(0,3)=='ty_'){
              alert_box('Please enter value in '+name.slice(3));
            } else{
              alert_box('Please enter value in '+name);
            }
            return false;
          }
        }    
      }      
    }) 
    if(is_validate==1){
      return false;
    } 

          
    $('#ticket_form').closest('table').find(':input').each(function(){  
      if($(this).attr('required')){
        var name = $(this).attr("name");
        if(name=='tickettype' || name=='parent' || name=='child'){
          // alert(ticket_root);
          // alert(ticket_parent);
          // alert(ticket_child);
          if(ticket_root!=''){
            $('select[name="tickettype"]').find('option[value="'+ticket_root+'"]').attr("selected",true);
            $('select[name="parent"]').find('option[value="'+ticket_parent+'"]').attr("selected",true);
            $('select[name="child"]').find('option[value="'+ticket_child+'"]').attr("selected",true);
          }
          else{
            if($(this).is('select') ) {
                var mySelect = $('select[name="' + name + '"]').val();
                if(mySelect==''){
                  is_validate=1;
                  if(name.slice(0,3)=='ty_'){
                    alert_box('Please select '+name.slice(3));
                  }
                  else{
                    alert_box('Please select '+name);
                  }
                  return false;
                }
              } 
          }
        }
        else{
          if($(this).is('select') ) {
              var mySelect = $('select[name="' + name + '"]').val();
              if(mySelect==''){
                is_validate=1;
                if(name.slice(0,3)=='ty_'){
                  alert_box('Please select '+name.slice(3));
                }
                else{
                  alert_box('Please select '+name);
                }
                return false;
              }
            } 
        }

        if($(this).is('input') ) {
          var minlength = $(this).attr("minlength");
          var mySelect = $('input[name="' + name + '"]').val();
          var length = mySelect.length;
          var requiredLength = $('input[minlength="' + minlength + '"]').val();
          if(mySelect==''){
            is_validate=1;
            if(name.slice(0,3)=='ty_'){
              alert_box('Please enter value in '+name.slice(3));
            }else{
              alert_box('Please enter value in '+name);
            }
            return false;
          }
          if(length<minlength){
            alert_box(name+' Should have minimum length '+requiredLength);
            return false;
          }

          var type = $('input[name="' + name + '"]').attr('type');
          if(type=='radio'){
            var checked = $("input[name='"+name+"']:checked").val();
            if(checked == undefined){
              is_validate=1;
              if(name.slice(0,3)=='ty_'){
                alert_box('Please select '+name.slice(3));
              }else{
                alert_box('Please select '+name);
              }
              return false;
            }           
          }

          if(type=='email'){
            if( !validateEmail(mySelect)){
              is_validate=1;
              alert_box('Please enter correct email address');
              return false;
            }
          }
        }

        if($(this).is('textarea') ) {
          var mySelect = $('textarea[name="' + name + '"]').val();
          if(mySelect==''){
            is_validate=1;
            if(name.slice(0,3)=='ty_'){
              alert_box('Please enter value in '+name.slice(3));
            } else{
              alert_box('Please enter value in '+name);
            }
            
            return false;
          }
        }   

      }   
     

      else if($(this).hasClass('required')){
        var name = $(this).attr("name");
        console.log("input[name='"+name+"']:checked");
        var list = $("input[name='"+name+"']:checked").map(function () {
          return this.value;
        }).get();
        if(list.length==0){
          is_validate=1;
          if(name.slice(0,3)=='ty_'){
            alert_box('Please checked atlest one checkbox in '+name.slice(3));
          } else{
            alert_box('Please checked atlest one checkbox in '+name);
          }
          
          return false;
        }
        // console.log(list);
      }
    })  
  
    if(is_validate==1){
      return false;
    }    
    //field validation end
   var status = $('#ticketstatus').val();
    
    var ticket_data   = $('#ticket_form').closest('table').find(':input').serialize();
    var customer_data = $('#customer_form').closest('table').find(':input').serialize();
    // var ticket_tree   = $('#tickettype_detail').html();
    // ticket_tree       = $.trim(ticket_tree);
    var ticket_tree_root   = $('#ticket_type').val();
    var ticket_tree_parent   = $('#parent').val();
    var ticket_tree_child   = $('#child').val();
    var ticket_tree       = $("#ticket_type option[value='"+ticket_tree_root+"']").text()+"->"+$("#parent option[value='"+ticket_tree_parent+"']").text()+"->"+$("#child option[value='"+ticket_tree_child+"']").text();
    var form_data     = 'action=saveAgentRequest'+'&ticket_id='+ticket_id+'&form1=ticket_form&'+ticket_data+'&form2=customer_form&'+customer_data+'&ticket_tree='+ticket_tree+'&creat_at='+creat_at+'&holdTime='+holdTime+'&holdCount='+holdCount;     
    $.ajax({
        type:'POST',
        dataType:'JSON',
        // data:{'customer_form':customer_data,'ticket_form':ticket_data,'action':'saveAgentRequest'},
        data:form_data,
        url:'ajaxRequest.php',
        success:function(res){
          if(res.success==1){              
            dispose_button = res.message;  
            $('#save_ticket').attr('disabled',true);
            $('#view_history').attr('disabled',true);
            $('#disposeButton').css('display','block');
            $('#ticketmessage').html(dispose_button);
            if(status!=2){
              var lastchild = $('#child').val(); 
              total_tickets_child.push({
                'lastchild' : lastchild, 
                'ticket_id' : res.ticket_id
              });
            }
            console.log(total_tickets_child);
          }else{
            alert(res.message);
            return false;
          }
        }
    });      
  });

    var start_Date = new Date('yyyy-mm-dd 23:59'); 
    var last_Date = new Date(); 

    // $(".form_datetime").datetimepicker({format: 'yyyy-mm-dd hh:ii'});
     $('#fromDate').datetimepicker({ 
      footer: true,
      modal: true, 
      minDate:new Date(), 
      maxtDate:start_Date, 
      format: 'yyyy-mm-dd HH:MM'
    });
    $('#toDate').datetimepicker({
      modal: true ,
      footer: true,
      minDate:new Date(),
      maxtDate:start_Date ,
      format: 'yyyy-mm-dd HH:MM'
    });
     $('#date').datetimepicker({ footer: true, modal: true ,format: 'yyyy-mm-dd'});

     $(document).on('keypress','.number',function(e){
      // $(".number").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
       if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          //display error message
          // $("#errmsg").html("Digits Only").show().fadeOut("slow");
                 return false;
      }
     });

      $('#searchTicket').click(function(){
        // alert('hello');
        $('#dash1').css('display','none');
      });

      // var input = document.getElementById("searchby");
      //   input.addEventListener("keyup", function(event) {
      //     if (event.keyCode === 13) {
      //      event.preventDefault();
      //      document.getElementById("searchTicket").click();
      //     }
      //   });

        // $("#searchby").keypress(function(event) {
        //       if (event.which == 13) {
        //           alert("You pressed enter");
        //        }
        //   });

  $('#refresh_search').click(function(){
    var agent_status = document.getElementById("break-status").textContent;
    if(agent_status=='ACW'){
      dispoaseCall();
    }
  });

  $('#dashboard').click(function() {
     $("#loginHourModel").show();
        getLoginHour();
  });

  // $('#ParkCustomerDial').click(function() {
  //    console.log("consult start======================");
  //    is_consult = 1;
  // });

  $('#HangupXferLine').click(function() {
     console.log("consult end======================");
     is_consult = 0;
  });

  $("#webphoneSpan").css('display','none');
  $("#webphoneLink").css('display','none');

});

  function check_for_consult()
  {
    var agent = $('#dashboard').attr('agent');
    $.ajax({
        type:'POST',
        dataType:'JSON',
        data:{agent:agent,action:'get_consult_status'},
        url:'ajaxRequest.php',
        success:function(res){
          if(res.success==1){              
            if(res.result==0 || res.result==1){
              is_consult = res.result;
            }
          }else{
            return false;
          }
        }
    });
  }



  function setFollowUpTime(followupMinutes=1,ticket_id){
    // var followupMinutes = 5;
    var dt = new Date();
    dt.setSeconds(dt.getSeconds()+parseInt(followupMinutes));
	var callbackcount = total_tickets_child.length;
	console.log('|--------------------------> callbackcount=' +callbackcount);
    console.log(dt+ '========' +followupMinutes);
    var hours   = dt.getHours();
    var minutes = dt.getMinutes();
    var seconds = dt.getSeconds();
    var year    = dt.getFullYear();
    var date    = dt.getDate();
    var month   = dt.getMonth()+1;  
    // var timeZone = hours+":"+minutes+":00";
    var todaysDate = year+"-"+month+"-"+date;
    $("#CBT_hour option:first").text(hours);
    document.vicidial_form.CBT_hour.value = hours;
    $("#CBT_minute option:first").text(minutes);
    document.vicidial_form.CBT_minute.value = minutes;
    $('#CBT_seconds').val(seconds);
    $('#CallBackCommenTsField').val(ticket_id);
    $('#cbcomment_comments').val("Call Back Comment");
    $('#CallBackDatESelectioN').val(todaysDate);
    $('#DispoSelection').val('CALLBK');
    CB_date_pick(todaysDate);  
    DispoSelectContent_create('CALLBK','ADD','YES');
    CallBackDatE_submit();
    $('#CallBackSelectBox').css('display','none');
    $('#DispoSelectBox').css('display','none');
  }

  function dispoaseCall(){
    var uniqueid = document.vicidial_form.uniqueid.value;
    //document.getElementById("XferControl").innerHTML = "<img src=\"./images/<//?php echo _QXZ("vdc_LB_makeCall-new.gif"); ?>\" width=\"110px\" height=\"25px\" border=\"0\" alt=\"Transfer asa dsa dsa d - Conference\" />";
    $('#HangupBothLines').css('display','none');
    $('#HangupXferLine').css('display','none');
    var callBack = $('#callBack').val();
    var agent_status = document.getElementById("break-status").textContent;
    var led = document.vicidial_form.lead_id.value;
    var phoneNo = $('#phonenumber').val();
     update_ring_time(uniqueid,ring_time);
    // console.log("mobileno========================"+phoneNo);
     document.getElementById("CusTInfOSpaN").innerHTML = "";
     $('#open_iframe').html("");
 //    if(callBack!='callBack'){
 //      // var status = $('#ticketstatus').val();
 //      // if(status!=2){
	// 	lists_id    = "998";
	// 	var callbackcount = total_tickets_child.length;
	// 	console.log('|-------------------------->dispoaseCall, callbackcount=' +callbackcount);
 //        for (var i = 0; i < total_tickets_child.length; i++) { 
 //            // console.log(total_tickets_child[i]); 
 //            var child = total_tickets_child[i]['lastchild'];
 //            var ticket_id = total_tickets_child[i]['ticket_id'];
 //            var dt = new Date();
			
 //            $.ajax({
 //              type:'POST',
 //              dataType:'JSON',
 //              data:{'child_id':child,'ticket_id':ticket_id,'phoneNo':phoneNo,'action':'getFollowupTime','CBHOLDcount':i},
 //              url:'ajaxRequest.php',
 //              success:function(res){

	// 			//alert(led);
	// 			//alert(res.lead_id);
			
 //                if(res.success==1){              
 //                  var followupMinutes = res.data[1];
 //                  if(followupMinutes!=0 && followupMinutes!=null){
 //                    console.log("setFollowUpTime===== "+followupMinutes);
 //                    document.vicidial_form.list_id.value = lists_id;
 //                    if(led==''){
 //                      document.vicidial_form.lead_id.value = res.lead_id;
 //                    }
	// 				           document.vicidial_form.lead_id.value = res.lead_id;
 //                    setFollowUpTime(followupMinutes,res.ticket_id);
 //                  }
 //                }
 //              }
 //            }); 
 //        } 
	// 	callbackcount = '';
	// 	console.log('|-------------------------->after total_tickets_child dispoaseCall, callbackcount=' +callbackcount);
	// // DJ Commented to stay value till dispo 
 //      total_tickets_child = [];
 //        if(agent_status=='Oncall' || agent_status=='ACW'){
 //          dialedcall_send_hangup('','','','','YES'); 
 //        }
 //      // }  
 //     }
     // else{
     if(agent_status=='Oncall' || agent_status=='ACW'){
        // alert('You can not Search! You are still on call');      
        dialedcall_send_hangup('','','','','YES'); 
        // open_dispo_screen=1;
        // start_all_refresh();
        DispoSelectContent_create('SALE','ADD','YES');      
        DispoSelect_submit('','','YES');
      }
    // }
      is_hangup=0;      
      is_timeout=0;
      is_makeCall=0;
      loggenInWithStatus = 'Idle';
      ticket_root = '';
      ticket_parent = '';
      ticket_child = '';
      previois_call_ticket = 0;
      is_consult = 0;
      // DispoSelectContent_create('','ReSET');
      // DispoSelectContent_create('N','ADD','YES');      
      // DispoSelect_submit('','','YES');
      AutoDial_ReSume_PauSe('VDADready');
      $('#skills').html('');
      // $("#ticket_form").css("display", "none");
      // $("#ticket_form_header").css("display", "none");
      // $('#ticket_form .dynamicFields').remove();
      $("#customer_form").css("display", "none");
      // $('#save_ticket').removeAttr('disabled');
      // $('#view_history').removeAttr('disabled');
      $("#vicidial_form")[0].reset();
      // $('#history_span').html('');
      // $('#history_span').css('display','none');
      // $('#ticketmessage').html('');
      // $('#disposeButton').css('display','none');  
      // $('#ticket_type').addClass('active');   
      // $('#clouserlabel').css('display','none');            
      // $('#clouserinput').css('display','none');            
      // $('#tickettype_detail').html('');
      // $('#tickettypeparent').html('');             
      // $('#tickettypechild').html(''); 
      // $('#MainStatuSSpan').html('');
      // $('#clouseroptions').removeAttr('required');
      //reset ticket type start
      $('#call_state').html('');
      // $('#MainPanel').css('display','none');
      $('#phone_numberDISP').html('');
      

      // $('#tickets_status #tickettypechild').insertBefore("#tickets_status li:nth-child(1)");  
      // $('#tickets_status #ticketroot').insertAfter("#tickets_status li:nth-child(1)");        
      // $('#tickets_status #tickettypeparent').last();  
      // $('#tickets_status select').removeClass('active');
      // $('#tickets_status #ticket_type').addClass('active');
      position=0; //re-initialize position
      

      $('#vicidial_form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
          e.preventDefault();
          return false;
        }
      });
      //reset ticket type end
      // $( "#activeDeactive" ).trigger( "click" );
      // setTimeout(AutoDial_ReSume_PauSe('VDADpause','','','','','','','YES'), 3000);      
      // begin_all_refresh();
      // document.vicidial_form.lead_id.value    ='';
      // document.vicidial_form.list_id.value    ='';
  }

  function refreshTicketType(){
    $('#tickets_status #tickettypechild').insertBefore("#tickets_status li:nth-child(1)");  
      $('#tickets_status #ticketroot').insertAfter("#tickets_status li:nth-child(1)");        
      $('#tickets_status #tickettypeparent').last();  
      $('#tickets_status select').removeClass('active');
      $('#tickets_status #ticket_type').addClass('active');
      position=0; //re-initialize position
  }

  function showDynamicFieldsList(dynamic_keys,ticket_data){ 
  // console.log(dynamic_keys);       
    $.ajax({
        type:'POST',
        dataType:'JSON',
        // data:{'customer_form':customer_data,'ticket_form':ticket_data,'action':'saveAgentRequest'},
        data:{action:'getDynamicFields',form_fields:dynamic_keys},
        url:'ajaxRequest.php',
        success:function(res){
          // console.log(res);
          if(res.success==1){              
            appendTicketTypeFields_new(res,ticket_data);
            // setFieldvalue(ticket_data);
          }else{
            alert(res.message);
            return false;
          }
        }
    });      
  }

   //Dynamic fields of ticket types 
  function appendTicketTypeFields_new(res,ticket_data){
    var ticketField='';
    var field_name ='ty_name';      
    $('#ticket_form .dynamicFields').remove();
    jQuery.each(res.ticketFields, function( i, val ) {
        var required ="";
        if(val.is_required==1){
          required = 'required';
        }        
         var field_name = val.name;         
        if(val.field_type=='text' || val.field_type=='email') {
          if(val.field_controlType=='numeric'){
            ticketField += '<tr class="dynamicFields"><td><font>'+val.label+': </font></td><td><input type='+val.field_type+' name="'+val.name+'" '+required+' value="'+ticket_data[field_name]+'" maxlength="10" minlength="'+val.minLength+'" class="form-control number" readonly ></td></tr>';
          } else if(val.field_controlType=='email'){
            ticketField += '<tr class="dynamicFields"><td><font>'+val.label+': </font></td><td><input type='+val.field_controlType+' name="'+val.name+'" '+required+' value="'+ticket_data[field_name]+'" minlength="'+val.minLength+'" readonly class="form-control"></td></tr>';
          } else{
            ticketField += '<tr class="dynamicFields"><td><font>'+val.label+': </font></td><td><input type='+val.field_type+' name="'+val.name+'" '+required+' value="'+ticket_data[field_name]+'" minlength="'+val.minLength+'" class="form-control" readonly></td></tr>';
          }
          // ticketField += '<tr class="dynamicFields"><td><font>'+val.label+': </font></td><td><input type='+val.field_type+' name="'+val.name+'" '+required+' value="'+ticket_data[field_name]+'" readonly ></td></tr>';
           
        } else if(val.field_type=='date'){
           ticketField += '<tr class="dynamicFields"><td><font>'+val.label+': </font></td><td><input type='+val.field_type+' name="'+val.name+'" id="date" '+required+' value="'+ticket_data[field_name]+'" readonly class="form-control" ></td></tr>';
        }

        else if(val.field_type=='select'){
          ticketField += '<tr class="dynamicFields"><td><font>'+val.label+': </font></td><td><select name ="'+val.name+'" class="form-control" '+required+' disabled ><option value="" >Select '+val.label+'</option>';
          // console.log(val.fieldvalues);
          var fields = JSON.parse(val.fieldvalues);
          jQuery.each(fields, function(j, k){
            if(k.is_visible==1){  
              // console.log(k.value+'=='+ticket_data[field_name]);
              if(k.value==ticket_data[field_name]){
                ticketField += '<option value="'+k.value+'" selected >'+k.key+'</option>';
              } else{                
                ticketField += '<option value="'+k.value+'"  >'+k.key+'</option>';
              }               
            }
          });
          ticketField += '</select></td></tr>';
        }
        else if(val.field_type=='checkbox'){
          ticketField += '<tr class="dynamicFields"><td><font>'+val.label+': </font></td><td>';
          // console.log(ticket_data[field_name]);
          var fields = JSON.parse(val.fieldvalues);          
          jQuery.each(fields, function(j, k){              
            if(k.is_visible==1){             
              if(jQuery.inArray(k.value,ticket_data[field_name]) !== -1){              
                ticketField += '<input type="'+val.field_type+'" name="'+val.name+'[]" value="'+k.value+'" class="'+required+' is_required" checked readonly>'+k.key;
              }else{
                ticketField += '<input type="'+val.field_type+'" name="'+val.name+'[]" value="'+k.value+'" class="'+required+' is_required" readonly>'+k.key;
              }
            }
          });           
          ticketField +='</td></tr>';
        }
        else if(val.field_type=='radio'){
          ticketField += '<tr class="dynamicFields"><td><font>'+val.label+': </font></td><td>';
          // console.log(val.fieldvalues);
          var fields = JSON.parse(val.fieldvalues);          
          jQuery.each(fields, function(j, k){              
            if(k.is_visible==1){      
              // console.log(k.value+'=='+ticket_data[field_name]);
              if(k.value==ticket_data[field_name]){
                ticketField += '<input type="'+val.field_type+'" name="'+val.name+'" value="'+k.value+'" '+required+' checked readonly>'+k.key;
              }else{
                ticketField += '<input type="'+val.field_type+'" name="'+val.name+'" value="'+k.value+'" '+required+' readonly>'+k.key;
              } 
            }
          });           
          ticketField +='</td></tr>';
        }
        else if(val.field_type=='textarea'){                           
          ticketField += '<tr class="dynamicFields"><td><font>'+val.label+': </font></td>';
          ticketField += '<td><textarea name="'+val.name+'" '+required+' readonly>'+ticket_data[field_name]+'</textarea></td></tr>';
        }
        else if(val.field_type=='time'){                           
          ticketField += '<tr class="dynamicFields"><td><font>'+val.label+': </font></td>';
          ticketField += '<td><input type="text" name="'+val.name+'" id="time_picker" '+required+' value="'+ticket_data[field_name]+'" class="form-control" readonly ></td></tr>';
        }                           
      });      
      $('#ticket_form tr:last').before(ticketField);
  }

  function appendParent(root_id,selected_parent_id){
      $('#ticket_form .dynamicFields').remove();
      $('#tickettypeparent').html('');
      $('#tickettypechild').html('');
      $('#tickettype_detail').html('');
        var parent_id = root_id;     
        // cat= 'Tree : '+ $('#ticket_type').find(":selected").text();
        // text = cat+parent+child;
        // $('#tickettype_detail').html(cat);
        var html = '<td width="100%"><select name="parent" id="parent" stateset="" class="form-control" disabled required><option value="">Select Ticket Parent </option>';
        $.ajax({        
          type:'POST',
          dataType:'JSON',
          data:{'parent_id':parent_id},
          url:'ticket_type.php',
          success:function(res){
            if(res.success==1){
              jQuery.each( res.data, function( i, val ) {  
                if(val.id==selected_parent_id){
                  html += '<option value='+val.id+' selected>'+val.ticket_name+'</option>';
                } else{                  
                  html += '<option value='+val.id+'>'+val.ticket_name+'</option>';
                }            
              });
              html +='</select></td>';
              $('#tickettypeparent').html(html);
              // console.log(html);
              //-------------------------------------show parent mapping
              var clouseroptions = '<option value="">Select Clouser type </option>';
              jQuery.each( res.clouser_types, function( i, val ) {
                clouseroptions += '<option value='+val.id+'>'+val.name+'</option>';
              });
              $('#clouseroptions').html(clouseroptions);
              var ticketField='';              
            }
          }
        });
    }


    function appendChild(parent_id,selected_child_id){      
      $('#ticket_form .dynamicFields').remove();
      var child_id = parent_id;
      // parent= '->'+ $('#parent').find(":selected").text();
      // text = cat+parent;
      // $('#tickettype_detail').html(text);      
      $.ajax({        
        type:'POST',
        dataType:'JSON',
        data:{'child_id':child_id},
        url:'ticket_type.php',
        success:function(res){
          if(res.success==1){
            if(res.data.length>0){
              var html = '<td width="100%"><select name="child" id="child" class="form-control" disabled required><option value="">Select Childs </option>';
              jQuery.each( res.data, function( i, val ) { 
              //  if(val.id==selected_child_id){              
              //   html += '<option value='+val.id+' selected>'+val.ticket_name+'</option>';
              // }else{
                html += '<option value='+val.id+'>'+val.ticket_name+'</option>';
              // }
              });  
              html +='</select></td>';
              $('#tickettypechild').html(html);
            }
            
            
            //-------------------------------------show parent mapping
            if(res.clouser_types==null){
              $.ajax({        
                type:'POST',
                dataType:'JSON',
                data:{'selected_child_id':selected_child_id},
                url:'ticket_type.php',
                success:function(res){
                  if(res.clouser_types!=null){
                    var clouseroptions = '<option value="">Select Clouser type </option>';
                    jQuery.each( res.clouser_types, function( i, val ) {
                      clouseroptions += '<option value='+val.id+'>'+val.name+'</option>';
                    });
                    $('#clouseroptions').html(clouseroptions);
                  }
                }    
              });        
          }
            // appendTicketTypeFields(res);                          
          }
        }
      });
  };



</script>
<!-- --------------------------statuswise time change--------------------------------- -->
<script type="text/javascript">
    let seconds = 0;
    let minutes = 0;
    let hours = 0;
    let displaySeconds = 0;
    let displayMinutes = 0;
    let displayHours = 0;
    let interval = null;
    let status = "stopped";

    function stopwatch(){
      seconds++;
      if(seconds / 60 === 1){
          seconds = 0; 
          minutes ++;
          if(minutes /60 ===1){
              minutes = 0;
              hours++;
          }
      }

      //adding 0 before one digit charachters
      if (seconds <10) {
          displaySeconds = "0" + seconds.toString();
      }
      else{
          displaySeconds = seconds;
      }

      if(minutes <10) {
          displayMinutes = "0" + minutes.toString();
      }
      else{
          displayMinutes = minutes;
      }

      if(hours <10){
          displayHours = "0" +hours.toString();
      }
      else{
          displayHours = hours;
      }
      //display
      var time =displayHours + ":" + displayMinutes + ":" + displaySeconds;
      $('#display').html(time);
      var agent_status = document.getElementById("break-status").textContent;      
      if(agent_status=='Oncall'){
        $('.ticketCall_time').val(time);
        $('.ticketCall_time_view').html(time);
        $('#SecondSDISP').css('display','none');
        // $('#ticketStart').css('display','none');
        // $('#ticket_start').css('display','none');
        // $('#endticket').css('display','none');
        // $('#endticket_time').css('display','none');
      }
      if(agent_status=='ACW'){
        $('#acw_time').val(time);
      }
  }

  function startStop() {
          // interval = window.setInterval(stopwatch, 1000);
  }


  //function for reset button
  function reset(){
    var status_interval = document.getElementById("display").innerHTML;
    var status_name = document.getElementById("break-status").innerHTML;
    var agent_id = $('#dashboard').attr('agent');
   
    if(typeof(campaign) != "undefined" && campaign !== null) {
      var logged_in_camp = campaign;
    }else{
      var logged_in_camp = '';
    }
    
    $.ajax({
      type:'POST',
      dataType:'JSON',
      data:{'agent_id':agent_id,'status_interval':status_interval,'status_name':status_name,'campaign_id':logged_in_camp,'action':'saveAgentStatusTime'},
      url:'ajaxRequest.php',
      success:function(res){
        if(res.success){
          // window.clearInterval(interval);
          // seconds = 0;
          // minutes = 0;
          // hours = 0;
          // document.getElementById("display").innerHTML = "00:00:00";
          status="stopped";
        }
      }
    });
  }

  function makeCall()
  {
      var agent_status = document.getElementById("break-status").textContent;
      if(agent_status=='Oncall' || agent_status=='ACW'){
        alert_box('Firstly! Please complete running call');
        return false;        
      }else if(is_onbreak==1){
        alert_box('You can not make call on break');
        return false;
      }
      is_timeout=0;
      previois_call_ticket = 0;
      document.getElementById("CusTInfOSpaN").innerHTML = "";
      var status = '';
      is_makeCall=1;
    $('#makeCall').css('display','block');
    var st = $('#ticketstatus').val();
   if(st=='2'){
    $('#clouserlabel').css('display','none');
    $('#clouserinput').css('display','none');
    $('#closed_clouser').css('display','none');    
   }
  }

   function getLoginHour(){
      var agent = $('#dashboard').attr('agent');
        $.ajax({
          type:'POST',
          dataType:'JSON',
          data:{'agent_id':agent,'action':'getAgentLoginHour'},
          url:'ajaxRequest.php',
          success:function(res){
            // alert(res.data);
            if(res.success){
              // console.log(res.stmt);
              // console.log(res.testdata);
              // var html = '<b>Net Login Hour</b> : '+res.logintime+' <b>|| Total Break Duration</b>: '+res.breaktime+' <b>|| Total Shift Hour</b>: '+res.time;
              // $('#loginHour').html(html);
              $('#net_login').html(res.time);
              $('#break_duration').html(res.breaktime);
              $('#shift_hour').html(res.logintime);
            }
            // console.log(res.time);
            // $('#loginHour').html(res.open);
          }
        });
    }

    function update_ring_time(uniqueid,ring_time){
    // alert(ring_time);
    $.ajax({
      type:'POST',
      dataType:'JSON',
      data:{'uniqueid':uniqueid,'ring_time':ring_time,'action':'save_ringTime'},
      url:'ajaxRequest.php',
      success:function(res){
        if(res.success){
            console.log(res);
            // console.log(uniqueid);
            // console.log(holdTime);      
        }
      }
    });
  }
</script>

