<?php
session_start();
include 'includes/vars_inc.php'; 
include 'includes/auth_inc.php';
include 'includes/func_inc.php';

//our goback link
$goback == "<br /><a href=\"add_event.php\">Go back</a>";

if ($_POST["proc"] == "add_event")
{	
	//verify stuff
	if (empty($_POST['name'])){
		echo "No Event Name entered.";
		echo $goback;
		exit;
	}
	
	if (empty($_POST['description'])){
		echo "No description entered.";
		echo $goback;
		exit;
	}
	
	//only check the hour, minute and duration if it is not an all day event
	if ($_POST['allday'] != "1"){
		if (empty($_POST['time_hour'])){
			echo "No minute time entered.";
			echo $goback;
			exit;
		}
		
		if (empty($_POST['time_minute'])){
			echo "No minute time entered.";
			echo $goback;
			exit;
		}
		
		if (empty($_POST['dur_time'])){
			echo "No duration entered.";
			echo $goback;
			exit;
		}
	}
	
	if ($_POST['respon'] == "1"){
		echo "No responsibility set.";
		echo $goback;
		exit;
	}
	//makes our name & description HTML safe
	$name = htmlentities($_POST['name'],ENT_QUOTES);
	$description = htmlentities($_POST['description'],ENT_QUOTES);
	
	//we want to make line breaks work in our description
	$description = nl2br($description);
	
	//formats the date & time to be in a unix time format
	//$unix_time = strtotime ($date[2]."-".$date[0]."-".$date[1]." ".$_POST['time']);
	
	//puts the date in YYYYMMDD format
	$cal_date = $_POST['year'].$_POST['month'].$_POST['day'];
	
	//if the time is PM then we make it +12 hours
	if ($_POST['half'] == "pm"){
		$_POST['time_hour'] = $_POST['time_hour'] + 12;
	}
	
	//but if it is noon we set it back
	if (($_POST['time_hour'] == 24) AND ($_POST['half'] == "pm")){
		$_POST['time_hour'] = 12;
	}
	
	//put time in HHMM format (24 hour format)
	$cal_time = $_POST['time_hour'].$_POST['time_minute'];
	
	//breaks the duration up into two parts
	$dur_time = explode (":", $_POST['dur_time']);
	
	//allday setup
	if ($_POST['allday'] != "1"){
		$$_POST['allday'] = "0";
	}
	
	
	//now we ride our elephant
	$query = "INSERT INTO `wbm_projectcal` (`eventID`, `projectID`, `ownerID`, `duration`, `cal_date`, `cal_time`, `allday`, `name`, `description`, `add_time`, `respon`) VALUES
		 ('', '".$_SESSION['uzuriweb_wbm_projectvars']['ID']."', '".$_SESSION['uzuriweb_wbm_uservars']['ID']."', '".$_POST['dur_time']."', '".$cal_date."', '".$cal_time."',
		  '".$_POST['allday']."', '".$name."', '".$description."', NOW(), '".$_POST['respon']."');"; 
		  		  
	//enter the query
	dbase_query($query);
	
	@ header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/add_event.php?procmsg=1");
} elseif ($_POST["proc"] == "mod_event")
{	
	//verify stuff
	if (empty($_POST['name'])){
		echo "No Event Name entered.";
		echo $goback;
		exit;
	}
	
	if (empty($_POST['description'])){
		echo "No description entered.";
		echo $goback;
		exit;
	}
	
	//only check the hour, minute and duration if it is not an all day event
	if ($_POST['allday'] != "1"){
		if (empty($_POST['time_hour'])){
			echo "No minute time entered.";
			echo $goback;
			exit;
		}
		
		if (empty($_POST['time_minute'])){
			echo "No minute time entered.";
			echo $goback;
			exit;
		}
		
		if (empty($_POST['dur_time'])){
			echo "No duration entered.";
			echo $goback;
			exit;
		}
	}
	
	if ($_POST['respon'] == "1"){
		echo "No responsibility set.";
		echo $goback;
		exit;
	}
	//makes our name & description HTML safe
	$name = htmlentities($_POST['name'],ENT_QUOTES);
	$description = htmlentities($_POST['description'],ENT_QUOTES);
	
	//we want to make line breaks work in our description
	$description = nl2br($description);
	
	//formats the date & time to be in a unix time format
	//$unix_time = strtotime ($date[2]."-".$date[0]."-".$date[1]." ".$_POST['time']);
	
	//puts the date in YYYYMMDD format
	$cal_date = $_POST['year'].$_POST['month'].$_POST['day'];
	
	//if the time is PM then we make it +12 hours
	if ($_POST['half'] == "pm"){
		$_POST['time_hour'] = $_POST['time_hour'] + 12;
	}
	
	//but if it is noon we set it back
	if (($_POST['time_hour'] == 24) AND ($_POST['half'] == "pm")){
		$_POST['time_hour'] = 12;
	}
	
	//put time in HHMM format (24 hour format)
	$cal_time = $_POST['time_hour'].$_POST['time_minute'];
	
	//breaks the duration up into two parts
	$dur_time = explode (":", $_POST['dur_time']);
	//duration is set in minututes so we convert...
	
		//allday setup
	if ($_POST['allday'] != "1"){
		$$_POST['allday'] = "0";
	}
	
	//now we ride our elephant
	$query = "UPDATE `wbm_projectcal` SET `ownerID` = '".$_SESSION['uzuriweb_wbm_uservars']['ID']."', `duration` =  '".$_POST['dur_time']."', `cal_date` = '".$cal_date."',
		`cal_time` = '".$cal_time."',  `allday` = '".$_POST['allday']."', `name` = '".$name."', `description` = '".$description."', `respon` = '".$_POST['respon']."'
		WHERE `eventID` = '".$_POST['modID']."' LIMIT 1";
	
		  		  
	//enter the query
	dbase_query($query);
	
	@ header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/view_event.php?procmsg=modsuc&eventID=".$_POST['modID']);
}
?>
