<?php
if(preg_match("/project-management.php/i",$_SERVER['PHP_SELF'])) {
	echo "You are not allowed to access this page directly";
    die();
}

/**
 * Sidebar section for UzuriWeb
**/
?>
<table width="98%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td width="93%">
	  <table width="100%" border="0" bgcolor="#ffffff" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td style="background: transparent url(images/topline.jpg) repeat-x scroll 0% 0%;" height="6"></td>
          </tr>
          <tr>
            <td height="6" align="center" valign="middle"><table width="200"  border="0" align="center" cellpadding="0" cellspacing="0">
			  
			  <tr>
			    <td width="5%" rowspan="8"><img src="images/spacer.gif" width="10" height="10" /></td>
			    <td width="90%"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="10%" valign="top"><img src="images/property.png" alt="" name="config" width="28" height="29" border="0" style="vertical-align: middle;" /></td>
                      <td width="90%" class="sideheadings">Settings Manager</td>
                    </tr>
                </table></td>
                <td width="5%" rowspan="41"><img src="images/spacer.gif" width="10" height="10"></td>
			  </tr>
              <tr>
                <td>
			      <table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
					<td valign="top" style="background-color:#ffffff; padding-top:5px;"><a href="reports_mgr.php?rpt=sales_leads" class="sidelinks"><b>S</b>ales Leads Report</a></td>
					</tr>
					<tr>
					<td valign="top" style="background-color:#ffffff; padding-top:5px;"><a href="reports_mgr.php?rpt=hosting_acc" class="sidelinks"><b>H</b>osting Accounts Report</a></td>
					</tr>
					<tr>
					<td valign="top" style="background-color:#ffffff; padding-top:5px;"><a href="reports_mgr.php?rpt=email_marketing" class="sidelinks"><b>E</b>mail Marketing Report</a></td>
					</tr>
					<tr>
					<td valign="top" style="background-color:#ffffff; padding-top:5px;"><a href="reports_mgr.php?rpt=projects" class="sidelinks"><b>P</b>ojects Report</a></td>
					</tr>
					<tr>
					<td valign="top" style="background-color:#ffffff; padding-top:5px;"><a href="reports_mgr.php?rpt=income" class="sidelinks"><b>I</b>ncome (Money In) Report</a></td>
					</tr>
					<tr>
					<td valign="top" style="background-color:#ffffff; padding-top:5px;"><a href="reports_mgr.php?rpt=expenses" class="sidelinks"><b>E</b>xpenses (Money Out) Report</a></td>
					</tr>
					<tr>
					<td valign="top" style="background-color:#ffffff; padding-top:5px;"><a href="reports_mgr.php?rpt=invoices" class="sidelinks"><b>I</b>nvoices Report</a></td>
					</tr>
					<tr>
					<td valign="top" style="background-color:#ffffff; padding-top:5px;"><a href="reports_mgr.php?rpt=cheques" class="sidelinks"><b>I</b>ssued Cheques Report</a></td>
					</tr>
                </table>				
				</td>
              </tr>
              
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table>
            <!-- Button -->
			</td>
          </tr>
          <tr>
            <td style="background: transparent url(images/bottomline.jpg) repeat-x scroll 0% 0%;" height="6"></td>
          </tr>
        </tbody>
      </table>    
	  </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
