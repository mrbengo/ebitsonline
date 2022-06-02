<?php 
ob_start();
error_reporting (E_ALL ^ E_NOTICE);

session_start();
$_SESSION['uzuriweb_wbm_authenticate_id'];
$_SESSION['uzuriweb_wbm_modes'];

//Include common files
include('includes/wbm_common.php');
//page variables
$page_title = "Modify Event";
$cur_main = "schedule";
$cur_sub = "modevent";
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
                <td width="47%" align="right" class="breadcrumbs"><strong>MODIFY EVENT&nbsp;&nbsp;</strong></td>
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
				if ($_GET['procmsg'] == 1) {
				echo "<p class=\"current\">Event added successfully.</p>\n";
				} elseif ($_GET['procmsg'] == "delsuc"){
				echo "<p class=\"current\">Event deleted successfully.</p>\n";
				}
				$event = get_event_info($_GET['eventID']);
				
				//set flag for disabled time events
				if ($event['allday'] == "1"){
				$disabled = TRUE;
				}
				?>
				<form method="POST" action="event_process.php" name="calevent" id="mod_event" onsubmit="return future_date();">
				<input type="hidden" name="proc" value="mod_event" />
				<input type="hidden" name="modID" value="<?php echo $event['eventID']; ?>">
				<table cellspacing="1" cellpadding="1" border="0">
				<tr class="post_row_0">
				<td colspan="2"  class="pad">
				<span class="addheader">Add Calendar Event</span>
				</td>
				</tr>
				<tr class="post_row_1">
				<td class="pad">Name</td>
				<td class="pad"><input type="text" name="name" size="50" maxlength="255" class="txtinput" value="<?php echo $event['name']; ?>"/></td>
				</tr>
				<tr class="post_row_1">
				<td class="pad vtop">Description</td>
				<td class="pad">
				<textarea cols="40" rows="5" name="description" class="txtinput"><?php echo $event['description']; ?></textarea>
				</td>
				</tr>
				<tr class="post_row_1">
				<td class="pad">Date</td>
				<td class="pad">
				<select name="month" class="txtinput" >
				<?php echo opt_month($event['today']['mon']);  ?>
				</select>
				<select name="day" class="txtinput" >
				<?php echo opt_day($event['today']['mday']);  ?>
				</select>
				<select name="year" class="txtinput" >
				<?php echo opt_year($event['today']['year']);  ?>
				</select>
				</tr>
				</td>
				<tr class="post_row_1">
				<td class="pad">Time</td>
				<td class="pad vcenter">
				<input type="text" name="time_hour" size="2" maxlength="2" class="txtinput" value="<?php echo $event['begin_h']; ?>" <?php if ($disabled) {echo "disabled";} ?>/>:
				<input type="text" name="time_minute" size="2" maxlength="2" class="txtinput" value="<?php echo $event['begin_m']; ?>" <? if ($disabled) {echo "disabled";} ?>/>
				<select name="half" class="txtinput" <? if ($disabled) {echo "disabled";} ?>>
				<option value="am" <?php if ($event['begin_half'] == "am") { echo "selected";} ?>>am</option>
				<option value="pm" <?php if ($event['begin_half'] == "pm") { echo "selected";} ?>>pm</option>
				</select><input type="checkbox" name="allday" value="1" class="radio" <?php 
				if ($event['allday'] == "1"){
				echo "checked";
				}
				?> onclick="disabletime();">All day event
				</td>
				</tr>
				<tr class="post_row_1">
				<td class="pad">Duration</td>
				<td class="pad">
				<input type="text" name="dur_time" size="4" maxlength="3" class="txtinput" value="<?php echo $event['duration']; ?>" <? if ($disabled) {echo "disabled";} ?>/> minutes
				</td>
				</tr>	
				<tr class="post_row_1">
				<td class="pad">Responsibility</td>
				<td class="pad">
				<select name="respon" class="txtinput">
				<option value="firm" <?php if ($event['respon'] == "firm"){ echo "selected"; } ?>>Design Firm</option>
				<option value="client" <?php if ($event['respon'] == "client"){ echo "selected"; } ?>>Client</option>
				<option value="joint" <?php if ($event['respon'] == "joint"){ echo "selected"; } ?>>Joint</option>
				<option value="outside" <?php if ($event['respon'] == "outside"){ echo "selected"; } ?>>Outside Party</option>
				</select>
				</td>
				</tr>	
				<tr class="post_row_1">
				<td>&nbsp;</td>
				<td class="pad">
				<input type="submit" name="submit" value="Submit" class="button"/>
				<input type="reset" name="reset" value="Reset" class="button"/>
				</td>
				</tr>
				</table>
				</form>	
				<br />
				<br />
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