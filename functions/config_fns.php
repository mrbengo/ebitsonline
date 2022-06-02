<?php
if(preg_match("/config_fns.php/i",$_SERVER['PHP_SELF'])) {
	echo "You are not allowed to access this page directly";
    die();
}

/**
 * Configuration file for UzuriWeb
 * Generate the form
**/
function GenerateForm($action, $notify) {
	
	// Select Current Configuration details
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	$result=@mysql_query("SELECT * FROM wbm_settings WHERE companyid=$strWbmCompanyid LIMIT 1");
	if(@mysql_num_rows($result) <> 0) {		
		// Grab Record Values
		$row = @mysql_fetch_object($result);
		$strWbmID = $row->ID;
		$strCompanyName = $row->company_name;
		$strCompanyLogo = $row->company_logo;
		$strContactEmail = $row->site_email;
		$strFname = $row->fname;
		$strLname = $row->lname;
		$strCountry = $row->country; 
		$strLocation = $row->location;
		$strPobox = $row->pobox;
		$strPocode = $row->pocode;
		$strTelephone = $row->telephone;
		$strCellphone = $row->cellphone;
		$strWebsite = $row->website;
		$strWbmRowsperview = $row->rows_perview; 	
		$strInvoicenum = $row->invoicenum;
		$strSalestaxName = $row->salestaxname; 	
		$strSalestax = $row->salestax; 	
		$strPinnumber = $row->pinnumber; 	
		$strVatnumber = $row->vatnumber; 
		$strReceiptnum = $row->receiptnum; 
				
	    if(!$strCompanyLogo) 
		  {
		  $strCompanyLogo = 'http://www.uzuriweb.com/upload/blank.gif';
		  }
		else
		  {
		  $strCompanyLogo = 'http://www.uzuriweb.com/upload/thumb/'.$strCompanyLogo;
		  }

	}
	echo '<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
	<tr>
	<td width="70%" class="breadcrumbs"><strong>NOTE: Keep these details current. They are important for the functionality of the system.</strong></td>
	<td width="30%" align="right">&nbsp;</td>
	</tr>
	</table>';
	echo '<hr width="98%" align="center">';
	?>
	<?
	if($notify==237){
	?>
	<table width="98%" align="center" border="0" bgcolor="#F3F5F8" cellpadding="0" cellspacing="0">
	<tbody>
	<tr>
	<td style="background-image: url(images/lefttop.jpg);" width="6" height="6"></td>
	<td style="background: transparent url(images/topline.jpg) repeat-x scroll 0% 0%;" height="6"></td>
	<td style="background-image: url(images/righttop.jpg);" width="6" height="6"></td>
	</tr>
	<tr>
	<td style="background: transparent url(images/left.jpg) repeat-y scroll 0% 0%;" height="6"></td>
	<td valign="middle" align="center">	
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td width="98%" align="center">Settings Updated Successfully.</td>
	</tr>
	</table>
	</td>
	<td style="background: transparent url(images/right.jpg) repeat-y scroll 0% 0%;" height="6"></td>
	</tr>
	<tr>
	<td style="background-image: url(images/leftbottom.jpg);" width="6" height="6"></td>
	<td style="background: transparent url(images/bottomline.jpg) repeat-x scroll 0% 0%;" height="6"></td>
	<td style="background-image: url(images/rightbottom.jpg);" width="6" height="6"></td>
	</tr>
	</tbody>
	</table>
	<br />
	<?
	}
	?>
	<table width="98%" align="center" border="0" bgcolor="#F3F5F8" cellpadding="0" cellspacing="0">
	<tbody>
	<tr>
	<td style="background-image: url(images/lefttop.jpg);" width="6" height="6"></td>
	<td style="background: transparent url(images/topline.jpg) repeat-x scroll 0% 0%;" height="6"></td>
	<td style="background-image: url(images/righttop.jpg);" width="6" height="6"></td>
	</tr>
	<tr>
	<td style="background: transparent url(images/left.jpg) repeat-y scroll 0% 0%;" height="6"></td>
	<td valign="middle" align="center">	
	<FORM name="formID" id="formID" action="<?=$_SERVER['PHP_SELF']?>" METHOD="POST">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
	<thead>
	<tr>
	<td colspan="2">&nbsp;</td>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td width="32%" class="formscss" align="right">Your Company Name :*</td>
	<td width="68%"><input name="company_name" type="text" id="company_name" class="validate[required]" style="width:400px;" value="<?PHP echo $strCompanyName;?>"></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Company Logo :*</td>
	<td><img alt="company logo" src="<?PHP echo $strCompanyLogo;?>" name="pic1" align="absmiddle" border="1"></td>
	</tr>
	<tr>
	<td class="formscss" align="right">Active Contact Email :*</td>
	<td><input name="site_email" type="text" id="site_email" style="width:400px;" class="validate[required,custom[email]] text-input" value="<?PHP echo $strContactEmail;?>"></td>
	</tr>
	<tr>
	<td colspan="2" class="formscss">&nbsp;</td>
	</tr>
	<tr>
	<td align="right" class="formscss">First Name <em>(Contact Person)</em> :*</td>
	<td><input name="fname" type="text" id="fname" style="width: 400px;" class="validate[required,custom[onlyLetter],length[0,100]] text-input" value="<?php echo $strFname;?>" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Last Name <em>(Contact Person)</em> :*</td>
	<td><input name="lname" type="text" id="lname" style="width: 400px;" class="validate[required,custom[onlyLetter],length[0,100]] text-input" value="<?php echo $strLname;?>" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Country: *</td>
	<td>
	<select name="country" id="country" class="validate[required]" style="width: 400px;">
	<?PHP
	$sql = "SELECT * FROM wbm_countries ORDER BY country ASC";
	$result = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_array($result))
	{
	$strCountryID = $row['ID'];
	$strCountryName = $row['country'];
	?>
	<option value="<?PHP echo $strCountryID;?>" <?PHP if($strCountryID == $strCountry) {echo "Selected";}?>><?PHP echo $strCountryName;?></option>
	<?PHP
	}
	?>
	</select>
	</td>
	</tr>
	<tr>
	<td align="right" class="formscss">Physical Location  :</td>
	<td><textarea name="location" id="location" style="width: 400px; height:100px;" class="buttontxt" ><?php echo $strLocation;?></textarea></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Postal Address  :</td>
	<td><input name="pobox" type="text" id="pobox" style="width: 400px;" class="validate[required,custom[onlyNumber]] text-input" value="<?php echo $strPobox;?>" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Zip/Postal Code :</td>
	<td><input name="pocode" type="text" id="pocode" style="width: 400px;" class="validate[required,custom[onlyNumber]] text-input" value="<?php echo $strPocode;?>" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Telephone Number :</td>
	<td><input name="telno" type="text" id="telno" style="width: 400px;" class="validate[optional,custom[phone]] text-input" value="<?php echo $strTelephone;?>" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Cellphone Number :</td>
	<td><input name="cellno" type="text" id="cellno" style="width: 400px;" class="validate[optional,custom[phone]] text-input" value="<?php echo $strCellphone;?>" /></td>
	</tr>
	<tr>
	<td align="right" class="formscss">Website URL :</td>
	<td><input name="website" type="text" id="website" style="width: 400px;" class="validate[optional,length[0,100]] text-input" value="<?php echo $strWebsite;?>" /></td>
	</tr>
	<tr>
	<td colspan="2" class="formscss">&nbsp;</td>
	</tr>
	<tr>
	<td class="formscss" align="right">Record Per Page :</td>
	<td>
	<select name="records_perpage" id="records_perpage" style="width: 400px;">
	<option value="10" <?PHP if($strWbmRowsperview==10){echo "Selected";}?>>10</option>
	<option value="25" <?PHP if($strWbmRowsperview==25){echo "Selected";}?>>25</option>
	<option value="50" <?PHP if($strWbmRowsperview==50){echo "Selected";}?>>50</option>
	<option value="100" <?PHP if($strWbmRowsperview==100){echo "Selected";}?>>100</option>
	<option value="500" <?PHP if($strWbmRowsperview==500){echo "Selected";}?>>500</option>
	</select>
	</td>
	</tr>
	<tr>
	<td class="formscss" align="right">Invoice Number :</td>
	<td><input name="invoice_num" type="text" id="invoice_num" style="width:400px;" class="validate[required]" value="<?PHP echo $strInvoicenum;?>"></td>
	</tr>
	<tr>
	<td class="formscss" align="right">Sales Tax Name :</td>
	<td><input name="sales_taxname" type="text" id="sales_taxname" style="width:400px;" class="validate[required]" value="<?PHP echo $strSalestaxName;?>"></td>
	</tr>
	<tr>
	<td class="formscss" align="right">Sales Tax (%) :</td>
	<td><input name="sales_tax" type="text" id="sales_tax" style="width:400px;" class="validate[required,custom[onlyNumber]] text-input" value="<?PHP echo $strSalestax;?>"></td>
	</tr>
	<tr>
	<td class="formscss" align="right">PIN Number :</td>
	<td><input name="pin_number" type="text" id="pin_number" style="width:400px;" value="<?PHP echo $strPinnumber;?>"></td>
	</tr>
	<tr>
	<td class="formscss" align="right">VAT Number :</td>
	<td><input name="vat_number" type="text" id="vat_number" style="width:400px;" value="<?PHP echo $strVatnumber;?>"></td>
	</tr>
	<tr>
	<td class="formscss" align="right">Receipt Number :</td>
	<td><input name="receipt_number" type="text" id="receipt_number" style="width:400px;" value="<?PHP echo $strReceiptnum;?>"></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td><input type="image" src="images/savebutton.png" border="0" alt="Submit Entry" title="Submit Entry">
	<input type="hidden" name="action" value="<?=$action?>" />
	<input type="hidden" name="theID" value="<?=$strWbmID?>" />
	</td>
	</tr>
	</tbody>
	</table>
	</form>	
	</td>
	<td style="background: transparent url(images/right.jpg) repeat-y scroll 0% 0%;" height="6"></td>
	</tr>
	<tr>
	<td style="background-image: url(images/leftbottom.jpg);" width="6" height="6"></td>
	<td style="background: transparent url(images/bottomline.jpg) repeat-x scroll 0% 0%;" height="6"></td>
	<td style="background-image: url(images/rightbottom.jpg);" width="6" height="6"></td>
	</tr>
	</tbody>
	</table>
	<?
}

// Process Submitted Form
function ProcessForm($formValues) {
	// Grab Values from array
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	$strTheID = preg_replace('/\'/', "''", trim($formValues["theID"]));
	$strCompanyName = preg_replace('/\'/', "''", trim($formValues["company_name"]));
	$strCompanyEmail = preg_replace('/\'/', "''", trim($formValues["site_email"]));
	$strFname = preg_replace('/\'/', "''", trim($formValues["fname"]));
	$strLname = preg_replace('/\'/', "''", trim($formValues["lname"]));
	$strCountry = preg_replace('/\'/', "''", trim($formValues["country"]));
	$strLocation = preg_replace('/\'/', "''", trim($formValues["location"]));
	$strPobox = preg_replace('/\'/', "''", trim($formValues["pobox"]));
	$strPocode = preg_replace('/\'/', "''", trim($formValues["pocode"]));
	$strTelno = preg_replace('/\'/', "''", trim($formValues["telno"]));
	$strCellno = preg_replace('/\'/', "''", trim($formValues["cellno"]));
	$strWebsite = preg_replace('/\'/', "''", trim($formValues["website"]));
	$strRecPerpage = preg_replace('/\'/', "''", trim($formValues["records_perpage"]));
	$strInvNum = preg_replace('/\'/', "''", trim($formValues["invoice_num"]));
	$strSalesTaxName = preg_replace('/\'/', "''", trim($formValues["sales_taxname"]));
	$strSalesTax = preg_replace('/\'/', "''", trim($formValues["sales_tax"]));
	$strPinNum = preg_replace('/\'/', "''", trim($formValues["pin_number"]));
	$strVatNum = preg_replace('/\'/', "''", trim($formValues["vat_number"]));
	$strReceiptnum = preg_replace('/\'/', "''", trim($formValues["receipt_number"]));
	
	//Update database
	$sql = "UPDATE wbm_settings SET company_name='$strCompanyName', site_email='$strCompanyEmail', fname='$strFname', lname='$strLname', country='$strCountry', location='$strLocation', pobox='$strPobox', pocode='$strPocode', telephone='$strTelno', cellphone='$strCellno', website='$strWebsite', rows_perview='$strRecPerpage', invoicenum='$strInvNum', salestaxname='$strSalesTaxName', salestax='$strSalesTax', pinnumber='$strPinNum', vatnumber='$strVatNum', receiptnum='$strReceiptnum' WHERE ID=$strTheID AND companyid=$strWbmCompanyid LIMIT 1";
	$result = mysql_query($sql) or die(mysql_error());
	
	// Redirect to configuration page
	header('Location: config_mgr.php?nfcode=237');
	exit();
}
?>