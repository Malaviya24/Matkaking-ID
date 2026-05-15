<?php
	include("include/connect.php");
	

	$date = date('Y-m-d');
	$new_date = date('Y-m-d', strtotime($date.' - 8 days'));

    $qry =  "DELETE FROM user_transaction where date < '$new_date'";
	$result = mysqli_query($con,$qry);
	echo mysqli_affected_rows($con)." Rows Affected.";
	
	mysqli_close($con);
	
	if(1){
	$log = 'Crow job run happened at '.date('d-m-y h:i:s A').'|';
	$fp = fopen('cron_job_log.txt', 'a');//opens file in append mode  
	fwrite($fp, json_encode($log));  
	fwrite($fp, "\n");  
	fclose($fp); 
	}
     
?>
