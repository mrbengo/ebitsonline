<?php 
ob_start();
error_reporting (E_ALL ^ E_NOTICE);

session_start();
$_SESSION['uzuriweb_wbm_authenticate_id'];
$_SESSION['uzuriweb_wbm_modes'];

//Include common files
include('includes/wbm_common.php');
include('functions/myclients_fns.php');
//page variables
$page_title = "Month View";

$cur_main = "schedule";
$cur_sub = "month";
include 'templates/includes.php';


//Check if user is logged in
$uzuriweb_log_val=$_SESSION['uzuriweb_wbm_authenticate_id'];
if ($uzuriweb_log_val <> "")
{
	
	$uzuriwebpgname="month";
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
                <td width="47%" align="right" class="breadcrumbs"><strong>CURRENT VIEW:&nbsp;&nbsp;Month View&nbsp;&nbsp; &nbsp;&nbsp; </strong></td>
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
			<td width="60%" align="right" class="breadcrumbs"><strong>MORE VIEWS:&nbsp;&nbsp;<a href="day.php">Day View</a>&nbsp;</strong></td>
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
				//does stuff with querystring date -- if it is empty, it gets formated into the current date.
				//borrowing some code from webcalendar.sourceforge.net here.  Yay GPL'd code!
				if ( ! empty ( $_GET['date'] ) ) {
				$thisyear = substr (  $_GET['date'], 0, 4 );
				$thismonth = substr (  $_GET['date'], 4, 2 );
				$thisday = substr (  $_GET['date'], 6, 2 );
				} else {
				if ( empty ( $month ) || $month == 0 )
				$thismonth = date("m");
				else
				$thismonth = $month;
				if ( empty ( $year ) || $year == 0 )
				$thisyear = date("Y");
				else
				$thisyear = $year;
				if ( empty ( $day ) || $day == 0 )
				$thisday = date("d");
				else
				$thisday = $day;
				}
				
				//if for some odd reason we ever get a request for feburary 29 or greater, we set it back down to the 1st of february!
				if ($thismonth == 2){
				if ($thisday >= 29){
				$thisday = "01";
				}
				}
				//the current ??
				$wday = strftime ( "%w", mktime ( 0, 0, 0, $thismonth, $thisday, $thisyear ) );
				//the current full timestmap
				$now = mktime ( 0, 0, 0, $thismonth, $thisday, $thisyear );
				//the current Ymd timestamp
				$nowYmd = date ( "Ymd", $now );
				//the next day timestamp
				$next = mktime ( 0, 0, 0, $thismonth, $thisday + 1, $thisyear );
				$nextYmd = date ( "Ymd", $next );
				$nextyear = date ( "Y", $next );
				if ($thismonth != 12){
				$nextmonth = $thismonth + 1;
				$nextmonth = make2($nextmonth);
				} elseif ($thismonth == 12){
				$nextmonth = "01";
				}
				$nextday = date ( "d", $next );
				//Ymd for previous month this day
				$month_ago = date ( "Ymd", mktime ( 0, 0, 0, $thismonth - 1, $thisday, $thisyear ) );
				//previous day full timestamp
				$prev = mktime ( 0, 0, 0, $thismonth, $thisday - 1, $thisyear );
				$prevYmd = date ( "Ymd", $prev );
				$prevyear = date ( "Y", $prev );
				
				if ($thismonth != "01"){
				$prevmonth = $thismonth - 1;
				$prevmonth = make2($prevmonth);
				} elseif ($thismonth = 01){
				$prevmonth == "12";
				}
				
				//figgers out the previous & next month YMD format
				if ($thismonth != 12){
				$nextmonthYmd = $thisyear.make2($nextmonth).make2($thisday);
				} else {
				$nextmonthYmd = ($thisyear+1)."01".$thisday;
				}
				
				if ($thismonth != "01"){
				$prevmonthYmd = $thisyear.make2($prevmonth).make2($thisday);
				} else {
				$prevmonthYmd = ($thisyear-1)."12".$thisday;
				}
				
				$prevday = date ( "d", $prev );
				//Ymd for one month ahead, same day
				$month_ahead = date ( "Ymd", mktime ( 0, 0, 0, $thismonth + 1, $thisday, $thisyear ) );
				//date information for today!
				$today = getdate(mktime ( 0, 0, 0, $thismonth, $thisday, $thisyear )); 
				$events = get_month_events(make2($thismonth),$thisyear);
				?>
				<table width="95%" cellpadding="0" cellspacing="1" border="0">
				<tr class="post_row_0">
				<td class="post_row_1 pad"><a href="month.php?date=<?php echo $prevmonthYmd; ?>" class="addheader">&lt;&lt;</a></td>
				<td colspan="5" class="pad"><div align="center"><span class="addheader"><?php echo $today['month']." ".$today['year']; ?></span>
				<span class="addheader displayhide"><?php echo " - ".$_SESSION['uzuriweb_wbm_projectvars']['projectname']; ?></span></div></td>
				<td class="post_row_1 pad"><div align="right"><a href="month.php?date=<?php echo $nextmonthYmd; ?>" class="addheader">&gt;&gt;</a></div></td>
				</tr>
				<tr>
				<td width="14%" >Sun</td>
				<td width="14%">Mon</td>
				<td width="14%">Tue</td>
				<td width="14%">Wed</td>
				<td width="14%">Thu</td>
				<td width="14%">Fri</td>
				<td width="14%">Sat</td>
				</tr>
				
				<?php
				$side_month = get_month_attrib($thismonth, $thisyear);
				$row_count = 0;
				for($i=0; $i < ($side_month['num_rows']*7); $i++){
				//write out begin of row if it is start of row
				if (($i % 7) == 0){
				echo "<tr>\n";
				$rowclass = "post_row_1";
				} else {
				$rowclass = "post_row_2";
				}
				if (($i % 7) == 6){
				$rowclass = "post_row_1";
				}
				
				//we do the border different if it is not part of the standard days of the month.
				if (($i >= $side_month['start_day']) AND ($i < ($side_month['num_days']+$side_month['start_day']))) {
				echo "\t<td class=\"$rowclass pad printborder vtop\" style=\"height:100px;\">\n\t\t";
				} else	{
				echo "\t<td class=\"pad vtop\" style=\"height:100px;\">\n\t\t";
				}
				
				//if $i is greater than or equal to the start day, we write out the day, otherwise just a non-breaking space
				if ($i >= $side_month['start_day']){
				if ($i < $side_month['num_days']+$side_month['start_day']){
				//
				$write_date = ($i - $side_month['start_day']+1); // we do it minus the start day column, because otherwise it would count too high
				if (strlen($write_date) == 1){
				$url_day = "0".$write_date;
				} else {
				$url_day = $write_date;
				}
				//echo "<table width=\"100%\" height=\"100px\" cellpadding=\"0\" cellspacing=\"0\">";
				//echo "<tr class=\"".$rowclass."\"><td class=\"pad printborder vtop\">";
				echo "<a href=\"day.php?date=".$thisyear.make2($thismonth).$url_day."\" class=\"addheader\">";
				echo $write_date."</a><br />";
				//do event info here!
				if ( isset($events[$write_date]) ) {
				for ($j=1; $j <= count($events[$write_date]); $j++){
				
				//if it is an allday event we don't write the time
				if ($events[$write_date][$j]['allday'] == "1"){
				echo "<a href=\"view_event.php?eventID=".$events[$write_date][$j]['eventID']."\" class=\"allday\">";
				echo "&raquo; ".$events[$write_date][$j]['name']."</a><br />\n";
				} else{
				echo "<a href=\"view_event.php?eventID=".$events[$write_date][$j]['eventID']."\" >";
				echo "&raquo; ".$events[$write_date][$j]['begin_time']." ".$events[$write_date][$j]['name']."</a><br />\n";
				}
				
				}
				}
				//end event info!
				
				//echo "</td></tr>";
				//echo "</table>";
				}
				} else {
				echo "&nbsp;";
				}
				$row_count++;
				echo "\t</td>\n";
				//write out end of row if end of row
				if ($row_count == 7){
				echo "</tr>\n";
				$row_count = 0;
				}
				
				}
				?>		
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