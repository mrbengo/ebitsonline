<?php 
ob_start();
error_reporting (E_ALL ^ E_NOTICE);

session_start();
$_SESSION['uzuriweb_wbm_authenticate_id'];
//Include common files
include('includes/wbm_common.php');

//Check if user is logged in
$uzuriweb_log_val=$_SESSION['uzuriweb_wbm_authenticate_id'];
if ($uzuriweb_log_val <> "")
{
	$strWbmCompanyid = $_SESSION['uzuriweb_wbm_authenticate_id'];
	$uzuriwebpgname="dashboard";
	$strPath = "";
	$strTitle = 'UzuriWeb Dashboard';
	include('includes/wbm_header.php');
    ?>
	
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
    <td width="1%" rowspan="3" valign="top"><img src="images/spacer.gif" width="50" height="10" /></td>
    <td width="98%" valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="2" valign="top">
		<!--###### START TOPBAR ######-->
        <?php include("includes/wbm_topbar.php")?>
		<!--###### END TOPBAR ######-->		
		</td>
        </tr>
	  <tr>
	    <td colspan="2" valign="top">
	      <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="63%" valign="top" class="breadcrumbs"><strong>YOU ARE HERE:</strong>Dashboard</td>
              <td width="37%" align="right" class="breadcrumbs">&nbsp;</td>
            </tr>
          </table>
		  </td>
        </tr>
	  <tr>
	    <td height="6" colspan="2" valign="top" bgcolor="#F3F5F8" style="background: transparent url(images/bottomline.jpg) repeat-x scroll 0% 0%;"><img alt="" src="images/spacer.gif" width="10" height="10"></td>
	    </tr>
	  <tr>
	    <td width="4%" valign="top">
		<!--###### START TOPBAR ######-->
        <?php include("sidebars/dashboard-sidebar.php")?>
		<!--###### END TOPBAR ######-->		
		</td>
	    <td width="96%" valign="top" bgcolor="#F3F5F8">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2" style="background: transparent url(images/topline.jpg) repeat-x scroll 0% 0%;" height="6"></td>
          </tr>
		<tr>
            <td width="5"><img alt="" src="images/spacer.gif" width="5" height="5"></td>
            <td valign="top" class="titletx"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="53%" class="titletx"><?PHP echo $strTitle;?></td>
                <td width="47%" class="titletx">&nbsp;</td>
            </table></td>
	    </tr>
          <tr>
            <td width="5"><img alt="" src="images/spacer.gif" width="5" height="5"></td>
            <td valign="top">
			    <!--###### ACCESS FUNCTIONS ######-->

          <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td colspan="3">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" valign="top">
			<table width="100%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
			<tr>
			<td width="33%" class="breadcrumbs"><strong>Welcome to your Uzuriweb Account.</strong></td>
			<td width="67%" class="breadcrumbs" align="right"><strong>COMPANY:</strong>  <?php echo WEB_SITE_NAME;?> &nbsp;&nbsp;<strong>LOGGED IN AS:</strong>  <?php echo $_SESSION['uzuriweb_wbm_username'];?> &nbsp;&nbsp;</td>
			</tr>
			</table>			</td>
            </tr>
          <tr>
            <td colspan="3" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" valign="top">
			<table width="100%" style="border:solid 1px #000000; background-color:#FFFFFF; padding:10px;" align="center" cellpadding="0" border="0" cellspacing="0">
                    <tr>
                      <td width="10%" class="frmTabletxt" align="center"><a href="webprojects_mgr.php?searchmode=Nosearch&prjtype=1"><img src="images/icons/webdesign.png" width="100" height="100" vspace="2" border="0" /></a></td>
                      <td width="10%" class="frmTabletxt" align="center"><a href="webprojects_mgr.php?searchmode=Nosearch&prjtype=2"><img src="images/icons/webdev.png" width="100" height="100" vspace="2" border="0" /></a></td>
                      <td width="10%" class="frmTabletxt" align="center"><a href="webprojects_mgr.php?searchmode=Nosearch&prjtype=3"><img src="images/icons/maintenance.png" width="100" height="100" vspace="2" border="0" /></a></td>
                      <td width="10%" class="frmTabletxt" align="center"><a href="webprojects_mgr.php?searchmode=Nosearch&prjtype=6"><img src="images/icons/sem.png" width="100" height="100" vspace="2" border="0" /></a></td>
                      <td width="10%" class="frmTabletxt" align="center"><a href="webprojects_mgr.php?searchmode=Nosearch&prjtype=7"><img src="images/icons/seo.png" width="100" height="100" vspace="2" border="0" /></a></td>
                      <td width="10%" class="frmTabletxt" align="center"><a href="webprojects_mgr.php?searchmode=Nosearch&prjtype=8"><img src="images/icons/sem.png" width="100" height="100" vspace="2" border="0" /></a></td>
                      <td width="10%" class="frmTabletxt" align="center"><a href="webprojects_mgr.php?searchmode=Nosearch&prjtype=9"><img src="images/icons/content.png" width="100" height="100" vspace="2" border="0" /></a></td>
                    </tr>
                    <tr>
                      <td class="frmTabletxt" align="center">Web Design </td>
                      <td class="frmTabletxt" align="center">Web Development </td>
                      <td class="frmTabletxt" align="center">Web Maintenance </td>
                      <td class="frmTabletxt" align="center">Web Redesign</td>
                      <td class="frmTabletxt" align="center">SEO</td>
                      <td class="frmTabletxt" align="center">SEM</td>
                      <td class="frmTabletxt" align="center">Copywriting</td>
                    </tr>
                </table>			</td>
          </tr>
          <tr>
            <td colspan="3" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td width="95%" valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td width="100%"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0s">
                  <tr>
                    <td width="1%" height="19" bgcolor="#FF6600">&nbsp;</td>
                    <td width="99%" class="summaryheader">QUICK SUMMARY</td>
                  </tr>
                </table></td>
              </tr>
              
              
              <tr>
                <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="topbannertb">
                  <tr>
                    <td width="1%"><img src="images/lefttop2.jpg" width="6" height="6" /></td>
                    <td width="98%" valign="top"><img src="images/topline.jpg" width="100%" height="6" /></td>
                    <td width="1%" align="right"><img src="images/righttop.jpg" width="6" height="6" /></td>
                  </tr>
                  <tr>
                    <td><img src="images/left.jpg" width="6" height="180" /></td>
                    <td valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="1" class="inputboxs">
                        <tr>
                          <td width="62%" class="frmHeader"><b>Section</b></td>
                          <td width="22%" class="frmHeader"><b>Records</b></td>
                          <td width="16%" class="frmHeader"><b>View | Add</b></td>
                        </tr>
                        <tr>
                          <td class="frmTabletxt">Active Clients</td>
                          <td class="frmTabletxt"><?
							$sql = "SELECT COUNT(ID) AS total FROM wbm_clients WHERE companyid=$strWbmCompanyid AND archived=0";
							$result = mysql_query($sql) or die(mysql_error());
							$row = mysql_fetch_array($result);
							$strTotal = $row['total'];
							echo $strTotal;
							mysql_free_result($result);
							?></td>
                          <td class="frmTabletxt"><a href="myclients_mgr.php?searchmode=none"><img src="images/edit.png" width="16" height="16" vspace="2" border="0"></a> | <a href="myclients_mgr.php?mode=new"><img src="images/add_new.png" width="16" height="16" vspace="2" border="0"></a></td>
                        </tr>
                        
                        <tr>
                          <td class="frmTabletxt">Registered Domains</td>
                          <td class="frmTabletxt"><?
							$sql = "SELECT COUNT(ID) AS total FROM wbm_domains WHERE companyid=$strWbmCompanyid";
							$result = mysql_query($sql) or die(mysql_error());
							$row = mysql_fetch_array($result);
							$strTotal = $row['total'];
							echo $strTotal;
							mysql_free_result($result);
							?></td>
                          <td class="frmTabletxt"><a href="domains_mgr.php?searchmode=none"><img src="images/edit.png" width="16" height="16" vspace="2" border="0"></a> | <a href="domains_mgr.php?mode=new"><img src="images/add_new.png" width="16" height="16" vspace="2" border="0"></a></td>
                        </tr>
                        <tr>
                          <td class="frmTabletxt">Hosting Accounts</td>
                          <td class="frmTabletxt"><?
							$sql = "SELECT COUNT(ID) AS total FROM wbm_hosting WHERE companyid=$strWbmCompanyid";
							$result = mysql_query($sql) or die(mysql_error());
							$row = mysql_fetch_array($result);
							$strTotal = $row['total'];
							echo $strTotal;
							mysql_free_result($result);
							?></td>
                          <td class="frmTabletxt"><a href="hosting_mgr.php?searchmode=none"><img src="images/edit.png" width="16" height="16" vspace="2" border="0"></a> | <a href="hosting_mgr.php?mode=new"><img src="images/add_new.png" width="16" height="16" vspace="2" border="0"></a></td>
                        </tr>
                        <tr>
                          <td class="frmTabletxt">Sales leads</td>
                          <td class="frmTabletxt"><?
							$sql = "SELECT COUNT(ID) AS total FROM wbm_salesleads WHERE companyid=$strWbmCompanyid";
							$result = mysql_query($sql) or die(mysql_error());
							$row = mysql_fetch_array($result);
							$strTotal = $row['total'];
							echo $strTotal;
							mysql_free_result($result);
							?></td>
                          <td class="frmTabletxt"><a href="salesleads_mgr.php?searchmode=none"><img src="images/edit.png" width="16" height="16" vspace="2" border="0"></a> | <a href="salesleads_mgr.php?mode=new"><img src="images/add_new.png" width="16" height="16" vspace="2" border="0"></a></td>
                        </tr>
                        <tr>
                          <td class="frmTabletxt">Email marketing Clients</td>
                          <td class="frmTabletxt"><?
							$sql = "SELECT COUNT(ID) AS total FROM wbm_emailmarketing WHERE companyid=$strWbmCompanyid";
							$result = mysql_query($sql) or die(mysql_error());
							$row = mysql_fetch_array($result);
							$strTotal = $row['total'];
							echo $strTotal;
							mysql_free_result($result);
							?></td>
                          <td class="frmTabletxt"><a href="emclients_mgr.php?searchmode=none"><img src="images/edit.png" width="16" height="16" vspace="2" border="0"></a> | <a href="emclients_mgr.php?mode=new"><img src="images/add_new.png" width="16" height="16" vspace="2" border="0"></a></td>
                        </tr>
                    </table></td>
                    <td align="right"><img src="images/right.jpg" width="6" height="180" /></td>
                  </tr>
                  <tr>
                    <td><img src="images/leftbottom.jpg" width="6" height="6" /></td>
                    <td valign="bottom"><img src="images/bottomline.jpg" width="100%" height="6" /></td>
                    <td align="right"><img src="images/rightbottom.jpg" width="6" height="6" /></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="2%"><img src="images/spacer.gif" width="15" height="15" /></td>
            <td width="33%" valign="top"><table width="100" border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td width="100%"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0s">
                    <tr>
                      <td width="3%" height="19" bgcolor="#FF6600">&nbsp;</td>
                      <td width="97%" class="summaryheader">INCOME MANAGEMENT</td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="1%"><img src="images/lefttop2.jpg" width="6" height="6" /></td>
                    <td width="98%" valign="top"><img src="images/topline.jpg" width="100%" height="6" /></td>
                    <td width="1%" align="right"><img src="images/righttop.jpg" width="6" height="6" /></td>
                  </tr>
                  <tr>
                    <td><img src="images/left.jpg" width="6" height="90" /></td>
                    <td valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                        
                        <tr>
                          <td width="90%" class="frmTabletxt"><a href="income_mgr.php?mode=invoice&step=stepone"><img src="images/addnewinvpay.png" width="219" height="39" border="0"/></a></td>
                          </tr>
                        <tr>
                          <td class="frmTabletxt"><a href="income_mgr.php?mode=other"><img src="images/addnewincome.png" width="219" height="39" border="0"/></a><a href="income_mgr.php?mode=invoice&step=stepone"></a></td>
                          </tr>
                    </table></td>
                    <td align="right"><img src="images/right.jpg" width="6" height="90" /></td>
                  </tr>
                  <tr>
                    <td><img src="images/leftbottom.jpg" width="6" height="6" /></td>
                    <td valign="bottom"><img src="images/bottomline.jpg" width="100%" height="6" /></td>
                    <td align="right"><img src="images/rightbottom.jpg" width="6" height="6" /></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0s">
                  <tr>
                    <td width="3%" height="19" bgcolor="#FF6600">&nbsp;</td>
                    <td width="97%" class="summaryheader">EXPENSE MANAGEMENT</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="1%"><img src="images/lefttop2.jpg" width="6" height="6" /></td>
                    <td width="98%" valign="top"><img src="images/topline.jpg" width="100%" height="6" /></td>
                    <td width="1%" align="right"><img src="images/righttop.jpg" width="6" height="6" /></td>
                  </tr>
                  <tr>
                    <td><img src="images/left.jpg" width="6" height="50" /></td>
                    <td valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="90%" class="frmTabletxt"><a href="expenses_mgr.php?mode=new"><img src="images/addnew.png" width="219" height="39" border="0"/></a></td>
                        </tr>
                        
                    </table></td>
                    <td align="right"><img src="images/right.jpg" width="6" height="50" /></td>
                  </tr>
                  <tr>
                    <td><img src="images/leftbottom.jpg" width="6" height="6" /></td>
                    <td valign="bottom"><img src="images/bottomline.jpg" width="100%" height="6" /></td>
                    <td align="right"><img src="images/rightbottom.jpg" width="6" height="6" /></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
                <!--###### END ACCESSING FUNCTIONS ######-->				
			</td>
		</tr>
		</table></td>
		</tr>
	  <tr>
	    <td valign="top"></td>
	    <td valign="top" style="background: transparent url(images/bottomline.jpg) repeat-x scroll 0% 0%;" height="6"></td>
	    </tr>
	  <tr>
	    <td valign="top" class="summaryheader" align="center">&nbsp;</td>
	    <td valign="top" class="summaryheader" align="center">&nbsp;</td>
	    </tr>
	  <tr>
	    <td valign="top" class="summaryheader" align="center">&nbsp;</td>
    <td valign="top" class="summaryheader" align="left">&copy; <? echo date("Y");?> Ebits Online Ltd.</td>
	    </tr>
		<tr>
		  <td>          
	    </tbody>
	  </table>		
	  </td>
	  <td width="1%" valign="top"><img src="images/spacer.gif" width="50" height="10" /></td>
	  </tr>
   </table>
</body>
</html>
<?PHP
}
else
{
	header('Location: insufficientpermission.php');
}
ob_end_flush();
?>