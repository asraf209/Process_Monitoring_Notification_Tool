<?php
	include('check_client_ip.php');
?>

<html>
<head>
	<title>How It Works</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">

</head>

<!--<body bgcolor="#CCFF99" background="images/bg22.jpg" style="background-repeat:inherit">-->
<body bgcolor="#CCFF99" background="images/lblue154.gif" style="background-repeat:inherit">


<div id="topbar">
  <div align="center">
    <!--<p>&nbsp;</p>-->
    <p><font color="#FFFFFF" style="vertical-align:bottom">:: HOW IT WORKS ::</font> </p>
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

<table border="0" cellspacing="10" width="900" style="table-layout:fixed">
	<tr>
		<td width="5%"></td>
		<td width="95%">>> <u>About:</u> </td>		
	</tr>
	<tr>
		<td width="5%"></td>	
		<td width="95%">
			<ul>
			<li>Process Monitoring Tool is an application for executing and monitoring remote processes.</li> 
			<li>It will run and will monitor any erroneous event reported by your process/script.</li> 
			<li>It has an automtic notification system which will let you know about the faulty event found after running your scripts.</li> 
			<li>It will also notify you whenever your process take longer time than specified.</li>
			</ul>
		</td>			
	</tr>
	<tr></tr>
	<tr></tr>
	<tr>
		<td width="5%"></td>
		<td width="95%">>> <u>Supported Process/Script Type:</u> </td>
		
	</tr>
	<tr>
		<td width="5%"></td>	
		<td width="95%">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - shell(.sh) <br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - python(.py) <br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - perl(.pl) <br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - java(.jar) <br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - php(.php) <br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - c (executable) <br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - cpp (executable)
		</td>			
	</tr>
	<tr></tr>
	<tr></tr>
	<tr>
		<td width="5%"></td>
		<td width="95%">>> <u>Working Steps:</u> </td>		
	</tr>
	<tr>
		<td width="5%"></td>	
		<td width="95%">
			<u>Step 1:</u> Put some messages in your script, in a location, where you think you should be notified about. These messages will then be directed to standard output. Be sure to add 'error'/'failed' token in your message. Here is an example: <br>
			<pre>
		try{ 
			//some code block..
		}catch(...){ 
			//some code block	
			printf("found <b style="color:#CC0000">error</b> in this function..");
		} 
			</pre>
		</td>			
	</tr>
	
	<tr>
		<td width="5%"></td>	
		<td width="95%">
			<u>Step 2:</u> Do <a href="http://guru.eecs.northwestern.edu/fb/pmonitor/registerprocess.php">register</a> your script. If you want your process to be run periodically then put some value(>0) in second in 'Timer' field. 'Timer' with 0 value will run the script only once. Default max run time is 30min. If you think your process could take longer than that then put your value there. You will get an notification email if your scripts run time exceeds that value.
			
		</td>			
	</tr>		
	
	<tr>
		<td width="5%"></td>	
		<td width="95%">
			<u>Step 3:</u> As soon as you register your process, it will be activated for operation. Check <a href="http://guru.eecs.northwestern.edu/fb/pmonitor/">here</a> few seconds later for running status. 
			
		</td>			
	</tr>
	<tr></tr>
	<tr></tr>
	<tr>
		<td width="5%"></td>	
		<td width="95%">
			>> In any time if you need to update any param of your script or if you want to disable the process, then go to <a href="http://guru.eecs.northwestern.edu/fb/pmonitor/processlist.php">Process List</a> page. Find your script and you will see edit/delete button there to update or delete the process. 
			
		</td>			
	</tr>
	<tr></tr>
	<tr>
		<td width="5%"></td>	
		<td width="95%">
			>> <u>For C/C++ process</u>, during <a href="http://guru.eecs.northwestern.edu/fb/pmonitor/registerprocess.php">registration</a>, put file name with .c/.cpp extension in the 'Script Location' field. Be sure to compile it in the same location as the source file and make executable with the same name and with no extension.
			
		</td>			
	</tr>
</table>


</div>

<div id="bottombar"><b>Contact: asraful.alom@northwestern.edu</b></div>

</body>
</html>
