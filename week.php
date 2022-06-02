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
$page_title = "Week View";

$cur_main = "schedule";
$cur_sub = "week";
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
                <td width="47%" align="right" class="breadcrumbs"><strong>CURRENT VIEW:&nbsp;&nbsp;Week View&nbsp;&nbsp; &nbsp;&nbsp; </strong></td>
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
			<td width="60%" align="right" class="breadcrumbs"><strong>MORE VIEWS:&nbsp;&nbsp;<a href="day.php">Day View</a>&nbsp;|&nbsp;<a href="month.php">Month View</a>&nbsp;</strong></td>
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
				$prevYmd = date ( "Ymd", $prev );
				$prevyear = date ( "Y", $prev );
				
				//next and previous week timpestamps
				$next = mktime ( 3, 0, 0, $thismonth, $thisday + 7, $thisyear );
				$prev = mktime ( 3, 0, 0, $thismonth, $thisday - 7, $thisyear );
				
				
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
				
				//this stuff borrowed from webcalendar.  thanks!
				
				$wkstart = get_sunday_before ( $thisyear, $thismonth, $thisday );
				$wkend = $wkstart + ( 3600 * 24 * 6 );
				$startdate = date ( "Ymd", $wkstart );
				$enddate = date ( "Ymd", $wkend );
				
				for ( $i = 0; $i < 7; $i++ ) {
				$days[$i] = $wkstart + ( 24 * 3600 ) * $i;
				$weekdays[$i] = weekday_short_name ( ( $i + $WEEK_START ) % 7 );
				$header[$i] = $weekdays[$i]." ".month_short_name ( date ( "m", $days[$i] ) - 1 ) ." " . date ( "d", $days[$i] );
				}
				
				
				//print_r($days);
				//print_r($weekdays);
				//print_r($header);
				
				?>
				
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr class="post_row_0">
				<td class="pad"><a href="week.php?date=<? echo date("Ymd", $prev )?>">&lt;&lt;</td>
				<td colspan="6" class="pad"><div align="center">
				<span class="addheader">
				<?php echo $header[0]." - ".$header[6]; ?></span></div>
				</td>
				<td class="pad"><div align="right"><a href="week.php?date=<? echo date("Ymd", $next )?>">&gt;&gt;</div></td>
				</tr>
				
				<tr class="vtop">
				<?php
				
				for($k=0; $k <= 6; $k++){
				//generate the current ymd
				$curymd = date ( "Ymd", $days[$k] );
				
				//request events for this project on this day
				$query = "SELECT * FROM `wbm_projectcal` WHERE `cal_date` = '".$curymd."' AND `projectID` = '".$_SESSION['uzuriweb_wbm_projectvars']['ID']."' ORDER BY `cal_time` ASC;";
				//ride our pink elephant
				$result = dbase_query($query);
				
				//set this value here so below it starts counting at 0
				$allday = "";
				$allcount = 0;
				
				//we loop through the results and add a display time item to each, so we know where it goes in the list.
				//we also do some other formatting thigns as well
				for($i=1; $i <= mysql_num_rows($result); $i++){
				$row[$i] = mysql_fetch_array($result);
				//other formatting things
				if ($row[$i]['allday'] != "1"){
				
				//figure end time
				$dur_hours = intval($row[$i]['duration']/60);
				$dur_minutes = $row[$i]['duration'] % 60;
				
				$end_hours = substr($row[$i]['cal_time'],0,2) + $dur_hours;
				$end_minutes = substr($row[$i]['cal_time'],2,2) + $dur_minutes;
				
				if ($end_hours >= 12) {
				$append = " pm";
				} elseif (($end_hours >= 0) AND ($end_hours <= 11)) {
				$append = " am";
				}
				
				//we subtract if it is in the pm, but not noon
				if ($end_hours > 12) {
				$end_hours = $end_hours - 12;
				}
				
				$row[$i]['display_end'] = $end_hours.":".make2($end_minutes).$append;
				
				
				//figure begin time
				//puts it into "HH:MM" format
				$begin_hours = substr($row[$i]['cal_time'],0,2);
				$beigin_minutes = substr($row[$i]['cal_time'],2,4);
				
				if ($begin_hours >= 12) {
				$append = " pm";
				} elseif (($begin_hours >= 0) AND ($begin_hours <= 11)) {
				$append = " am";
				}
				
				//we subtract if it is in the pm, but not noon
				if ($begin_hours > 12) {
				$begin_hours = $begin_hours - 12;
				}
				
				$row[$i]['display_time'] = $begin_hours.":".make2($beigin_minutes).$append;
				
				//divides by 100, converts to integer.
				//example:  937 (9:37am) gets converted to 9.37 then the .37 gets dropped and we are left with just 9
				$tmp_eventtime = intval($row[$i]['cal_time']/100);
				//puts in the data
				$row[$i]['write_time'] == $tmp_eventtime;
				
				
				//before this loop started, we initialized a variable called $master_events
				//now what we are doing is adding any events to the master event list at the corresponding time.  they will always be in spot 1 or higher
				//this works out well for us a bit later on.
				
				$tmp_count = count($master_events[$tmp_eventtime]) + 1;
				$master_events[$tmp_eventtime][$tmp_count] =  $row[$i];
				} elseif ($row[$i]['allday'] != "0"){
				$alldayitems = TRUE;
				$allday[$allcount] = $row[$i];
				//count it up, we don't need to add 1 because the count will always start with 0, giving us the next slot to enter into
				$allcount = count($allday);
				}
				}
				//temporary storage of where we start and end the day
				$start_hour = 8;
				$end_hour = 17;  //5:00 in 24 hour format
				$cur_hour = "am";
				?>	
				<td>
				<?php
				if ($k == 0){
				?>
				<table width="100%" cellpadding="0" cellspacing="1" border="0">
				<tr class="post_row_0">
				<td colspan="1" class="pad">&nbsp;</td>
				</tr>
				<?
				$i = $start_hour;
				while ($i <= $end_hour){
				if (($i % 2) == 0){
				$rowclass = "post_row_1";
				} else{
				$rowclass = "post_row_2";
				}
				
				echo "<tr class=\"".$rowclass."\">";
				echo "\n<td width=\"100%\" class=\"vtop pad\">\n\n<span class=\"addheader\">";
				//figure out the current time
				if ($i > 12){
				$write_hour = $i - 12;
				 if ($write_hour==12){
				 $cur_hour = "am";
				 }
				 else{
				 $cur_hour = "pm";
				 }
				} 
				elseif ($i <= 11){
				$write_hour = $i;
				$cur_hour = "am";
				} 
				elseif ($i == 12){
				$write_hour = $i;
				$cur_hour = "pm";
				} 
				echo $write_hour.":00 ".$cur_hour."</span>";
				echo "</td>\n<td width=\"1\" class=\"white_row\"><img src=\"assets/spacer.gif\" alt=\"spacer image\" width=\"1px\" height=\"40px\"/></td></tr>";
				$i++;
				}
				?>
				</table></td>
				<td>
				<?php
				}
				?>
				<table width="100%" cellpadding="0" cellspacing="1" border="0">
				<tr  class="post_row_0">
				<td colspan="1" class="pad"><span class="addheader"><?php echo $header[$k];?></span></td>
				</tr>
				<?php
				//## BEGIN NORMAL TABLE HOURS
				$i = $start_hour;
				while ($i <= $end_hour){
				if (($i % 2) == 0){
				$rowclass = "post_row_1";
				} else{
				$rowclass = "post_row_2";
				}
				
				echo "<tr class=\"".$rowclass."\">";
				echo "\n<td class=\"vtop pad\">\n\n";
				//begin writing event info here!
				
				if ($master_events[$i] != ""){
				for($j=1; $j <= count($master_events[$i]); $j++){
				echo "<a href=\"view_event.php?eventID=".$master_events[$i][$j]['eventID']."\">";
				echo "&raquo; ".$master_events[$i][$j]['display_time']." ".$master_events[$i][$j]['name']."</a><br />";
				}
				}
				//end writing event info
				echo " </td>\n<td width=\"1\" class=\"white_row\"><img src=\"assets/spacer.gif\" alt=\"spacer image\" width=\"1px\" height=\"40px\"/></td></tr>\n\n";
				$i++;
				}
				
				//## END NORMAL TABLE HOURS
				//looks through all the events and sees if any are out of the standard hours we set up above a little bit
				for($i=0; $i <= $start_hour; $i++){
				if ($master_events[$i] != ""){
				$out_range = TRUE;
				}
				}
				for($i=($end_hour +1); $i <= 24; $i++){
				if ($master_events[$i] != ""){
				$out_range = TRUE;
				}
				}
				
				if ($out_range){
				?>
				<tr class="post_row_2">
				<td class="pad vtop">
				<span class="addheader">Other</span><br /><br />
				<?php
				//begining of day
				for($i=0; $i <= $start_hour; $i++){
				if ($master_events[$i] != ""){
				for($j=1; $j <= count($master_events[$i]); $j++){
				echo "<a href=\"view_event.php?eventID=".$master_events[$i][$j]['eventID']."\">";
				echo "&raquo; ".$master_events[$i][$j]['display_time']." ".$master_events[$i][$j]['name']."</a><br /><br />";
				}
				}
				}
				//end of day
				for($i=($end_hour +1); $i <= 24; $i++){
				if ($master_events[$i] != ""){
				for($j=1; $j <= count($master_events[$i]); $j++){
				echo "<a href=\"view_event.php?eventID=".$master_events[$i][$j]['eventID']."\">";
				echo "&raquo; ".$master_events[$i][$j]['display_time']." ".$master_events[$i][$j]['name']."</a><br /><br />";
				}
				}
				}
				?>
				</td>
				<?php
				}
				if ($allday){
				?>
				<tr class="post_row_2">
				<td class="pad vtop">
				<span class="addheader">All Day</span><br /><br />
				<?php
				//write out allday items
				for($i=0; $i < count($allday); $i++){
				echo "<a href=\"view_event.php?eventID=".$allday[$i]['eventID']."\">";
				echo $allday[$i]['name']."</a><br />";
				}
				?>
				</td>
				<?php
				}
				?>
				</td>
				<td width="1" class="white_row"><img src="assets/spacer.gif" alt="spacer image" width="1px" height="20px"/></td>
				</tr>
				</table>
				</td>
				<?
				//clear the master events at the end of the day
				unset($master_events);
				unset($out_range);
				$weekday++;
				}
				echo "</tr></table>";
				?>
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