<?php
if(preg_match("/wbm_header.php/i",$_SERVER['PHP_SELF'])) {
	echo "You are not allowed to access this page directly";
    die();
}

/**
 * Header section for UzuriWeb
 * Set all HTML constants
**/
function head() {
	global $uzuriwebpgname, $strPath, $strTitle;
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
	echo "<html>\n";
	echo "<head>\n";
	echo '<title>'.$strTitle.'</title>';
	echo "\n";
	/* include meta tags and any javascript to be processed. */
	include("includes/wbm_meta.php");
	include("includes/wbm_javascript.php");
	/* include stylesheet. */
	echo '<LINK href="'.$strPath.'wbm-css/uzuriweb.css" type=text/css rel=stylesheet>';
	if(($uzuriwebpgname=="day")||($uzuriwebpgname=="month")||($uzuriwebpgname=="week")){
	echo '<LINK href="'.$strPath.'wbm-css/style.css" type=text/css rel=stylesheet>';
	}
	?>
	<link rel="stylesheet" href="ajax/validator/css/validationEngine.jquery.css" type="text/css" media="screen" title="no title" charset="utf-8" />
	<link rel="stylesheet" href="ajax/validator/css/template.css" type="text/css" media="screen" title="no title" charset="utf-8" />
	<script src="ajax/jquery-1.4.2.min.js" type="text/javascript"></script>
	<script src="ajax/validator/js/jquery.validationEngine-en.js" type="text/javascript"></script>
	<script src="ajax/validator/js/jquery.validationEngine.js" type="text/javascript"></script>
	
	<script>	
	$(document).ready(function() {
		$("#formID").validationEngine()
		//$.validationEngine.loadValidation("#date")
		//alert($("#formID").validationEngine({returnIsValid:true}))
		//$.validationEngine.buildPrompt("#date","This is an example","error")	 		 // Exterior prompt build example
		//$.validationEngine.closePrompt(".formError",true) 							// CLOSE ALL OPEN PROMPTS
	});
	</script>	
	<script language="JavaScript" type="text/javascript" src="ajax/jquery.chainedSelects.js"></script>
	<style>
	#loading{
		position:absolute;
		top:0px;
		right:0px;
		background:#ff0000;
		color:#fff;
		font-size:14px;
		font-familly:Arial;
		padding:2px;
		display:none;
	}
	.style8 {font-weight: bold}
	</style>
	<?
	echo "</head>\n";
	echo "<body>\n";
}
head();
?>