<?php
$remote_ip = $_SERVER['REMOTE_ADDR'];
//echo $remote_ip;

if(!strstr($remote_ip, '165.124.') && !strstr($remote_ip, '129.105.')){
	echo "Sorry, only accessible from NU VPN..";
	exit();
}
?>
