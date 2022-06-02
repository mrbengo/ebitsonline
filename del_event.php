<?
session_start();
include 'includes/vars_inc.php'; 
include 'includes/auth_inc.php';
include 'includes/func_inc.php';

//Check if user is logged in
$uzuriweb_log_val=$_SESSION['uzuriweb_wbm_authenticate_id'];
if ($uzuriweb_log_val <> "")
{
	$query = "SELECT * FROM `wbm_projectcal` where `eventID` = '".$_GET['eventID']."' LIMIT 1;";
	$result = dbase_query($query);
	$row = mysql_fetch_array($result);
	//just a little added security.  we check to see that the projectID of the event and the projectID that the user is on match
	if ($row['projectID'] != $_SESSION['uzuriweb_wbm_projectvars']['ID']){
		echo "You are not authorized to preform this action.";
		exit;
	}
	//remote from the database
	$query = "DELETE FROM `wbm_projectcal` WHERE `eventID` = '".$_GET['eventID']."' LIMIT 1;";
	dbase_query($query);
	//should have all worked.
	@ header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/add_event.php?procmsg=delsuc");
}
?>