<?php
if(preg_match("/wbm_meta.php/i",$_SERVER['PHP_SELF'])) {
	echo "You are not allowed to access this page directly";
    die();
}

/**
 * CMS Meta section for UzuriWeb
**/

function pagemeta() {
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
	echo "<meta name=\"author\" content=\"Ebits Online.\">\n";
	echo "<meta name=\"copyright\" content=\"Copyright (c) 2009 UzuriWeb.\">\n";
	echo "<meta name=\"keywords\" content=\"UzuriWeb, Ebits Online\">\n";
	echo "<meta name=\"description\" content=\"UzuriWeb Content Management System\">\n";
	echo "<meta name=\"robots\" content=\"index, follow\">\n";
	echo "<meta name=\"revisit-after\" content=\"1 days\">\n";
	echo "<meta name=\"rating\" content=\"general\">\n";
  }
pagemeta();
?>