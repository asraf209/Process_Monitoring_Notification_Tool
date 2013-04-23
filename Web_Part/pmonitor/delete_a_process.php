	  
<?php
	$q=$_GET["q"];
	
	include('MySqlConn.php');

	$delete = "DELETE FROM procmon.request_to_process WHERE id = ".$q;
	$success=mysql_query($delete) or die(mysql_error());

	if($success)
		echo ">> * Process Deleted Successfully..";
	else
		echo ">> * Failed to Delete Process..";	
?>
