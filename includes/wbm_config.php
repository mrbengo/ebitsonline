<?php
if(preg_match("/wbm_config.php/i",$_SERVER['PHP_SELF'])) {
	echo "You are not allowed to access this page directly";
    die();
}

function wbmconfig() {
/**
 * Configuration details for UzuriWeb
 * Connect to configuration table
 * Set all initial constants
**/
$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
$result = @mysql_query("SELECT * FROM wbm_settings WHERE companyid=$strWbmCompanyid LIMIT 1");
if (@mysql_num_rows($result) > 0) {
	$row = @mysql_fetch_object($result);	
	define("WEB_SITE_NAME", $row->company_name);
	define("WBM_COMPANY_LOGO", $row->company_logo); 
	define("WEB_SITE_EMAIL", $row->site_email);	
	define("WEB_SITE_COUNTRY", $row->country);
	define("WEB_SITE_LOCATION", $row->location);
	define("WEB_SITE_POBOX", $row->pobox);
	define("WEB_SITE_POCODE", $row->pocode);
	define("WEB_SITE_TEL", $row->telephone);
	define("WEB_SITE_URL", $row->website);
	define("WBM_ROWS_PERVIEW", $row->rows_perview);	
	define("WBM_INVOICE_NUMBER", $row->invoicenum); 
	define("WBM_SALESTAX_NAME", $row->salestaxname); 	
	define("WBM_SALES_TAX", $row->salestax); 	
	define("WBM_PIN_NUMBER", $row->pinnumber); 	
	define("WBM_VAT_NUMBER", $row->vatnumber); 	
	define("WBM_ACCOUNT_LIMIT", $row->acclimit); 
}
//Close table connection
@mysql_free_result($result);	

	
	//Get Currency Details
	$countryId = WEB_SITE_COUNTRY;
	$result2 = @mysql_query("SELECT * FROM wbm_countries WHERE ID=$countryId LIMIT 1");
	$row2 = @mysql_fetch_object($result2);	
	define("WBM_CURRENCY_NAME", $row2->currency); 	
	define("WBM_CURRENCY_SYMBOL", $row2->currency_symbol);
	@mysql_free_result($result2);	
}
wbmconfig();
?>