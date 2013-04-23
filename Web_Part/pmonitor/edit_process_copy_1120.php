
<html>
<head>
	<!-- Auto Refresh in 60s-->
	<!--<meta http-equiv="refresh" content="60; url=pmonitor.php">-->
	<title>Edit Process Entry</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">

</head>

<!--<body bgcolor="#CCFF99" background="images/bg22.jpg" style="background-repeat:inherit">-->
<body bgcolor="#CCFF99" background="images/lblue154.gif" style="background-repeat:inherit">


<div id="topbar">
  <div align="center">
    <!--<p>&nbsp;</p>-->
    <p><font color="#FFFFFF" style="vertical-align:bottom">:: UPDATE A PROCESS ENTRY ::</font> </p>
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
	$id=$_GET["id"];
	$req=$_GET["req"];
	$script=$_GET["script"];
	$host=$_GET["host"];
	$status=$_GET["status"];
	$timer=$_GET["timer"];
	$username=$_GET["username"];
	$password=$_GET["password"];
	$port=$_GET["port"];
	$th=$_GET["th"];
	$contacts=$_GET["contacts"];
	
	
	$row['Request']=$req;
	$row['Status']=$status;
	$row['Script']=$script;
	$row['Host']=$host;
	$row['Uname']=$username;
	$row['Passwd']=$password;
	$row['Port']=$port; 
	$row['Th']=$th;
	$row['Timer']=$timer;
	$row['Contact']=$contacts;
		
?>


<form action="" method="post">    
	<table border="0" cellspacing="10">
		<tr>
			<td>Request Name*:</td> <td><input type="text" name="updaterequest" value="<?php echo $row['Request']; ?>"></td>
		</tr>
		<tr>
			<td>Status:</td> <td><input type="text" name="updatestatus" size="10" value="<?php echo $row['Status']; ?>"></td>
			<td>(1=active; 0=disabled)</td>
		</tr>
		<tr>
			<td>Script Location*:</td> <td><input type="text" name="updatescript" size="80" value="<?php echo $row['Script']; ?>"></td>
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
			<td></td><td><INPUT TYPE="Submit" VALUE="UPDATE RECORD" NAME="Submit"></td>
		</tr>
	</table>
</form>


<?php			

	if(isset($_POST['Submit'])){//if the submit button is clicked
		
		include('MySqlConn.php');
		
		$request = trim($_POST['updaterequest']);
		$status = trim($_POST['updatestatus']);
		$script = trim($_POST['updatescript']);
		$host = trim($_POST['updatehost']);
		$uname = trim($_POST['updateuname']);
		$passwd = trim($_POST['updatepass']);
		$port = trim($_POST['updateport']);
		$th = trim($_POST['updateth']);
		$contact = trim($_POST['updatecontact']);
		$timer = trim($_POST['updatetimer']);
		$id=trim($id);
		
		if($request=='')	print "<script> alert('\'Request Name\' field is blank')</script>";	
		else if($status!='0' && $status!='1' && $status!='')	print "<script> alert('\'Status\' should be only 0 or 1')</script>";	
		else if($script=='')	print "<script> alert('\'Script Location\' field is blank')</script>";	
		else if($host=='')	print "<script> alert('\'Host Machine\' field is blank')</script>";	
		else if($uname=='')	print "<script> alert('\'Username\' field is blank')</script>";	
		else if($passwd=='')	print "<script> alert('\'Password\' field is blank')</script>";	
		else if($contact=='')	print "<script> alert('\'Email Contacts\' field is blank')</script>";	
		
		else{			
			if($status=='')	$status=1;
			else	$status=intval($status);
			
			if($port=='')	$port=22;
			else	$port=intval($port);
			
			if($th=='')	$th=30;
			else	$th=intval($th);
			
			if($timer=='')	$timer=0;
			else	$timer=intval($timer);
				
				
			$update = "UPDATE procmon.request_to_process 
				   SET
				   	request_token = '$request',
				   	status = $status,
				   	script = '$script',
				   	host = '$host',
				   	username = '$uname',
				   	password = '$passwd',
				   	port = $port, 
				   	th = $th,
				   	contacts = '$contact',
				   	timer = $timer
				   WHERE
				   	id = $id";
					   	
			$result=mysql_query($update) or die(mysql_error());
			
			if($result){
				//ob_start();
				print "<script> alert('Entry Updated Successfully')</script>";	
				mysql_close();			
				//header( 'Location: http://localhost/pmonitor/processlist.php') ;				
				//ob_flush();
				//exit;
			}
			else{
				print "<script> alert('Failed to Update..')</script>";	
				mysql_close();				
			}
			?>
		
			<meta http-equiv="Refresh" content="0; url=http://guru.eecs.northwestern.edu/fb/pmonitor/processlist.php">
		
		<?php		
		}
		
	}

	//mysql_close();

?>




</center>

</div>

<div id="bottombar"><b>Contact: asraful.alom@northwestern.edu</b></div>

</body>
</html>
