<?php
if(preg_match("/wbm_common.php/i",$_SERVER['PHP_SELF'])) {
	echo "You are not allowed to access this page directly";
    die();
}

/**
 * Header section for UzuriWeb
 * Set all HTML constants
**/
include("./wbm-access/wbm-access.php");		        // Connects to database
include("./includes/wbm_config.php");			// Loads UzuriWeb's Default Values
include("./functions/login_fns.php");			// Access login functions
include("./functions/passwordreminder_fns.php");// Password reminder functions

function formatMoney($number, $fractional=false) {
    if ($fractional) {
        $number = sprintf('%.2f', $number);
    }
    while (true) {
        $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
        if ($replaced != $number) {
            $number = $replaced;
        } else {
            break;
        }
    }
    return $number;
} 
?>