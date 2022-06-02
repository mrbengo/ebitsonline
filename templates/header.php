<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<title><?php //do some naming here
					  if ( isset($_SESSION['uzuriweb_wbm_projectvars']['projectname']) ) {
					  	echo $_SESSION['uzuriweb_wbm_projectvars']['projectname']." - ".$page_title;
					  }
					  else
					  {
					  	echo "UzuriWeb - ".$page_title;
					  } 
					  ?></title>
	<style type="text/css" media="all">@import "style.css";</style>
	<link rel="stylesheet"   type="text/css"   media="print" href="print.css" />
	<link rel="SHORTCUT ICON" href="assets/siteicon.ico" />
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<script src="includes/scripts.js" type="text/javascript" language="javascript"></script>
</head>
<body>
<table summary="Big Table" border="0" width="100%" cellspacing="0" cellpadding="0" >
<!-- top bar -->
<tr>
	<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0" class="printhide">
			<tr class="bluebar">
				<td>
					<img src="assets/logo.gif" alt="Logo" align="left" />
				</td>
				<td><!-- got rid of the M :(
					<img src="assets/side_m.gif" alt="MCAD M" align="right" />
					-->
				</td>
			</tr>
		</table>
	</td>
</tr>
<!-- /top bar -->

<!-- title bar -->
<tr>
	<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0" class="printhide">
			<tr class="titlerow">
				<td width="1%">
					<img src="assets/spacer.gif" alt="spacer image" width="150px" height="13px"/>
				</td>
				<td width="99%">
					<span class="topheading"><?php  //do some naming here
					  if ( isset($_SESSION['uzuriweb_wbm_projectvars']['projectname']) ) {
					  	echo $_SESSION['uzuriweb_wbm_projectvars']['projectname'];
					  }
					  else
					  {
					  	echo "UzuriWeb";
					  } 
					  ?></span>
				</td>
			</tr>
		</table>
	</td>
</tr>
<!-- /title bar -->


<tr>
		<td>
		
		<table border="0" width="100%" cellspacing="0" cellpadding="0" class="maintable">
			<tr>
				<td width="1%" valign="top">
				<table border="0" width="100%" cellspacing="0" cellpadding="0" class="printhide">
						<tr>
							<td colspan="4"><img src="assets/spacer.gif" alt="spacer image" width="150px" height="1px"/><td>
						</tr>
						<tr>
							<td width="27px"><img src="assets/spacer.gif" alt="spacer image" width="27px" height="500px"/></td>
							<td width="106px" valign="top">
							<!-- nav menu area -->
							
							
							<?php include ("nav.php"); ?>
							
							
							
							<!-- /nav menu area -->
							</td>
							<td width="1px" class="divider">
								<img src="assets/divider_blue.gif" width="1" height="100%" alt="Blue Spacer" border="0" />							
							</td>
							<td width="16px">
								<img src="assets/spacer.gif" alt="spacer image" width="16px" height="1px"/>
							</td>
						</tr>
					</table>
				</td>
				<td width="99%" valign="top"><br />
					<!-- content area -->

					
					
