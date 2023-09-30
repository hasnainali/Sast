 <?php

 require_once("dbconnect_mysqli.php");
require_once("functions.php");
        
       

    ?> 

<div class="not-connected-call-summary" id="onNotConnectedCall" style="display:none;">
        <h1 class="tc-performance-tracker">TC Performance Tracker <?php echo $VD_campaign ?></h1>
        <input type="hidden" name="campaign" id="campaign" value="<?php echo $VD_campaign ?>">
        <section>
          
          <table>
            <tr ><th colspan="3" class="text-center" style="text-align: center; background: #ff7d60;">Brand Category Wise Sale</th></tr>
            <tr>
              <th style="width:33.3%;">Brand Category</th>
              <th style="width:33.3%;">Total Sale</th>
              <th style="width:33.3%;">Contribution%</th>
            </tr>
            
            <form><input type="hidden" id="hidden_grand_total" name="hidden_grand_total"></form>
            <tr>
              <td id='psl'>Super Star</td>
              <td id='psl_sale'>₹ 0</td>
              <td id='psl_sale_per'>0 %</td>
            </tr>
            <tr>
              <td id='national'>Star</td>
              <td id='national_sale'>₹ 0</td>
              <td id='national_sale_per'>0 %</td>
            </tr>
            <!-- <tr>
              <td id='regional'>C</td>
              <td id='regional_sale'>₹ 0</td>
              <td id='regional_sale_per'>0 %</td>
            </tr> -->
            <tr style="background: #a1dddd;font-weight: 550;">
              <td id='sub_total'>Sub Total</td>
              <td id='sub_total_amount'>₹ 0</td>
              <td id='sub_total_percentage'>0 %</td>
            </tr>
            <tr>
              <td id='pvt'>Others</td>
              <td id='pvt_sale'>₹ 0</td>
              <td id='pvt_sale_per'>0 %</td>
            </tr>
            <tr>
              <td>Grand Total</td>
              <td id='grand_total'> ₹ 0 </td>
              <td id='grand_total_perc'>0 %</td>
            </tr>
 
            
           </table>
      </section>

        <div class="average-conversion">
            <content style=" background: #6dd6ca;border: 2px solid #181414;">
              <span>
                <h1>Value Avg Sale</h1>
                <p id="average_sale">₹ 0</p>
              </span>
            </content>
            <content style=" background: #ff7070;border: 2px solid #181414;">
              <span>
                <h1>Focus Sku Sale | Sale %</h1>
                <p id="focus_sku_sale">₹ 0.00 | 0%</p>
              </span>
            </content>
            <content style=" background: #72a6ff;border: 2px solid #181414;">
              <span>
                <h1>Conversion</h1>
                <p id="conversation_percs">0%</p>
              </span>
            </content>
        </div>

       <section style="width:100%;">
          <!-- <h2>Call Summary</h2> -->
          
          <?php 
              //if($VD_login==8002){
                 ?>
                    
                    <table class="lead-table">
                      <tr ><th colspan="3" class="text-center" style="text-align: center; background: #ff7d60;">Call Summary</th></tr>
                      <!-- <tr>
                        <td>Total Leads</td>
                        <td id="total_leads">00</td>
                      </tr> -->
                      <tr>
                        <td>Total Connected Leads</td>
                        <td id="total_connected_leads">00</td>
                      </tr>
                      <tr>
                        <td>Total Order</td>
                        <td id="total_orders">00</td>
                      </tr>
                      <tr>
                        <td>Total Call Back</td>
                        <td id="total_callback">00</td>
                      </tr>
                      <tr>
                        <td>Total No Order</td>
                        <td id="total_no_orders">00</td>
                      </tr>
                      <!-- <tr>
                        <td>Total Not Connected</td>
                        <td id="total_not_conn">00</td>
                      </tr> -->
                    </table>  
                  
                 <?php

             // }  
           ?>
        </section>
    </div>


    <script >
       
      $(document).ready(function(){
        setInterval(function(){
           
           getTcData();


        },4 * 60 * 1000);

        var agents=<?php echo $VD_login ?>;
        /*setInterval(function(){
           
              $.ajax({
              type:'POST',
              dataType:'JSON',
              data:{'agent_id':agents},
              url:'tc_call_detail.php',
              success:function(res){
               // console.log(res); 
               // var   
                if(res.data.total_leads !=''){
                   $('#total_leads').html(res.data.total_leads);
                }
                if(res.data.total_orders !=''){
                   $('#total_orders').html(res.data.total_orders);
                }
                if(res.data.total_no_orders !=''){
                   $('#total_no_orders').html(res.data.total_no_orders);
                }
                if(res.data.total_callback !=''){
                   $('#total_callback').html(res.data.total_callback);
                }
                if(res.data.total_not_conn !=''){
                   $('#total_not_conn').html(res.data.total_not_conn);
                }
                

                var total_sale = $('#hidden_grand_total').val();
                var total_orders = res.data.total_orders;
                var total_no_orders = res.data.total_no_orders;
                var total_callback = res.data.total_callback;
                var average_sale  =0;
                var total_conversion  =0;

                $('#total_connected_leads').html(parseFloat(total_no_orders) + parseFloat(total_orders) + parseFloat(total_callback));

                if(total_sale !='' && total_orders>0){
                  // console.log("tttttt"+total_sale);
                    average_sale = parseFloat(total_sale)/parseFloat(total_orders);
                }
                var total_leads = res.data.total_leads;
                var total_connected_leads = $('#total_connected_leads').html();
                if(total_orders>0 && total_connected_leads>0){
                      total_conversion=(parseFloat(total_orders)/parseFloat(total_connected_leads))*100;
                      // console.log("ddfd");
                      // console.log(total_conversion);
                }
                // console.log("dfd");
                // console.log(total_conversion);
                $('#average_sale').html(average_sale.toFixed(2));
                $('#conversation_perc').html(total_conversion.toFixed(2)+" %");
                // console.log('average_sale'+average_sale);
                // console.log('total_sale'+total_sale);
                // console.log('total_orders'+total_orders);
                // console.log('total_conversion'+total_conversion);
                //console.log(average_sale);


              },
              error:function(res){
                // console.log(res);
              }
            });

        },9000);*/
      });


       $(document).ready(function(){
       	   getTcData();
      });

      function getTcData(){
 
          var grand_total=0;
           var grand_total_perc=0;
           var agent=<?php echo $VD_login ?>;
           var campaign=$('#campaign').val();
           // var campaign = 'DEWAS';;
           if(agent==8001){
              agent = 5003;
              campaign = 'DEWAS';
           }
           $.ajax({
              type:'POST',
              dataType:'JSON',
              data:{
                'agent_id':agent,
                'campaign_id':campaign

              },
              // url:'https://dms2.vyapaar-vistaar.in/api/getTcPerformerRural',
              url:'https://tcob.vyapaar-vistaar.in/api/getTcPerformerRuralV1',
              success:function(res){
                var total_sum = 0;
                var total_contri = 0;
                var sub_total_sum = 0;
                var sub_total_contri = 0;
                $.each(res.data['data'], function(key,val) {
                    
                    if(val.category_name == 'A'){
                        total_contri +=parseFloat(val.sale_contribution);
                        total_sum +=parseFloat(val.category_sale); 
                        sub_total_contri +=parseFloat(val.sale_contribution);
                        sub_total_sum +=parseFloat(val.category_sale); 
                        // $('#psl').html(val.category_name);
                        $('#psl_sale_per').html(val.sale_contribution+' %');
                        $('#psl_sale').html('₹ '+val.category_sale);
                    }
                    if(val.category_name == 'B'){
                        total_contri +=parseFloat(val.sale_contribution);
                        total_sum +=parseFloat(val.category_sale); 
                        sub_total_contri +=parseFloat(val.sale_contribution);
                        sub_total_sum +=parseFloat(val.category_sale);
                        // $('#national').html(val.category_name);
                        $('#national_sale_per').html(val.sale_contribution+' %');
                        $('#national_sale').html('₹ '+val.category_sale);
                    }
                    if(val.category_name == 'C'){
                        total_contri +=parseFloat(val.sale_contribution);
                        total_sum +=parseFloat(val.category_sale); 
                        sub_total_contri +=parseFloat(val.sale_contribution);
                        sub_total_sum +=parseFloat(val.category_sale);
                        // $('#regional').html(val.category_name);
                        // $('#regional_sale_per').html(val.sale_contribution+' %');
                        // $('#regional_sale').html('₹ '+val.category_sale);
                    }
                    if(val.category_name == 'Others'){
                        total_contri +=parseFloat(val.sale_contribution);
                        total_sum +=parseFloat(val.category_sale); 
                        $('#pvt').html(val.category_name);
                        $('#pvt_sale_per').html(val.sale_contribution+' %');
                        $('#pvt_sale').html('₹ '+val.category_sale);
                    }
                 });
                $('#sub_total_amount').html('₹ '+sub_total_sum.toFixed(2));
                $('#sub_total_percentage').html(sub_total_contri+' %');
               /*if(res.data['data'][0]['butype'] !=''){
                    $('#psl').html(res.data['data'][0]['butype']);
                    $('#psl_sale_per').html(res.data['data'][0]['total_sale_per']+'%');
                    $('#psl_sale').html('₹'+res.data['data'][0]['total_amount']);
               }
               if(res.data['data'][1]['butype'] !=''){
                    $('#national').html(res.data['data'][1]['butype']);
                    $('#national_sale_per').html(res.data['data'][1]['total_sale_per']+'%');
                    $('#national_sale').html('₹'+res.data['data'][1]['total_amount']);
               }
               if(res.data['data'][2]['butype'] !=''){
                    $('#regional').html(res.data['data'][2]['butype']);
                    $('#regional_sale_per').html(res.data['data'][2]['total_sale_per']+'%');
                    $('#regional_sale').html('₹'+res.data['data'][2]['total_amount']);
               }
               if(res.data['data'][3]['butype'] !=''){
                    $('#pvt').html(res.data['data'][3]['butype']);
                    $('#pvt_sale_per').html(res.data['data'][3]['total_sale_per']+'%');
                    $('#pvt_sale').html('₹'+res.data['data'][3]['total_amount']);
               }
               grand_total=res.data['data'][0]['total_amount']+res.data['data'][1]['total_amount']+res.data['data'][2]['total_amount']+res.data['data'][3]['total_amount'];
               if(res.data.total_sale[0]>0){
                  $('#grand_total').html('₹'+res.data.total_sale[0]);
                  // $('#hidden_grand_total').val(grand_total);
               }
               grand_total_perc=parseFloat(res.data['data'][0]['total_sale_per'])+parseFloat(res.data['data'][1]['total_sale_per'])+parseFloat(res.data['data'][2]['total_sale_per'])+parseFloat(res.data['data'][3]['total_sale_per']);
               
               if(grand_total_perc>0){
                  $('#grand_total_perc').html(grand_total_perc.toFixed(2)+'%');
               }*/
                $('#grand_total_perc').html(total_contri.toFixed(2)+' %');
                $('#grand_total').html('₹ '+total_sum.toFixed(2));
                
                if(res.data.retailer_conn !=''){
                   $('#total_connected_leads').html(res.data.retailer_conn);
                }
                if(res.data.total_order !=''){
                   $('#total_orders').html(res.data.total_order);
                }
                if(res.data.total_no_order !=''){
                   $('#total_no_orders').html(res.data.total_no_order);
                }
                if(res.data.total_clbk !=''){
                   $('#total_callback').html(res.data.total_clbk);
                }
                if(res.data.avg_order_val !=''){
                   $('#average_sale').html(res.data.avg_order_val);
                }

                if(res.data.conversion !=''){
                   $('#conversation_percs').html(res.data.conversion+" %");
                }

                if(res.data.focus_sku_sale !=''){
                   $('#focus_sku_sale').html(res.data.focus_sku_sale +" | "+res.data.focus_sku_sale_perc+" %");
                }
               // console.log(grand_total_perc);


              },
              error:function(res){
                // console.log(res);
              }
            });
      } 

    </script>