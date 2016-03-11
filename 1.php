<?PHP
require_once("./include/membersite_config.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
<title>RasPiViv.com - Vivarium 1</title>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>

<!-- VIV 1 TEMP GAUGE -->

    <script type="text/javascript">
      google.load("visualization", "1", {packages:["gauge"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['TEMP C', <?php
$servername = "localhost";
$username = "datalogger";
$password = "datalogger";
$dbname = "datalogger";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT dryTemp FROM mollier ORDER BY date_time DESC LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo $row["dryTemp"];
    }
} else {
    echo "0 results";
}

mysqli_close($conn);
?> 

],

        ]);

        var options = {
          width: 400, height: 200,
          redFrom: 26.6, redTo: 100,
          yellowFrom:20, yellowTo: 25.5,
          minorTicks: 5
        };

        var chart = new google.visualization.Gauge(document.getElementById('charttemp_div'));

        chart.draw(data, options);


      }
    </script>

<!-- VIV 1 HUM GAUGE -->
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["gauge"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['HUM %', <?php
$servername = "localhost";
$username = "datalogger";
$password = "datalogger";
$dbname = "datalogger";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT wetTemp FROM mollier ORDER BY date_time DESC LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo $row["wetTemp"];
    }
} else {
    echo "0 results";
}

mysqli_close($conn);
?> ],

        ]);

        var options = {
          width: 400, height: 200,
          redFrom: 0, redTo: 80,
          yellowFrom:80, yellowTo: 100,
          minorTicks: 5
        };

        var chart = new google.visualization.Gauge(document.getElementById('charthum_div'));

        chart.draw(data, options);


      }
    </script>
<!-- VIV 1 HUM GRAPH -->
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['TIME', 'HUMIDITY', ],
<?php 
$db = mysql_connect("localhost","datalogger","datalogger") or die("DB Connect error"); 
mysql_select_db("datalogger"); 

$q=   "select * from mollier "; 
$q=$q.""; 
$q=$q."order by date_time desc "; 
$q=$q."limit 720"; 
$ds=mysql_query($q);  

while($r = mysql_fetch_object($ds)) 
{ 
	echo "['".$r->date_time."', "; 
	echo " ".$r->dryTemp." ],"; 

} 
?> 
        ]);

	var options = {
	title: 'HUMIDITY LAST HOUR',
	curveType: 'function',
	legend: { position: 'none' },
	hAxis: { textPosition: 'none', direction: '-1' },
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

        chart.draw(data, options);
      }
    </script>

<!-- VIV 1 TEMP GRAPH -->

    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['시간', '건구온도', '습구온도', '상대습도','절대습도','이슬점온도','수분부족량'],
<?php 
$db = mysql_connect("localhost","datalogger","datalogger") or die("DB Connect error"); 
mysql_select_db("datalogger"); 

$q=   "select * from mollier "; 
$q=$q.""; 
$q=$q."order by date_time desc "; 
$q=$q."limit 720"; 
$ds=mysql_query($q); 

while($r = mysql_fetch_object($ds)) 
{ 
	echo "['".$r->date_time."', "; 
	echo " ".$r->dryTemp.", ";
	echo " ".$r->wetTemp.", ";  
	echo " ".$r->RH.", ";
	echo " ".$r->AH.", ";
	echo " ".$r->DewPoint.", ";
	echo " ".$r->HD." ],";

} 
?> 
        ]);

	var options = {
	title: '온실 환경 정보',
	curveType: 'function',
	hAxis: { showTextEvery: 50 , direction: '-1' },
          vAxes: {0: {viewWindowMode:'explicit',
                      viewWindow:{
                                  max:35,
                                  min:0
                                  },
                      gridlines: {color: 'transparent'},
                      format:"# C"
                      },
                  1: {gridlines: {color: 'transparent'},
                      format: '#\'%\''},
                  2: {gridlines: {color: 'transparent'},
                  	  textPosition:'in', 
                      format:"# g/m3"},
                  },
          series: {0: {targetAxisIndex:0},
                   1:{targetAxisIndex:0},
                   2:{targetAxisIndex:1},
                   3:{targetAxisIndex:2},
                   4:{targetAxisIndex:0},
                   5:{targetAxisIndex:2},
                  },
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart2_div'));

        chart.draw(data, options);
      }
    </script>
</head>
<body>
<div class="jumbotron">
<div class="container">
<?php include 'menu.php';?>
<h2>1</h2>
<?php include 'time.php';?>
</div>
</div>
<div class="container">
<h3>CURRENT CONDITIONS</h3>
  <div class="row">
    <div class="col-sm-3">
    <div id="charttemp_div" style="width: 200px; height: 200px;"></div>
    </div>
    <div class="col-sm-3">
    <div id="charthum_div" style="width: 200px; height: 200px;"></div>
    </div>
    </div>
<hr>
    </div>
<div class="container">
    <div id="chart2_div" style="width: auto; height: 500px;"></div>
    <?php include 'footer.php';?>
</div>
</body>
</html>
