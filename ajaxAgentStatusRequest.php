<?php
 $log_file_name = '/var/log/tekdial/AgentStatus/'.date('Y-m-d').'-AgentStatus.log';
 $response = json_encode($_POST['res']);
 writeErroIntoLogFile($response,$log_file_name);

 function writeErroIntoLogFile($msg,$log_file_name) {
  $date = date('d.m.Y G:i:s');
  $logText =  $date.' | '.$msg.PHP_EOL;
  error_log($logText, 3, $log_file_name);
}

?>