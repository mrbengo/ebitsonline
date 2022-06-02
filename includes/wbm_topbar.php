<?php
if(preg_match("/wbm_topbar.php/i",$_SERVER['PHP_SELF'])) {
	echo "You are not allowed to access this page directly";
    die();
}

/**
 * Topbar section for UzuriWeb
**/
echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20%" align="left" height="82" valign="top">
	<a href="dashboard.php"><img alt="Uzuriweb" src="images/uzuriweb-logo.png" width="253" height="60" border="0"></a></td>
    <td width="80%" align="left">
	<table width="100%" border="0" style="margin-top:38px;" cellpadding="0" cellspacing="0">
        <tr>
          <td width="86%" align="left" valign="bottom">
			<div id="tabs10">
			<ul>
			<li><a href="dashboard.php" target="_parent" class="logout"><span>Dashboard</span></a></li>
			<li><a href="myclients_mgr.php?searchmode=none" target="_parent" class="logout"><span>Clients</span></a></li>
			<li><a href="hosting_mgr.php?searchmode=none" target="_parent" class="logout"><span>Web Services</span></a></li>
			<li><a href="webprojects_mgr.php?searchmode=Nosearch&prjtype=1" target="_parent" class="logout"><span>Project Management</span></a></li>
			<li><a href="pro_invoices_mgr.php?searchmode=none" target="_parent" class="logout"><span>Financial Accounting</span></a></li>
			<li><a href="reports_mgr.php" target="_parent" class="logout"><span>Reports</span></a></li>
			<li><a href="config_mgr.php" target="_parent" class="logout"><span>Settings</span></a></li>
			</ul>
			</div>
		</td>
        <td width="14%" style="padding-left:50px;" valign="bottom">
			<div id="tabs10">
			<ul>
			<li><a href="logout.php" target="_parent" class="logout"><span>Logout</span></a></li>
			</ul>
			</div>
		</td>
        </tr>
    </table>
	</td>
  </tr>
</table>';
?>