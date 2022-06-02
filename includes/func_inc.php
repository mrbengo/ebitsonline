<?php
//FUNCTIONS FOR DESIGNWORKS PROJECTSITE


//universal database function
function dbase_query($query){
	global $dw_host, $dw_user, $dw_pass, $dw_dbase;
	
	$db  = mysql_pconnect($dw_host, $dw_user, $dw_pass);
	if (!$db){  //sees if there is an error
		echo "Error: Could not connect to database.<br /><br /><a href=\"javascript:history.back()\">Go Back</a><br /><br />\n\n";
		echo "QUERY: ".$query;
		return;
	}
	
	mysql_select_db($dw_dbase);	
	
	$result = mysql_query($query);
	
	if (!$result) {  //sees if there is an error
	 	echo "Error putting data in database.<br /><br /><a href=\"javascript:history.back()\">Go Back</a><br /><br />\n\n";
		echo "QUERY: ".$query;		
	} else { //no error, we set return the result
		return($result);
	}
}

/*
*This function will parse through any chunk of text and search for our custom tags
*in this format:" <file 3> "
*and convert them to html for the post, it will either do this in the form of a image tag, or
*and anchor tag with some added text.
*THIS IS WHAT I AM DOING, in order
*search through the text for any string that has a the file tag in it
*search through each of those items returned in the array and create a paralell array with the number in that array
*create the nessecary html needed to be put into each of those positions
*loop again through the text and replace each instance of the file tag with the correct HTML
*/

function parse_for_uploaded($text,$projectID,$uploadpath){
	//preg_match_all("(<file\s+[0-9]{1,}>)",$text, $bigstrings);
	//preg_match_all("(<file=+[0-9]{1,}>)",$text, $bigstrings);
	preg_match_all("(<file=+[0-9]{1,}\sdescription=+.*?>)",$text, $bigstrings);
	
	//print_r($bigstrings[0]);
	
	$count = count($bigstrings[0]);
	//loops through the results and gets the numbers our and puts them in our paralell array
	for ($i = 0; $i <= $count; $i++){  //we start with 1 not 0, because the 0 place in bigstrings is the full $text string
		//gotta make sure that the <file > thing actually has a number in it
		if ( ereg("[0-9]{1,}",$bigstrings[0][$i], $reg) ){
			$tinystrings[$i] = $reg[0];
			unset($reg);
		}
	}
	//we should now have an array with the numbers and an array with the whole <file *> tag
	for ($i = 0; $i < $count; $i++){
		$query = "SELECT * FROM `files` WHERE fileID = ".$tinystrings[$i]." AND projectID = ".$projectID." ";
		$row = mysql_fetch_array(dbase_query($query));
		$url = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."//files/".$_SESSION['uzuriweb_wbm_projectvars']['projectname']."/";  //figures out the url of the file to link
		
		if (($row["ftype"] == "png") || ($row["ftype"] == "jpg") || ($row["ftype"] == "gif") ){
			$replace[$i] = "<img src=\"".$url.$row["fname"]."\" alt=\"".$row["fdesc"]."\" />\n\n";
		} else {
			$replace[$i] = "<a href=\"".$url.$row["fname"]."\" ><img src=\"assets/icons/".$row["ftype"].".gif\" border=\"0\" /> ".$row["fname"]."</a>\n\n";
		}	
	}
	//now we gotta loop back through the text and replace each of those chunks with the new stuff
	for($i = 0; $i <= $count; $i++){
		$text = str_replace($bigstrings[0][$i],$replace[$i],$text);
	}
	return($text);
}


/*
* Returns a selected index of the projects database using the $arg argument.
* Acceptable arguments are projectID, projtname and projtitle.
*/

function get_all_projects($arg){
	$query = "SELECT * FROM `wbm_webprojects` ORDER BY `projectname`";
	$result = dbase_query($query);
	$num_results = mysql_num_rows($result);
	$proj_arr = array();
	for ($i=0; $i < $num_results; $i++){
		$temp = mysql_fetch_array($result);
		$proj_arr[$i] = $temp[$arg];
	}
	return($proj_arr);
}


/*
* Returns a selected index of the projects database using the $arg argument, for a userID.
* Acceptable arguments are projectID, projtname and projtitle.
*If the user is an admin, returns "admin"
*/

function get_user_projects($userID, $arg){
	
	//new code
	//first we see if they are an admin
	//$query = "SELECT * FROM `users` where `userID` = '".$userID."' LIMIT 1";
	//$result = dbase_query($query);
	//$row = mysql_fetch_array($result);
	//if ($row['acclvl'] == "admin"){
		//return("admin");
	//}
	//they're not admin, so we keep going
	$query = "SELECT * FROM `assigned` where `userID` = '".$userID."';";
	$result = dbase_query($query);
	for ($i=0; $i < mysql_num_rows($result); $i++){
		$row = mysql_fetch_array($result);
		$query = $query = "SELECT * from `wbm_webprojects` where `ID` = '".$row['projectID']."' LIMIT 1";
		$result2 = dbase_query($query);
		$temp = mysql_fetch_array($result2);
		$proj_arr[$i] = $temp[$arg];
	}
	
	return($proj_arr);
	
}

/*
*	Writes out <option> tags for the projects specified by $userID
*	If $userID == "all" then it writes out option tags for all projects
*	returns the HTML, does not echo
*/

function write_proj_option($userID){
	if ($userID == "all"){
		$query = "SELECT * FROM `wbm_webprojects` ORDER BY `projectname` ASC";
		$result = dbase_query($query);
		$num_results = mysql_num_rows($result);
		for ($i=0; $i < $num_results; $i++)
		{
			$row = mysql_fetch_array($result);
			$r_val = $r_val."<option value=\"".$row['ID']."\">".$row['projectname']."</option>\n";
		}
	} else {
		//first we must get a list of the projects that user is on
		$proj_ID = get_user_projects($userID, "ID"); //gets the projectIDs
		$proj_title = get_user_projects($userID, "projectname");	//gets the project titles
		
		for ($i=0; $i < count($proj_ID); $i++){
			$r_val = $r_val."<option value=\"".$proj_ID[$i]."\">".$proj_title[$i]."</option>\n";
		}
		//if there aren't any assigned projects for that user
		if ($proj_ID == ""){
			$r_val =  write_proj_option("all");
		}
	}
	return($r_val);
}

/*
*gets the total number of projects
*/
function get_num_projects(){
	$query = "SELECT * FROM `wbm_webprojects`";
	$result = dbase_query($query);
	$num = mysql_num_rows($result);
	return($num);
}

/*
*gets the total number of users
*/
function get_num_users(){
	$result = dbase_query("SELECT * FROM `users` LIMIT 0, 30");
	$num_rows = mysql_num_rows($result);
	return($num_rows);
}


/*
*	sets the user to assigned projects
*	$userID = userid
*	$projectID = projectID, ignored if admin
*	$acclvl  = access level (client or user)
*/
function set_assigned_proj($userID,$projectID, $acclvl){
	//first we remove the user/project combo if they exist
	$query = "DELETE FROM `assigned` WHERE `userID` = '".$userID."' AND `projectID` = '".$projectID."';";
	dbase_query($query);
	$query = "INSERT INTO `assigned` (`userID`, `projectID`, `acclvl`) VALUES ('".$userID."', '".$projectID."', '".$acclvl."');"; 
	dbase_query($query);
}


/*
*	clears all assigned projects
*	if userID is not 0, clears for the user
*	if projectID is not 0, clears for the project
*	should never be both.  will cause massive screw up
*/
function clear_assigned_proj($userID,$projectID){
	if ($projectID == 0){
		//do user stuff here.
		$query = "DELETE FROM `assigned` WHERE `userID` = '".$userID."';";
		dbase_query($query);
	} elseif ($userID == 0){
		//do project stuff here
		$query = "DELETE FROM `assigned` WHERE `projectID` = '".$projectID."';";
		dbase_query($query);
	}
}


/*
* processes the upload of a file
* $file should be the $_FILES[***] array
* $dest should be the directory to save to, unix style, WITH a trailing slash
* $name should be the new name of the file.  If not specified, then the uploaded name will be used.  
* returns true on success
* returns an error message and forces php to exit on fail
*/

function process_upload($file,$dest,$name){
	if (isset($file)){  //gotta make sure there is a file
		if ($file['size'] > 0) {  //gotta make sure we have somethng bigger than 0
			$source = $file['tmp_name'];  
			if (isset($name)) {  //if the name argument is set
				$dest = $dest.$name;	//then we make the destination the new file name
			} else {
				$dest = $dest.$file['name'];  //otherwise we use the given name
			}
			if ( move_uploaded_file( $source, $dest ) ) { //actually move it
				return (true);  //return it
			}
		} else {  //file is too small
			echo "No file uploaded or too small.<br /><br /><a href=\"javascript:history.back()\">Go Back</a><br /><br />\n\n";
			exit;
		}
	}else{  //file isn't set
		echo "No file specified.<br /><br /><a href=\"javascript:history.back()\">Go Back</a><br /><br />\n\n";
		exit;
	}
}

/*
*	deletes a directory specified by $dir and all files and subdirectories
*	returns true if successful, false if something couldn't be deleted.
* 	borrowed from the friendly folks on php.net:  http://www.php.net/manual/en/function.rmdir.php
*/
function clr_dir($dir) {
	if(@ ! $opendir = opendir($dir)) {
		return false;
	}
	while(false !== ($readdir = readdir($opendir))) {
		if($readdir !== '..' && $readdir !== '.') {
			$readdir = trim($readdir);
			if(is_file($dir.'/'.$readdir)) {
				if(@ ! unlink($dir.'/'.$readdir)) {
					return false;
				}
			} elseif(is_dir($dir.'/'.$readdir)) {
				// Calls itself to clear subdirectories
				if(! clr_dir($dir.'/'.$readdir)) {
					return false;
				}
			}
		}
	}
	closedir($opendir);
	if(@ ! rmdir($dir)) {
		return false;
	}
	return true;
}

/*
*	Sets the access level permissions into the session variables for the project called with $projectID
*	also sets various project variables
*	returns true on success or false on failure
*	$_SESSION['uzuriweb_wbm_projectvars']		--	contains all info from `projects` table in DB		array
*/

function set_proj_vars($projectID){
	//first we need to get the project info from the DB
	$query = "SELECT * FROM `wbm_webprojects` WHERE `ID` = '".$projectID."' LIMIT 1";
	$result = dbase_query($query);
	if (mysql_num_rows($result) == 1){
		$_SESSION['uzuriweb_wbm_projectvars'] =  mysql_fetch_array($result);
		return(true);
	} else {
		return(false);
	}
}

/*
*	Sets the various user infos into the session variable $_SESSION['uzuriweb_wbm_uservars']
*	makes it nice and neat to access these things later on.
*/

function set_user_vars($userID){
	$query = "SELECT * FROM `wbm_users` WHERE `ID` = '".$userID."' LIMIT 1";
	$result = dbase_query($query);
	if (mysql_num_rows($result) == 1){
		$_SESSION['uzuriweb_wbm_uservars'] = mysql_fetch_array($result);
		return(true);
	} else {
		return(false);
	}
}
/*
*	Converts all $text to html safe compnents EXCEPT < and >
*	Actually, it does convert those, but then it converts them back.
*/

function html_safe($text){
	$text = htmlentities($text, ENT_QUOTES);
	$text=ereg_replace('&gt;', '>', $text); 
	$text=ereg_replace('&lt;', '<', $text); 
	return($text);
}

/*
*	Un-converts all $text to html safe compnents EXCEPT < and >
*	Actually, it does convert those, but then it converts them back.
*/
function unhtml_safe($text){
	$trans_tbl = get_html_translation_table (HTML_ENTITIES);
	$trans_tbl = array_flip ($trans_tbl);
	return strtr ($text, $trans_tbl);
}


/*
*	Gets the start day of week, number of rows, and number of days for the month
*	$month == number of the month.  01 to 12
*	$year == year in YYYY format.  1999 or such
*	RETURNS:
*	array([startday],[num_days],[num_rows])
*	[start_day] == start day in terms of a column for the month.  0 to 6
*	[num_days] == number of days in the month for that particular year, also the last day, obiously
*	[num_rows] == number of rows needed for the month, should be 4 to 6
*/

function get_month_attrib($month, $year){
	//first we figure out the first day of the month, it'll be in the wday place in this array
	$day = getdate (mktime ( 0, 0, 0, $month, 1, $year));

	//now we loop through and figure out the last day
	for($i = 27; $i <= 33; $i++){ 
		if (checkdate ( intval($month), $i, $year) == false){
			
			$last_day = $i - 1; 
			break;
		}
	} 
	//now we have our lastday
	
	
	//what this does is correct the number of days in the month for calculating purposes, so that we can divide the number of days by 7 to get the number
	//of rows that we will have in the table when we build it
	$corrected_month = $last_day + ($day['wday'] );
	//we now take that divide it by 7 days
	$num_rows = intval($corrected_month/7);
	//and that is it!
	if (($corrected_month % 7) != 0){
		$num_rows++;
	}
	
	return array("start_day"=>$day['wday'],"num_days"=>$last_day,"num_rows"=>$num_rows);
}

/*
	makes any $item that is 1 char long, 2.  adds a padding 0 on the front.
*/
function make2($item){
	if (strlen($item) == 1){
		$item = "0".$item;
	}
	return($item);
}

/*
	makes any $item that is 1, 2 or 3 chars long, 4.  adds a padding 0 on the front.
*/
function make4($item){
	if (strlen($item) == 1){
		$item = "000".$item;
	} elseif (strlen($item) == 2){
		$item = "00".$item;
	} elseif (strlen($item) == 3){
		$item = "0".$item;
	}
	return($item);
}


/*
	Returns certain variable stuffs in an array for each eventID
	begin_time 	= time the thing started at, nicely formatted for output
	begin_h		= the time it starts at in hours
	begin_m 	= the time it starts at in minutes
	begin_half 	= the am or pm
	end_time	= time it ends at
	eventID		= the eventID
	duration	= the duration in minutes
	cal_date	= the cal_date just as from the database
	description = the description
	name 		= the event name
	respon		= who is responsible for the project
	today		= date info as returned by the getdate function
	hour_range 	= what hour range it falls in.  0 to 23
	allday		= 1 if event is all day, 0 if not.

*/

function get_event_info($eventID){
	$query = "SELECT * FROM `wbm_projectcal` WHERE `eventID` = '".$eventID."' LIMIT 1;";
	$result = dbase_query($query);
	$row = mysql_fetch_array($result);
	
	
	//do the begin time
	$begin_hours = substr($row['cal_time'],0,2);
	$beigin_minutes = substr($row['cal_time'],2,4);
	
	if ($begin_hours >= 12) {
		$half = "pm";
		$append = " pm";
	} elseif (($begin_hours >= 0) AND ($begin_hours <= 11)) {
		$half = "am";
		$append = " am";
	}
	
	//we subtract if it is in the pm, but not noon
	if ($begin_hours > 12) {
		$begin_hours = $begin_hours - 12;
	}
	
	$my_return['begin_time'] = $begin_hours.":".make2($beigin_minutes).$append; 
	
	
	
	//do the end time	
	$dur_hours = intval($row['duration']/60);
	$dur_minutes = $row['duration'] % 60;
	
	$end_hours = substr($row['cal_time'],0,2) + $dur_hours;
	$end_minutes = substr($row['cal_time'],2,4) + $dur_minutes;
	
	//fix bug with getting time such as 1:60pm.  Not going to next hour if minutes tally to be more than 60
	//this would happen if the start time was :30 or similar and the $dur_minutes ends up being more than :29 in this example.
	
	if ($end_minutes >= 60){
		$end_minutes = $end_minutes - 60;
		$end_hours++;
	}
	
	
	if ($end_hours >= 12) {
		$append = " pm";
	} elseif (($end_hours >= 0) AND ($end_hours <= 11)) {
		$append = " am";
	}
	
	//we subtract if it is in the pm, but not noon
	if ($end_hours > 12) {
		$end_hours = $end_hours - 12;
	}
	$my_return['end_time'] = $end_hours.":".make2($end_minutes).$append;
	
	//format the date nice and such	
	
	$thismonth =  substr($row['cal_date'], 4, 2);
	$thisyear = substr($row['cal_date'], 0, 4);
	$thisday = substr($row['cal_date'], 6, 2);
	$my_return['today'] = getdate(mktime ( 0, 0, 0, $thismonth, $thisday, $thisyear )); 

	
	$my_return['hour_range'] = intval($row[$i]['cal_time']/100);
	$my_return['eventID'] = $eventID;
	$my_return['duration'] = $row['duration'];
	$my_return['cal_date'] = $row['cal_date'];
	$my_return['description'] = $row['description'];
	$my_return['name'] = $row['name'];
	$my_return['respon'] = $row['respon'];
	$my_return['begin_h'] = $begin_hours;
	$my_return['begin_m'] = $beigin_minutes;
	$my_return['begin_half'] = $half;
	$my_return['allday'] = $row['allday'];
	return($my_return);
}

/*
	*gets events for a given month, sorted in a multidemensional array for the given month & year
	$month	=	month that we want the details for
	$year	=	year that we're dealing with
*/

function get_month_events($month,$year){
	$query = "SELECT * FROM `wbm_projectcal` WHERE `projectID` = '".$_SESSION['uzuriweb_wbm_projectvars']['ID']."' AND 
		`cal_date` >= '".$year.$month."01' AND `cal_date` <= '".$year.$month."31' ORDER BY `cal_time` ASC;";
	$result = dbase_query($query);
	if (mysql_num_rows($result) == 0){
		return (false);
	} else {
		//put all the results in one big multidemensional array
		for($i = 0; $i < mysql_num_rows($result); $i++){
			$reval[$i] = mysql_fetch_array($result); 
			
		}

		//we're going to make an even bigger array that has multidemensional entries for the days that have entries
		//so we need to know the number of possible days in the month.
		$mattrib = get_month_attrib($month, $year);

		//loop through all the days of the month and see if there are any entries for that day
		for($i = 1; $i <= $mattrib['num_days']; $i++){
			
			for($j = 0; $j <= count($reval); $j++){
/*			echo $year.$month.make2($i);
			echo "<br />\n";
			echo $reval[$i]['cal_date'];
			echo "<br />\n";*/
				if ($reval[$j]['cal_date'] == ( $year.$month.make2($i) )  ){
/*					if ( empty($returnval[$i]) ) {
						$k = 0;
					} else {
						$k = count($returnval[$i]) + 1;
					}*/
					$tmp_count = count($returnval[$i]) + 1;
					$returnval[$i][$tmp_count] = get_event_info($reval[$j]['eventID']);
				}
			}
		}
	}
	return($returnval);
}





/*
	* writes out option tags for the month
	* month is optional, if specified will write the specified month as the selected value
	* otherwise it writes the current month
*/

function opt_month($month){
	if (!isset($month)) {
		$month = date("m");
	}
	if ($month == "01"){
		$buf = $buf."<option value=\"01\" selected>January</option>\n";
	} else {
		$buf = $buf."<option value=\"01\" >January</option>\n";
	}

	if ($month == "02"){
		$buf = $buf."<option value=\"02\" selected>February</option>\n";
	} else {
		$buf = $buf."<option value=\"02\" >February</option>\n";
	}

	if ($month == "03"){
		$buf = $buf."<option value=\"03\" selected>March</option>\n";
	} else {
		$buf = $buf."<option value=\"03\" >March</option>\n";
	}
	
	if ($month == "04"){
		$buf = $buf."<option value=\"04\" selected>April</option>\n";
	} else {
		$buf = $buf."<option value=\"04\" >April</option>\n";
	}

	if ($month == "05"){
		$buf = $buf."<option value=\"05\" selected>May</option>\n";
	} else {
		$buf = $buf."<option value=\"05\" >May</option>\n";
	}
	
	if ($month == "06"){
		$buf = $buf."<option value=\"06\" selected>June</option>\n";
	} else {
		$buf = $buf."<option value=\"06\" >June</option>\n";
	}
	if ($month == "07"){
		$buf = $buf."<option value=\"07\" selected>July</option>\n";
	} else {
		$buf = $buf."<option value=\"07\" >July</option>\n";
	}
	if ($month == "08"){
		$buf = $buf."<option value=\"08\" selected>August</option>\n";
	} else {
		$buf = $buf."<option value=\"08\" >August</option>\n";
	}
	if ($month == "09"){
		$buf = $buf."<option value=\"09\" selected>September</option>\n";
	} else {
		$buf = $buf."<option value=\"09\" >September</option>\n";
	}
	if ($month == "10"){
		$buf = $buf."<option value=\"10\" selected>October</option>\n";
	} else {
		$buf = $buf."<option value=\"10\" >October</option>\n";
	}
	if ($month == "11"){
		$buf = $buf."<option value=\"11\" selected>November</option>\n";
	} else {
		$buf = $buf."<option value=\"11\" >November</option>\n";
	}
	if ($month == "12"){
		$buf = $buf."<option value=\"12\" selected>December</option>\n";
	} else {
		$buf = $buf."<option value=\"12\" >December</option>\n";
	}

	return ($buf);
	
}

/*
	* writes out option tags for the day
	* day is optional, if specified will write the specified day as the selected value
	* otherwise it writes the current day
*/

function opt_day($day){
	
	if (!isset($day)) {
		$day = date("d");
	}
	for($i = 1; $i <= 31; $i++){ 
		$curday = make2($i);
		if ($day == $i) {
			$buf = $buf."\t\t<option value=\"".$curday."\" selected>".$curday."</option>\n";
		} else {
			$buf = $buf."\t\t<option value=\"".$curday."\">".$curday."</option>\n";
		}
	}
	return ($buf);
}

/*
	* writes out option tags for the year
	* year is optional, if specified will write the specified year as the selected value
	* otherwise it writes the current year
*/

function opt_year($year){
	
	if (!isset($year)) {
		$year = date("Y");
	}
	$startdate = $year;
	$enddate = $year + 5;
		for($i = $startdate; $i <= $enddate; $i++){ 
		if ($year == $i) {
			$buf = $buf."\t\t<option value=\"".$i."\" selected>".$i."</option>\n";
		} else {
			$buf = $buf."\t\t<option value=\"".$i."\">".$i."</option>\n";
		}
	}
	
	return ($buf);
}


/*
	generates a dropdown menu with 12 months on either side of the specified month & year
	* month 	=	month we want as selected
	* year 		= 	year we want as selected
	* day		=	specified so we know what day in that month to jump to, never used otherwise
	* page		=	month.php or day.php, we use them both!
*/

function menu_month($month,$year,$day,$page){

	$buf = "<form name=\"monthmenu\">";
	$buf = $buf."<span class=\"addheader\">Month: </span>";
	$buf = $buf."<select name=\"gomenu\" onChange=\"location=document.monthmenu.gomenu.options[document.monthmenu.gomenu.selectedIndex].value;\" value=\"go\" class=\"txtinput vmiddle\">\n";
	
	$curmonth = $month;
	$curyear = $year - 1;
	for($i = 0; $i <= 24; $i++){ 
		//bring up the current month, and make it zero padded
		make2($curmonth++);
		//if the current month is beyond december
		if ($curmonth > 12){
			$curmonth = "01";
			$curyear++;
		}
		
		$now = mktime ( 0, 0, 0, $curmonth, $day, $curyear );
		$date = getdate($now);
		$longmonth = date("M", $now);
		if ($curmonth == $month && $curyear == $year){
			$selected = "selected";
		} 
		$curmonth = make2($curmonth);
		$buf = $buf."<option value=\"".$page."?date=".$curyear.make2($curmonth).make2($day)."\" ".$selected.">".$longmonth." ".$date['year']."</option>\n";
		$selected = "";
	}
	
	$buf = $buf."</select>\n</form>\n";
		
	return($buf);
		

}

/*
	generates a dropdown menu with 1 month in days on either side of the specified day, month & year
	* month 	=	month we want as selected
	* year 		= 	year we want as selected
	* day		=	specified so we know what day in that month to jump to, never used otherwise
	* page		=	month.php or day.php, we use them both!
*/

function menu_day($month,$year,$day,$page){
	$buf = "<form name=\"daymenu\">";
	$buf = $buf."<span class=\"addheader\">Day: </span>";
	$buf = $buf."<select name=\"gomenu\" onChange=\"location=document.daymenu.gomenu.options[document.daymenu.gomenu.selectedIndex].value;\" value=\"go\" class=\"txtinput vmiddle\">\n";

	$now = mktime ( 0, 0, 0, $month, $day, $year );
	$date = getdate($now);
	$longmonth = date("M", $now);

	$cur = get_month_attrib($month, $year);
	
	
	for($i = 1; $i <= $cur['num_days']; $i++){ 
		if ($day == $i){
			$selected = "selected";
		}
		$buf = $buf."<option value=\"".$page."?date=".$year.make2($month).make2($i)."\" ".$selected.">".$longmonth." ".$i."</option>\n";
		$selected = "";
	}
	$buf = $buf."</select>\n</form>\n";
		
	return($buf);
}

//## MORE CODE BORROWED FROM PHPWEBCALENDAR!  THANKS A TON!  I LOVE THE GPL!

// Get the Sunday of the week that the specified date is in.
// (If the date specified is a Sunday, then that date is returned.)
function get_sunday_before ( $year, $month, $day ) {
  $weekday = date ( "w", mktime ( 3, 0, 0, $month, $day, $year ) );
  $newdate = mktime ( 3, 0, 0, $month, $day - $weekday, $year );
  return $newdate;
}

// Get the Monday of the week that the specified date is in.
// (If the date specified is a Monday, then that date is returned.)
function get_monday_before ( $year, $month, $day ) {
  $weekday = date ( "w", mktime ( 3, 0, 0, $month, $day, $year ) );
  if ( $weekday == 0 )
    return mktime ( 3, 0, 0, $month, $day - 6, $year );
  if ( $weekday == 1 )
    return mktime ( 3, 0, 0, $month, $day, $year );
  return mktime ( 3, 0, 0, $month, $day - ( $weekday - 1 ), $year );
}


// Return the abbreviated month name
// params:
//   $m - month (0-11)
function month_short_name ( $m ) {
  switch ( $m ) {
    case 0: return ("Jan");
    case 1: return ("Feb");
    case 2: return ("Mar");
    case 3: return ("Apr");
    case 4: return ("May");
    case 5: return ("Jun");
    case 6: return ("Jul");
    case 7: return ("Aug");
    case 8: return ("Sep");
    case 9: return ("Oct");
    case 10: return ("Nov");
    case 11: return ("Dec");
  }
  return "unknown-month($m)";
}


// Return the full weekday name
// params:
//   $w - weekday (0=Sunday,...,6=Saturday)
function weekday_name ( $w ) {
  switch ( $w ) {
    case 0: return ("Sunday");
    case 1: return ("Monday");
    case 2: return ("Tuesday");
    case 3: return ("Wednesday");
    case 4: return ("Thursday");
    case 5: return ("Friday");
    case 6: return ("Saturday");
  }
  return "unknown-weekday($w)";
}

// Return the abbreviated weekday name
// params:
//   $w - weekday (0=Sun,...,6=Sat)
function weekday_short_name ( $w ) {
  switch ( $w ) {
    case 0: return ("Sun");
    case 1: return ("Mon");
    case 2: return ("Tue");
    case 3: return ("Wed");
    case 4: return ("Thu");
    case 5: return ("Fri");
    case 6: return ("Sat");
  }
  return "unknown-weekday($w)";
}


// convert a date from an int format "19991231" into
// "Friday, December 31, 1999", "Friday, 12-31-1999" or whatever format
// the user prefers.
function date_to_str ( $indate, $format="", $show_weekday=true, $short_months=false ) {
 $format = "__month__ __dd__, __yyyy__";
  if ( strlen ( $indate ) == 0 ) {
    $indate = date ( "Ymd" );
  }
  $y = (int) ( $indate / 10000 );
  $m = (int) ( $indate / 100 ) % 100;
  $d = $indate % 100;
  $date = mktime ( 3, 0, 0, $m, $d, $y );
  $wday = strftime ( "%w", $date );
  $weekday = weekday_name ( $wday );

  if ( $short_months )
    $month = month_short_name ( $m - 1 );
  else
    $month = month_name ( $m - 1 );
  $yyyy = $y;
  $yy = sprintf ( "%02d", $y %= 100 );

  $ret = $format;
  $ret = str_replace ( "__yyyy__", $yyyy, $ret );
  $ret = str_replace ( "__yy__", $yy, $ret );
  $ret = str_replace ( "__month__", $month, $ret );
  $ret = str_replace ( "__mon__", $month, $ret );
  $ret = str_replace ( "__dd__", $d, $ret );
  $ret = str_replace ( "__mm__", $m, $ret );

  if ( $show_weekday )
    return "$weekday, $ret";
  else
    return $ret;
}

?>
