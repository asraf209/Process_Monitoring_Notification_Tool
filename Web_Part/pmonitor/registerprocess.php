<?php
	include('check_client_ip.php');
?>

<html>
<head>
	<!-- Auto Refresh in 60s-->
	<!--<meta http-equiv="refresh" content="60; url=pmonitor.php">-->
	<title>Register a Process</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">

</head>

<!--<body bgcolor="#CCFF99" background="images/bg22.jpg" style="background-repeat:inherit">-->
<body bgcolor="#CCFF99" background="images/lblue154.gif" style="background-repeat:inherit">


<div id="topbar">
  <div align="center">
    <!--<p>&nbsp;</p>-->
    <p><font color="#FFFFFF" style="vertical-align:bottom">:: REGISTER A PROCESS ::</font> </p>
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
<br>


<?php 
	$row['Port']=22; 
	$row['Th']=30;
	$row['Timer']=0;
?>


<form action="" method="post">    
	<table border="0" cellspacing="10">
		<tr>
			<td>Request Name*:</td> <td><input type="text" name="updaterequest" value="<?php echo $row['Request']; ?>"></td>
		</tr>
		<tr>
			<td>Script Location*:</td> <td><input type="text" name="updatescript" size="80" value="<?php echo $row['Script']; ?>"></td>
		</tr>
		<tr>
			<td>Arguments:</td> <td><input type="text" name="updateargs" size="80" value="<?php echo $row['Args']; ?>"></td>
			<td>(Ex: Number 'String Param' 'String param')</td>
		</tr>
		<tr>
			<td>Host Machine*:</td> <td><input type="text" name="updatehost" size="50" value="<?php echo $row['Host']; ?>"></td>
		</tr>
		<tr>
			<td>Username(SSH Login)*:</td> <td><input type="text" name="updateuname" size="30" value="<?php echo $row['Uname']; ?>"></td>
		</tr>
		<tr>
			<td>Password(SSH Login)*:</td> <td><input type="text" name="updatepass" size="30" value="<?php echo $row['Passwd']; ?>"></td>
		</tr>
		<tr>
			<td>Port(SSH Login):</td> <td><input type="text" name="updateport" value="<?php echo $row['Port']; ?>"></td>
			<td>(default: 22)</td>
		</tr>
		<tr>
			<td>Max Runtime(in min):</td> <td><input type="text" name="updateth" value="<?php echo $row['Th']; ?>"></td>
			<td>(default: 30min)</td>
		</tr>
		<tr>
			<td>Timer(in sec):</td> <td><input type="text" name="updatetimer" value="<?php echo $row['Timer']; ?>"></td>
			<td>(for periodic running process)</td>
		</tr>
		<tr>
			<td>Email Contacts*:</td> <td><input type="text" name="updatecontact" size="80" value="<?php echo $row['Contact']; ?>"></td>
			<td>(comma separated)</td>
		</tr>
		<tr></tr>
		<tr></tr>
		<tr></tr>
		<tr>
			<td></td><td><INPUT TYPE="Submit" VALUE="INSERT RECORD" NAME="Submit"></td>
		</tr>
	</table>
</form>


<?php
			
	if(isset($_POST['Submit'])){//if the submit button is clicked
		
		include('MySqlConn.php');
		
		$request = trim($_POST['updaterequest']);
		$script = trim($_POST['updatescript']);
		$args = trim($_POST['updateargs']);
		$host = trim($_POST['updatehost']);
		$uname = trim($_POST['updateuname']);
		$passwd = trim($_POST['updatepass']);
		$port = trim($_POST['updateport']);
		$th = trim($_POST['updateth']);
		$contact = trim($_POST['updatecontact']);
		$timer = trim($_POST['updatetimer']);

		
		if($request=='')	print "<script> alert('\'Request Name\' field is blank')</script>";	
		else if($script=='')	print "<script> alert('\'Script Location\' field is blank')</script>";	
		else if($host=='')	print "<script> alert('\'Host Machine\' field is blank')</script>";	
		else if($uname=='')	print "<script> alert('\'Username\' field is blank')</script>";	
		else if($passwd=='')	print "<script> alert('\'Password\' field is blank')</script>";	
		else if($contact=='')	print "<script> alert('\'Email Contacts\' field is blank')</script>";	
		
		else{

			$query = "SELECT script from procmon.request_to_process WHERE request_token='".$request."'";#'test'";#'".trim($request)."'";
			#print "<script> alert('$query')</script>";
			$result = mysql_query($query) or die(mysql_error());
			$row=mysql_fetch_assoc($result);

			if($row['script']!='')
				print "<script> alert('The \'Request Name\' you entered is already in the database. Try a different one.')</script>";	

			else{
				if($port=='')	$port=22;
				else	$port=intval($port);
				
				if($th=='')	$th=30;
				else	$th=intval($th);
				
				if($timer=='')	$timer=0;
				else	$timer=intval($timer);
				
				if($args!='')	$args = "\"".$args."\"";
				else $args = 'NULL';
				

				$insert = "INSERT INTO procmon.request_to_process(request_token, host, script, args, username, password, port, th, contacts, timer) 
							VALUES('$request', '$host', '$script', $args, '$uname', '$passwd', $port, $th, '$contact', $timer)";
				$result=mysql_query($insert) or die(mysql_error());

				if($result)
					print "<script> alert('Entry Added Successfully')</script>";	
				else
					print "<script> alert('Failed to Add Entry')</script>";	
			}

		}
		
	}

	mysql_close();

?>




</center>

</div>

<div id="bottombar"><b>Contact: asraful.alom@northwestern.edu</b></div>

</body>
</html>
