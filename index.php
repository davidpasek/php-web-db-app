<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

########################################
# SET THESE PARAMETERS
########################################
$mysql_server = "localhost";
#$mysql_server = "172.16.11.10";
$mysql_username = "vmware";
$mysql_password = "vmware";
$mysql_dbname = "vmware";
$max_show_rows = 25;
########################################

if (isset($_GET['action'])) {
	$action = $_GET['action'];
} else {
	$action = 'main';
}
$hostname = gethostname();
?>

<html>
<head>
<title><?php echo $hostname; ?></title>
</head>

<body>
<h1>Hostname: <?php echo $hostname; ?></h1>

<h3>
<a href="index.php?action=main">ADD DB RECORD</a> | 
<a href="index.php?action=show">SHOW DB RECORDS</a> | 
<a href="index.php?action=delete">DELETE ALL DB RECORDS</a>
</h3>

<?php
if (isset($_SERVER['REMOTE_ADDR'])) {
	$ip_REMOTE_ADDR 		= $_SERVER['REMOTE_ADDR'];
} else {
	$ip_REMOTE_ADDR 		= 'not-def';
}
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	$ip_HTTP_X_FORWARDED_FOR 	= $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
	$ip_HTTP_X_FORWARDED_FOR 	= 'not-def';
}
if (isset($_SERVER['HTTP_CLIENT_IP'])) {
	$ip_HTTP_CLIENT_IP 		= $_SERVER['HTTP_CLIENT_IP'];
} else {
	$ip_HTTP_CLIENT_IP 		= 'not-def';
}
if (isset($_SERVER['HTTP_X_FORWARDED'])) {
	$ip_HTTP_X_FORWARDED 		= $_SERVER['HTTP_X_FORWARDED'];
} else {
	$ip_HTTP_X_FORWARDED 		= 'not-def';
}
if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
	$ip_HTTP_FORWARDED_FOR		= $_SERVER['HTTP_FORWARDED_FOR'];
} else {
	$ip_HTTP_FORWARDED_FOR 		= 'not-def';
}
if (isset($_SERVER['HTTP_FORWARDED'])) {
	$ip_HTTP_FORWARDED		= $_SERVER['HTTP_FORWARDED'];
} else {
	$ip_HTTP_FORWARDED 		= 'not-def';
}
if (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
	$ip_HTTP_X_CLUSTER_CLIENT_IP	= $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
} else {
	$ip_HTTP_X_CLUSTER_CLIENT_IP 		= 'not-def';
}
?>

<pre>
<?php
print "REMOTE_ADDR: $ip_REMOTE_ADDR\n";
print "The IP address from which the user is viewing the current page.\n\n";

print "HTTP_X_FORWARDED_FOR: $ip_HTTP_X_FORWARDED_FOR\n";
print "A de facto standard for identifying the originating IP address of a client connecting to a web server through an HTTP proxy or load balancer \n\n";

print "HTTP_CLIENT_IP: $ip_HTTP_CLIENT_IP\n";
print "\n\n";

print "HTTP_X_FORWARDED: $ip_HTTP_X_FORWARDED\n";
print "\n\n";

print "HTTP_FORWARDED_FOR: $ip_HTTP_FORWARDED_FOR\n";
print "\n\n";

print "HTTP_FORWARDED: $ip_HTTP_FORWARDED\n";
print "Disclose original information of a client connecting to a web server through an HTTP proxy.\n\n";

print "HTTP_X_CLUSTER_CLIENT_IP: $ip_HTTP_X_CLUSTER_CLIENT_IP\n";
print "\n\n";
?>
</pre>
<hr>

<?php
// insert data into database
$sql = "insert into access_log (
ACCESS_TIME,
ACCESS_TO,
REMOTE_ADDR,
HTTP_X_FORWARDED_FOR,
HTTP_CLIENT_IP,
HTTP_X_FORWARDED,
HTTP_FORWARDED_FOR,
HTTP_FORWARDED,
HTTP_X_CLUSTER_CLIENT_IP
) values (
NOW(),
'$hostname',
'$ip_REMOTE_ADDR',
'$ip_HTTP_X_FORWARDED_FOR',
'$ip_HTTP_CLIENT_IP',
'$ip_HTTP_X_FORWARDED',
'$ip_HTTP_FORWARDED_FOR',
'$ip_HTTP_FORWARDED',
'$ip_HTTP_X_CLUSTER_CLIENT_IP'
)";

// Create DB connection
$conn = new mysqli($mysql_server, $mysql_username, $mysql_password, $mysql_dbname);
// Check DB connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Execute SQL query
if ($conn->query($sql) == TRUE) {
    echo '<div style="background-color:Lime">New access record created successfully</div>';
} else {
    echo '<div style="background-color:Red">Error: ' . $sql . '<br>' . $conn->error . '</div>';
}
?>

<hr>
<pre>
<?php
switch ($action) {
// ACTION DELETE - begin
  case 'delete':
	print "Show records from database\n\n";
	// Execute SQL query
        $sql = 'delete from access_log';
        $result = $conn->query($sql);
        echo '<div style="background-color:Lime">All database records has been deleted</div>';
        break;

// ACTION DELETE - end

// ACTION SHOW - begin
  case 'show':
	print "<b>Show records from database</b>\n";
	// Execute SQL query
        $sql = 'select * from access_log order by access_time desc';
        $result = $conn->query($sql);
        $num_of_records = $result->num_rows;
	if ($num_of_records > 0) {
		// output data of each row
?>
Number of records in DB: <?php echo $num_of_records; ?>

Max number of records to show in table below: <?php echo $max_show_rows; ?>
		<table border=1>
		<tr>
		<th>ACCESS_TIME</th>
		<th>ACCESS_TO</th>
		<th>REMOTE_ADDR</th>
		<th>HTTP_X_FORWARDED_FOR</th>
		<th>HTTP_CLIENT_IP</th>
		<th>HTTP_X_FORWARDED</th>
		<th>HTTP_FORWARDED_FOR</th>
		<th>HTTP_FORWARDED</th>
		<th>HTTP_X_CLUSTER_CLIENT_IP</th>
		</tr>
<?php
		    $i = 0;
		    while($row = $result->fetch_assoc()) {
			$i=$i+1;
			if ($i>$max_show_rows) {break;}
                        echo "<tr>";
			echo "<td>" . $row["ACCESS_TIME"] . "</td>";
			echo "<td>" . $row["ACCESS_TO"] . "</td>";
			echo "<td>" . $row["REMOTE_ADDR"] . "</td>";
			echo "<td>" . $row["HTTP_X_FORWARDED_FOR"] . "</td>";
			echo "<td>" . $row["HTTP_CLIENT_IP"] . "</td>";
			echo "<td>" . $row["HTTP_X_FORWARDED"] . "</td>";
			echo "<td>" . $row["HTTP_FORWARDED_FOR"] . "</td>";
			echo "<td>" . $row["HTTP_FORWARDED"] . "</td>";
			echo "<td>" . $row["HTTP_X_CLUSTER_CLIENT_IP"] . "</td>";
                        echo "</tr>";
		    }
?>
			</table>
<?php
	} else {
	    echo "0 results\n";
	}
	break;  
// ACTION SHOW - end

}
?>

</pre>

<?php
// Close DB connection
$conn->close();
?>
</body>
</html>
