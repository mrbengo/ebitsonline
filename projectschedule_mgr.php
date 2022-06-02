<?php 
session_start();
//Include common files
include('includes/wbm_common.php');
include 'templates/includes.php';

//Check if user is logged in
$uzuriweb_log_val=$_SESSION['uzuriweb_wbm_authenticate_id'];
if ($uzuriweb_log_val <> "")
{
	$strID = rtrim($_GET['ID']);
	if($strID <>""){
	$_SESSION['uzuriweb_wbm_projectvars']['projectID'] = $strID;
	set_proj_vars($strID);  //sets the various stuff for the project
	set_user_vars($uzuriweb_log_val);  //sets the various info for the project
	}
	
	$strMode = rtrim($_GET['mode']);
	if ($strMode==""){
	header('Location: month.php');
	}
	elseif ($strMode=="month"){
	header('Location: month.php');
	}
	//End of Setting action modes
}
else
{
	header('Location: insufficientpermission.php');
}
?>