
<html>
<head>
	<!-- Auto Refresh in 60s-->
	<!--<meta http-equiv="refresh" content="60; url=pmonitor.php">-->
	<title>List of Processes</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">


<script>
	function deleteEntry(id, name){	
		//alert("Hey buddy.. " + id);
		yes = confirm("Are you sure you want to delete Id:" + id + ", Request:'" + name + "'");
		
		if(yes){
			if (id=="")
			{
				//document.getElementById("statusbar").innerHTML="";
				return;
			} 
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
		
			xmlhttp.onreadystatechange=function()
	  		{
	  			if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    			{
	    				response = xmlhttp.responseText;
	    				//document.getElementById("statusbar").innerHTML=response;
	    				alert(response);
	    				
	    				if(response.search("Deleted")){
	    					location.reload();
	    				}
	    			}
	  		}
			xmlhttp.open("GET","delete_a_process.php?q=" + id, true);
			xmlhttp.send();						
			
		}
		
	}
	
	
	function editEntry(id, reqname, script, host, status, timer, username, password, port, th, contacts){	
		yes = confirm("Are you sure you want to edit Id:" + id + ", Request:'" + reqname + "'");		
		if(yes){			
			window.location.href="edit_process.php?id="+ id + "&req=" + reqname + "&script=" + script+ "&host=" + host + "&status=" + status + "&timer=" + timer +
						"&username=" + username + "&password=" + password + "&port=" + port + "&th=" + th + "&contacts=" + contacts;
		}
		
	}
	
</script>

</head>


<!--<body bgcolor="#CCFF99" background="images/bg22.jpg" style="background-repeat:inherit">-->
<body bgcolor="#CCFF99" background="images/lblue154.gif" style="background-repeat:inherit">


<div id="topbar">
  <div align="center">
    <!--<p>&nbsp;</p>-->
    <p><font color="#FFFFFF" style="vertical-align:bottom">:: LIST OF ALL PROCESSES ::</font> </p>
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

<table>
  
  <tr>
    <td>
    <form action="">    
      <!--<table border="1" cellspacing="0" width="860" style="table-layout:fixed">-->
	  <!--<tbody align="center" style="background-color:#9966FF; font-size:12px; color:#004400">-->
	<table border="1" cellspacing="0" width="870" style="table-layout:fixed">
	  <tbody align="center" style="background-color:#7E587E; font-size:12px; color:#FFFFFF">
		  <!--<tr height="30">
			<td width="5%"><b>ID</b></td>
			<td width="13%"><b>REQUEST</b></td>
			<td width="25%"><b>PROCESS</b></td>
			<td width="17%"><b>HOST</b></td>
			<td width="9%"><b>STATUS</b></td>
			<td width="12%"><b>ENTRY TIME</b></td>
			<td width="13%"><b>NEXT RUN TIME</b></td>
			<td width="7%"><b>TIMER</b></td>						
		  </tr>-->
		  <tr height="30">
			<td width="4%"><b>ID</b></td>
			<td width="11%"><b>REQUEST</b></td>
			<td width="23%"><b>PROCESS</b></td>
			<td width="12%"><b>HOST</b></td>
			<td width="9%"><b>STATUS</b></td>
			<td width="12%"><b>ENTRY TIME</b></td>
			<td width="13%"><b>NEXT RUN TIME</b></td>
			<td width="5%"><b>TIMER</b></td>
			<td width="12%"><b>ACTION</b></td>			
			
		  </tr>
	  </tbody>
	
	  <tbody align="center" style="font-size:12px">
	  	
		<?php
			
			include('MySqlConn.php');
					
			$query = "SELECT id, request_token, script, host, status, entry_time, next_run_time, timer, username, password, port, th, contacts
					  FROM procmon.request_to_process";					 
			
			$result = mysql_query($query);  
			
			$i=1;
			while($data = mysql_fetch_row($result))
			{					
					#$str="";
					if($i%2==0)		$str="<tr style=\"background-color:#DDDDDD\">";					
					else	$str="<tr>";
					
					/*
					echo($str."
						<td style=\"word-wrap: break-word\">$data[0]</td>
						<td style=\"word-wrap: break-word\"><b>$data[1]</b></td>
						<td style=\"word-wrap: break-word\">$data[2]</td>
						<td style=\"word-wrap: break-word\">$data[3]</td>				
						<td style=\"word-wrap: break-word\">$data[4]</td>				
						<td style=\"word-wrap: break-word\">$data[5]</td>				
						<td style=\"word-wrap: break-word\">$data[6]</td>					
						<td style=\"word-wrap: break-word\">$data[7]</td>
					</tr>");
					*/					
										
					if($data[4]==1)	$status="ACTIVE";
					else if($data[4]==0)	$status="DISABLED";
											
					echo($str."
						<td style=\"word-wrap: break-word\">$data[0]</td>
						<td style=\"word-wrap: break-word\"><b>$data[1]</b></td>
						<td style=\"word-wrap: break-word\">$data[2]</td>
						<td style=\"word-wrap: break-word\">$data[3]</td>
						<td style=\"word-wrap: break-word\">$status</td>				
						<td style=\"word-wrap: break-word\">$data[5]</td>				
						<td style=\"word-wrap: break-word\">$data[6]</td>				
						<td style=\"word-wrap: break-word\">$data[7]</td>				
						<td style=\"word-wrap: break-word\"> 
							<input type=\"button\" name={$data[0]} value=\"Edit\" onClick=\"editEntry($data[0], '$data[1]', '$data[2]', '$data[3]', $data[4],
							$data[7], '$data[8]', '$data[9]', $data[10], $data[11], '$data[12]')\"> 
							
							<input type=\"button\" name={$data[0]} value=\"Delete\" onClick=\"deleteEntry($data[0], '$data[1]')\"> 
						</td>
					</tr>"); 
										
					$i++;
			
			}
		
			mysql_close($conn);
			
		?>
	
	  </tbody>
    </table>
  </form>
   
  </td>
</tr>
</table>

</center>

</div>

<!--<div id="statusbar"><b>>> </b></div>-->

<div id="bottombar"><b>Contact: asraful.alom@northwestern.edu</b></div>

</body>
</html>

