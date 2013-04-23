<?php
	include('check_client_ip.php');
?>

<html>
<head>
	<!-- Auto Refresh in 60s-->
	<!--<meta http-equiv="refresh" content="60; url=pmonitor.php">-->
	<title>Process Monitoring Tool</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">
        
</head>

<!--<body bgcolor="#CCFF99" background="images/bg22.jpg" style="background-repeat:inherit">-->
<body bgcolor="#CCFF99" background="images/lblue154.gif" style="background-repeat:inherit">

<div id="topbar">
  <div align="center">
    <!--<p>&nbsp;</p>-->
    <p><font color="#FFFFFF" style="vertical-align:bottom">:: PROCESS MONITORING TOOL ::</font> </p>
  </div>
</div>


<div id="tabbar"> 
<center>
	<table border="0" cellspacing="0" width="870" style="table-layout:fixed">
		<tbody align="center">
		  <tr height="8">
			<td width="25%"><a href="http://guru.eecs.northwestern.edu/fb/pmonitor/">Home</a></td>
			<td width="25%"><a href="http://guru.eecs.northwestern.edu/fb/pmonitor/registerprocess.php">Register Process</a></td>
			<td width="25%"><a href="http://guru.eecs.northwestern.edu/fb/pmonitor/processlist.php">Process List</a></td>
			<td width="25%"><a href="http://guru.eecs.northwestern.edu/fb/pmonitor/howitworks.php">How It Works</a></td>
		  </tr>
		</tbody>
	</table>
</center>
</div>


<div id="middlebar">

<center>

<!--<br>-->

<table>
  <tr>
    <td>
      <!--<table border="1" cellspacing="0" width="860" style="table-layout:fixed">-->
	  <!--<tbody align="center" style="background-color:#9966FF; font-size:12px; color:#004400">-->
      <table border="1" cellspacing="0" width="900" style="table-layout:fixed">
	  <tbody align="center" style="background-color:#7E587E; font-size:12px; color:#FFFFFF">
		  <tr height="30">
			<td width="5%"><b>ID</b></td>
			<td width="11%"><b>REQUEST</b></td>
			<td width="25%"><b>PROCESS/SCRIPT</b></td>
			<td width="15%"><b>ARGS</b></td>						
			<td width="13%"><b>HOST</b></td>			
			<td width="9%"><b>STATUS</b></td>			
			<td width="11%"><b>START TIME</b></td>
			<td width="11%"><b>END TIME</b></td>
		  </tr>
	  </tbody>
	
	  <tbody align="center" style="font-size:12px">
	  
		<?php				
				
			include('MySqlConn.php');
			
			$query = "SELECT a.id, a.request, b.script, b.args, b.host, a.status, a.start_time, a.end_time
					  FROM procmon.request_queue a, procmon.request_to_process b
					  WHERE TRIM(a.request) = TRIM(b.request_token)					  
					  ORDER BY a.id DESC";
			/*
			$query = "SELECT a.id, a.request, a.args, a.status, a.entry_time, b.host, b.script, a.start_time, a.end_time
					  FROM procmon.request_queue a, procmon.request_to_process b
					  WHERE TRIM(a.request) = TRIM(b.request_token)					  
					  ORDER BY a.id DESC";
			*/
			/*
			$query = "SELECT a.id, a.request, a.args, a.status, a.entry_time, b.host, b.script, a.start_time, a.end_time
					  FROM procmon.request_queue a, procmon.request_to_process b
					  WHERE TRIM(a.request) = TRIM(b.request_token)
					  AND (a.start_time >= DATE_SUB(now(), INTERVAL 700 DAY) OR a.start_time is NULL)
					  ORDER BY a.id DESC";
			*/
			
			$result = mysql_query($query);  
			
			$i=1;
			while($data = mysql_fetch_row($result))
			{
				//echo $data[1]."\n";
				/*echo("<tr>
						<td>$data[0]</td>
						<td><b>$data[1]</b></td>
						<td>$data[2]</td>
						<td><b>$data[3]</b></td>				
						<td>$data[4]</td>				
						<td>$data[5]</td>				
						<td>$data[6]</td>				
						<td style=\"word-wrap: break-word\">$data[7]</td>				
					</tr>"); */					
					
					#$str="";
					if($i%2==0)		$str="<tr style=\"background-color:#DDDDDD\">";					
					else	$str="<tr>";
										
					
					if($data[5]=="Error")	$status="<b style=\"color:#810541\">ERROR</b>";		//RED
					else if($data[5]=="Done")	$status="<b style=\"color:#254117\">DONE</b>";	//GREEN
					else if($data[5]=="Running")	$status="<b style=\"color:#666600\">$data[5]</b>";	
					else if($data[5]=="Failed")	$status="<b style=\"color:#EE0000\">FAILED</b>";	
					else if(strpos($data[5],"TH")>0)	$status="<b style=\"color:#CC0000\">$data[5]</b>";		//RED
					else $status="<b>$data[5]</b>";		//BLACK default
											
					echo($str."
						<td style=\"word-wrap: break-word\">$data[0]</td>
						<td style=\"word-wrap: break-word\"><b>$data[1]</b></td>
						<td style=\"word-wrap: break-word\">$data[2]</td>
						<td style=\"word-wrap: break-word\">$data[3]</td>
						<td style=\"word-wrap: break-word\">$data[4]</td>
						<td style=\"word-wrap: break-word\">$status</td>				
						<td style=\"word-wrap: break-word\">$data[6]</td>				
						<td style=\"word-wrap: break-word\">$data[7]</td>							
					</tr>"); 
					
					$i++;
			
			}
		
			mysql_close($conn);
			
		?>
	
	  </tbody>
    </table>
  </td>
</tr>
</table>

</center>

</div>

<div id="bottombar"><b>Contact: asraf.alom02@gmail.com</b></div>

</body>
</html>
