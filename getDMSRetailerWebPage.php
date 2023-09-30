<?php


// Grab Variables from the call
$phone=$_GET['phone'];
/*$myfile = fopen("data.txt", "a") or die("Unable to open file!");
$txt = "$phone\n";
fwrite($myfile, $txt);
fclose($myfile);*/

if(isset($_GET['agentID']) && !empty($_GET['agentID'])){
    ?>
    <!-- <script>
    	 console.log("yes");
      customer_details_page_not_open(<?php //echo $phone ?>);
    </script> -->
    <?php
}else{
	?>
     <!-- <script>
    	 
      customer_details_page_not_open('6263131796');
      logWebLinkResponse("customer_details_page_not_open");
    </script> -->
    <?php
}
$data = array();
$data = '6263131796';
// $data['agent_id'] = '6263131796';
 
echo $data;

?>
