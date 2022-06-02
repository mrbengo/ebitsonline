<?PHP
# THIS SECTION ALLOWS YOU TO CONNECT TO THE MYSQL DATABASE
# DESIGNED BY EBITS ONLINE.

# MySQL host name, usually localhost
$host = "localhost";		
# MySQL user name
$uid  = "ebitambo_usr";		
# MySQL password
$pwd  = "5c$6O05#AX21";			
# MySQL database name
$db   = "ebitambo_erp";		
# Database connection using the details above
$xConn = mysql_connect($host, $uid, $pwd) or die("Couldn't connect to MySQL Server on $host");
mysql_select_db($db, $xConn);
?>