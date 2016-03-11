<?php 
function readSensor($sensor) 
{ 
	$output = array(); 
	$output2 = array();
	$return_var = 0; 
	$return_var2 = 0;
	$i=1; 
	exec('cat /sys/bus/w1/devices/28-0316004d7fff/w1_slave', $output, $return_var); 
  	$crc1=substr($output[0],36,3);
  	$arrOutput = split(" ",$output[0]);
  	print($arrOutput[11]);

	//print($output[0]);
	//print("\n");
	//print ($output[1]);
	//print("\n");
	exec('cat /sys/bus/w1/devices/28-0316005ec0ff/w1_slave', $output2, $return_var2);
	//print($output2[0]);
	print("\n");
	$crc2=substr($output2[0],36,3);
	$arrOutput2 = split(" ",$output2[0]);
  	print($arrOutput2[11]);
	//print("{$crc1}{$crc2}");
	if ($arrOutput[11]=='YES'&& $arrOutput2[11]=='YES') {
	//print ($output2[1]);
	$tempdryint=substr($output[1],29,2);
	$tempdryfloat=substr($output[1],31,3);
	$tempwetint=substr($output2[1],29,2);
	$tempwetfloat=substr($output2[1],31,3);
	print("\nDry Bulb Temperature: ");
	//echo("\nDry Bulb Temperature: ");
	$tempdry="{$tempdryint}.{$tempdryfloat}";
	print($tempdry);
	//echo($tempdry);
	print("\nWet Bulb Temperature: ");
	//echo("\nWet Bulb Temperature: ");
	$tempwet="{$tempwetint}.{$tempwetfloat}";
	print($tempwet);
	//echo($tempwet);
	$drytempfloat=floatval($tempdry);
	$wettempfloat=floatval($tempwet);
	$drystring = number_format($drytempfloat,1,'.','');
	//print("\n{$drystring}");

	$pva = (6.112 * (exp((17.67 * $wettempfloat) / ($wettempfloat + 243.5))) - 1027.0 * ($drytempfloat - $wettempfloat) * 0.00066 * (1 + (0.00115 * $wettempfloat)));
	$pvs = (6.112 * exp((17.67 * $drytempfloat) / ($drytempfloat + 243.5)));
        $dvt = ((0.00036 * ($drytempfloat * $drytempfloat * $drytempfloat)) + 0.00543 * ($drytempfloat * $drytempfloat) + 0.37067 * $drytempfloat + 4.81865);
        $hr = $pva * 100 / $pvs;
        $ha = $hr * $dvt / 100;
        $dew = (243.5 * log($pva / 6.112)) / (17.67 - log($pva / 6.112));
        $dva = $hr * $dvt / 100;
        $he = (621.9907 * ($pva * 100)) / ((1027.0 * 100) - ($pva * 100));
        $hd = 100.0 * $dvt / 100 - $ha;
	$hrString = number_format($hr,1,'.','');
	$haString = number_format($ha,1,'.','');
	$dewString = number_format($dew,1,'.','');
	$hdString = number_format($hd,1,'.','');
	print("\nRelative Humidity: {$hrString} %");
	print("\nAbsolute Humidity: {$haString} g/m3");
	print("\nDewPoint: {$dewString} C");
	print("\nHumidity Deficient: {$hdString} g/m3"); 

	//echo("\nRelative Humidity: {$hrString} %");
        //echo("\nAbsolute Humidity: {$haString} g/m3");
        //echo("\nDewPoint: {$dewString} C");
        //echo("\nHumidity Deficient: {$hdString} g/m3");

	$db = mysql_connect("localhost","datalogger","datalogger") or die("DB Connect error"); 
	mysql_select_db("datalogger"); 
	$q = "INSERT INTO mollier VALUES (now(), '$tempdry', '$tempwet', '$hrString','$haString','$dewString','$hdString')"; 
	mysql_query($q); 
	mysql_close($db); 
	
	$url = 'https://api.thethings.io/v2/things/e5liLasnnRcHcaH6LUfKAWizB7w5PmecXtbLEiVUrmA';
$fields = array(
	urlencode('건구온도') => $tempdry,
	urlencode('습구온도') => $tempwet,
	urlencode('상대습도') => $hrString,
	urlencode('절대습도') => $haString,
	urlencode('이슬점온도') => $dewString,
	urlencode('수분부족량') => $hdString,
);

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);
	}

	return; 
} 
readSensor(9); 
//readSensor(8); 
//readSensor(7); 
?> 

