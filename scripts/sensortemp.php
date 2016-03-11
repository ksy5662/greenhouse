<?php 
function readSensor($sensor) 
{ 
	$output = array(); 
	$return_var = 0; 
	$i=1; 
	exec('cat /sys/bus/w1/devices/28-0316005ec0ff/w1_slave, $output, $return_var); 
  	print ($output);
	
	#while (substr($output[$i],0,1)!="H") 
	#{ 
        #        $i++; 
	#} 
	#$humid=substr($output[$i],11,5); 
        #$temp=substr($output[$i],33,5); 
        #	$db = mysql_connect("localhost","datalogger","datalogger") or die("DB Connect error"); 
	#mysql_select_db("datalogger"); 
	#$q = "INSERT INTO datalogger VALUES (now(), $sensor, '$temp', '$humid',0)"; 
	#mysql_query($q); 
	#mysql_close($db);
	 
	return; 
} 
readSensor(9); 
//readSensor(8); 
//readSensor(7); 
?> 

