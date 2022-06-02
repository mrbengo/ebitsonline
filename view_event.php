<?php 
ob_start();
error_reporting (E_ALL ^ E_NOTICE);

session_start();
$_SESSION['uzuriweb_wbm_authenticate_id'];
$_SESSION['uzuriweb_wbm_modes'];
$_SESSION['uzuriweb_wbm_projectid'];

//Include common files
include('includes/wbm_common.php');
include('functions/myclients_fns.php');
//page variables
$page_title = "View Event";

$cur_main = "schedule";
$cur_sub = "event";
include 'templates/includes.php';


//Check if user is logged in
$uzuriweb_log_val=$_SESSION['uzuriweb_wbm_authenticate_id'];
if ($uzuriweb_log_val <> "")
{
	
	$uzuriwebpgname="week";
	$strPath = "";
	$strTitle = 'Manage Project Schedule';
	
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
              <td width="63%" valign="top" class="breadcrumbs"><strong>YOU ARE HERE:</strong> <a href="dashboard.php">Dashboard</a>&nbsp;->&nbsp;Manage Active Clients</td>
              <td width="37%" align="right" class="breadcrumbs"><strong>COMPANY:</strong>  <?php echo WEB_SITE_NAME;?> &nbsp;&nbsp;<strong>LOGGED IN AS:</strong>  <?php echo $_SESSION['uzuriweb_wbm_username'];?> &nbsp;&nbsp;</td>
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
        <?php include("sidebars/project-management.php")?>
		<!--###### END TOPBAR ######-->		
		</td>
	    <td width="96%" valign="top" bgcolor="#F3F5F8">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2" style="background: transparent url(images/topline.jpg) repeat-x scroll 0% 0%;" height="6"></td>
          </tr>
		<tr>
            <td width="5"><img alt="" src="images/spacer.gif" width="5" height="5"></td>
            <td valign="top" class="titletx">
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="titletx"><?PHP echo $strTitle;?></td>
                <td class="titletx"></td>
              </tr>
              <tr>
                <td width="53%" class="breadcrumbs"><strong>PROJECT NAME:&nbsp;&nbsp;<?PHP echo $_SESSION['uzuriweb_wbm_projectvars']['projectname'];?></strong></td>
                <td width="47%" align="right" class="breadcrumbs"><strong>VIEW EVENT&nbsp;&nbsp;</strong></td>
              </tr>
            </table></td>
	    </tr>
          <tr>
            <td width="5"><img alt="" src="images/spacer.gif" width="5" height="5"></td>
            <td valign="top">
			<table width="98%" border="0" align="center" cellspacing="1" cellpadding="1" class="inputtableboxs">
			<tr>
			<td width="40%"><a href="add_event.php">
			<img src="images/addnew.png" width="219" height="39" border="0"/></a></td>
			<td width="60%" align="right" class="breadcrumbs"><strong>PROJECT SCHEDULE:&nbsp;&nbsp;<a href="day.php">Day View</a>&nbsp;|&nbsp;<a href="month.php">Month View</a>&nbsp;</strong></td>
			</tr>
			</table>			
			<hr width="98%" align="center">
			<table width="98%" align="center" border="0" bgcolor="#F3F5F8" cellpadding="0" cellspacing="0">
			<tbody>
			<tr>
			<td style="background-image: url(images/lefttop.jpg);" width="6" height="6"></td>
			<td style="background: transparent url(images/topline.jpg) repeat-x scroll 0% 0%;" height="6"></td>
			<td style="background-image: url(images/righttop.jpg);" width="6" height="6"></td>
			</tr>
			<tr>
			<td style="background: transparent url(images/left.jpg) repeat-y scroll 0% 0%;" height="6"></td>
			<td valign="middle" align="center" bgcolor="#ffffff">	
			    <!--###### ACCESS FUNCTIONS ######-->
				<?php
				//for echoing a message if an event occured
				if ($_GET['procmsg'] == "modsuc") {
				echo "<p class=\"current\">Event modified successfully.</p>\n";
				} elseif ($_GET['procmsg'] == "addsuc") {
				echo "<p class=\"current\">Event added successfully.</p>\n";
				}
				//doublecheck to make sure that the eventID is for the project thye're currently on
				$query = "SELECT * FROM `wbm_projectcal` WHERE `eventID` = '".$_GET['eventID']."' LIMIT 1;";
				$result = dbase_query($query);
				$row = mysql_fetch_array($result);
				if ($row['projectID'] != $_SESSION['uzuriweb_wbm_projectvars']['ID']){
				echo "<p>You are trying to access an event for which you are not authorized.</p>";
				} 
				else 
				{
				$details = get_event_info($_GET['eventID']);
				?>
				<table cellspacing="1" cellpadding="1" border="0" width="500px">
				<tr class="post_row_0">
				<td colspan="2"  class="pad">
				<span class="addheader"><?php echo $details['name']; ?></span>
				</td>
				</tr>
				<tr class="post_row_1">
				<td class="pad vtop">Description</td>
				<td class="pad">
				<?php echo $details['description']; ?>
				</td>
				</tr>
				<tr class="post_row_1">
				<td class="pad">Date</td>
				<td class="pad">
				<?php
				echo $details['today']['weekday'].", ".$details['today']['month']." ".$details['today']['mday'].", ".$details['today']['year'];
				?>
				</td>
				</tr>
				<?php if ($details['allday'] != "1"){ ?>
				<tr class="post_row_1">
				<td class="pad">Start Time</td>
				<td class="pad">
				<?php echo $details['begin_time']; ?>
				</td>
				</tr>
				<tr class="post_row_1">
				<td class="pad">End Time</td>
				<td class="pad">
				<?php echo $details['end_time']; ?>
				</td>
				</tr>
				<?php } else {?>
				<tr class="post_row_1">
				<td class="pad">Time</td>
				<td class="pad">
				All Day
				</td>
				</tr>
				<?php } ?>
				<tr class="post_row_1">
				<td class="pad">Responsibility</td>
				<td class="pad">
				<?php 
				switch ($details['respon']) {
				case "firm":
				echo "Design Firm";
				break;
				case "client":
				echo "Client";
				break;
				case "joint":
				echo "Joint Effort";
				break;
				case "outside";
				echo "Outside Party";
				break;
				}
				?>
				</td>
				</tr>	
				<tr class="post_row_2">
				<td colspan="2"><div align="right">
				<?php if (($_SESSION['uzuriweb_wbm_acclvl'] == "user") OR ($_SESSION['uzuriweb_wbm_acclvl'] == "admin")){ ?>
				<a href="mod_event.php?eventID=<?php echo $details['eventID']; ?>">[modify]</a>
				<a href="del_event.php?eventID=<?php echo $details['eventID']; ?>" onclick="return confirm_submit('Are you sure you want to delete this event?\nThis is not undoable.');">[delete]</a>
				<?php } ?>
				&nbsp;</div>
				</td>
				</tr>
				</table>
				<?php
				}
				?>
	        	<!--###### END ACCESSING FUNCTIONS ######-->				
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