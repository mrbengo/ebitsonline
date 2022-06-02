<?php
//authtentication module for each page
session_start();

if ($use_auth == true){
	if ($_SESSION['uzuriweb_wbm_acclvl'] != (("client") || ("user") || ("admin") ) ) {
		include 'error_inc.php';  //writes an error page
		//echo "sess_dwp_acclvl: ".$HTTP_SESSION_VARS['sess_dwp_acclvl'];
		exit;	//breaks out of the processing
	}
}
?>