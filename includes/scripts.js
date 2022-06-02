 function collapseTableRow(tableId){   
 // modded to make table rows/cells collapse properly in mozilla and msie (fucking annoying browser incompatibilities! grrr) 
 //happily given to me by my buddy weez, what a swell fella!
  
 if (navigator.appName=="Netscape"&&parseFloat(navigator.appVersion)>=4.7) { 
  
                if(document.getElementById("t" + tableId).style.display == "table-cell"){ 
                     document.getElementById("t" + tableId).style.display = "none"; 
                     document.getElementById("i" + tableId).src = "assets/icon_plus.png"; 
                     //document.getElementById("f" + tableId).src = "/assets/icon_folder.png"; 
                      
                } else { 
                     document.getElementById("t" + tableId).style.display = "table-cell"; 
                     document.getElementById("i" + tableId).src = "assets/icon_minus.png"; 
                     //document.getElementById("f" + tableId).src = "/icon_folder_open.png"; 
                } 
                 
 }                
 else { 
                 
                if(document.getElementById("t" + tableId).style.display == "block"){ 
                     document.getElementById("t" + tableId).style.display = "none"; 
                     document.getElementById("i" + tableId).src = "assets/icon_plus.png"; 
                     //document.getElementById("f" + tableId).src = "/assets/icon_folder.png"; 
                      
                } else { 
                     document.getElementById("t" + tableId).style.display = "block"; 
                     document.getElementById("i" + tableId).src = "assets/icon_minus.png"; 
                     //document.getElementById("f" + tableId).src = "/icon_folder_open.png"; 
                }      
  
 } }
 
//confirm the deletion of an item
//con_message is the message that the user will se displayed in the dialog box
function confirm_submit(con_message){
	var conf=confirm(con_message);
	if (conf){
		return true;
	}else{
		return false;
	}
}

//takes data from one form and dumps it in another
//form must be named newspost
//form field must be named newspost
//myval is the name of data passed
function writeup(myval){
	document.forms.newspostform.newspost.value = document.forms.newspostform.newspost.value + myval;
}

//validates fields for the news posting pages

function checknewsform ( form )
{
	//newspost & modify forms
    if (document.newspostform.title.value == "") 
	{
        alert( "Please enter a title." );
        form.title.focus();
        return false ;
	}


    if (document.newspostform.newspost.value == "") 
	{
        alert( "Please enter some text for the update." );
        form.newspost.focus();
        return false ;
	}	
 return true ;
}

//validates fields for the upload pages

function checkupform ( form )
{
	if (document.upform.up_file.value == "") 
	{
        alert( "Please select a file to upload." );
        form.up_file.focus();
        return false ;
	}
	
    if (document.upform.ftype.value == "1" || document.upform.ftype.value == "") 
	{
        alert( "Please enter a file type." );
        form.ftype.focus();
        return false ;
	}
	
	
    if (document.upform.fcat.value == "" || document.upform.fcat.value == "1") 
	{
        alert( "Please select a category for the file." );
        form.fcat.focus();
        return false ;
	}
	
    if (document.upform.fdesc.value == "") 
	{
        alert( "Please enter a description." );
        form.fdesc.focus();
        return false ;
	}			
 return true ;
}

//alerts that selected type isn't web safe

function check_web_safe(){
	if ( document.upform.ftype.value != "png"){
		if ( document.upform.ftype.value != "gif"){
			if ( document.upform.ftype.value != "jpg"){
				if ( document.upform.ftype.value != "1"){
		alert( "Be aware that the file format you have selected may not display correctly in most browsers.");
				}
			}
		}
	}
	

}

//validates fields for the coments posting pages

function checkcommentform ( form )
{
	//newspost & modify forms
    if (form.poster.value == "") 
	{
        alert( "Please enter your name." );
        form.poster.focus();
        return false ;
	}	
    if (form.p_email.value == "") 
	{
        alert( "Please enter your email address." );
        form.p_email.focus();
        return false ;
	}	
	if (form.newspost.value == "") 
	{
        alert( "Please enter a comment." );
        form.newspost.focus();
        return false ;
	}	
 return true ;
}

//to disable the time on the calendar if an allday event is selected
function disabletime(){
    if (document.calevent.allday.checked)
	{
		document.forms.calevent.time_hour.disabled = true;
		document.forms.calevent.time_minute.disabled = true;
		document.forms.calevent.half.disabled = true;
		document.forms.calevent.dur_time.disabled = true;		
	} else {
		document.forms.calevent.time_hour.disabled = false;
		document.forms.calevent.time_minute.disabled = false;
		document.forms.calevent.half.disabled = false;
		document.forms.calevent.dur_time.disabled = false;	
		
	}
	
}

//used to disable or enable the file upload field for the add and modify user page.
//1 enables, 0 disables
function nopic(myval){
	if (myval == 0){
		document.forms.user.up_file.disabled = true;
	} else if (myval == 1) {
		document.forms.user.up_file.disabled = false;
	}


}

//used to disable or enable the project selection field for admin options

function checkadmin(what){
	if (document.forms.user.access.value == "admin"){
		document.user.elements['project[]'].disabled = true;
	} else {
		document.user.elements['project[]'].disabled = false;
	}

}

//use on calendar event pages to validate the date to make sure it is not in the past
//does not check to allow for non-valid dates such as feburary 31st or something like that

function future_date()
{
	var curdate = new Date();
	var curmonth = curdate.getMonth() + 1;
	var curday = curdate.getDate();
	
	if (document.calevent.name.value == ''){
		alert ("You must enter a name for this event.");
		return false;
	} else if (document.calevent.description.value == ''){
		alert ("You must enter a description for this event.");
		return false;
/*	} else if (document.calevent.year.value < curdate.getYear()){
		alert ("The date you have selected is in the past.\r\nPlease enter a valid date.");
		return false;
	} else if (document.calevent.month.value < curmonth){
		alert ("The date you have selected is in the past.\r\nPlease enter a valid date.");
		return false;
	} else if (document.calevent.day.value < curday){
		alert ("The date you have selected is in the past.\r\nPlease enter a valid date.");
		return false;*/
	} else if ((!document.calevent.allday.checked) && (document.calevent.time_hour.value == '')){
		alert ("You must enter a start hour.");
		return false;
	} else if ((!document.calevent.allday.checked) && (document.calevent.time_minute.value == '')){
		alert ("You must enter a start minute.");
		return false;
	} else if ((!document.calevent.allday.checked) && ((document.calevent.time_hour.value > 12) || (document.calevent.time_hour.value < 1))){
		alert ("You must enter a valid start hour.");
		return false;
	} else if ((!document.calevent.allday.checked) && ((document.calevent.time_minute.value > 59) || (document.calevent.time_minute.value < 0))){
		alert ("You must enter a valid start minute.");
		return false;
	} else if ((!document.calevent.allday.checked) && (document.calevent.dur_time.value == '')){
		alert ("You must enter a duration.");
		return false;
	} else if (document.calevent.respon.value == '1'){
		alert ("You must select a client responsibility for this event.");
		return false;	
	} else {
		return true;
	}
}

//for looping through the download_cur page to see what files have been checked

function writethecurrent()
{
	for (var i = 0; i < document.downform.countmax.value; i++)
		{
			if (document.getElementById("check" + i).checked == true)
			{				
				opener.writeup(' \n <file=' + document.getElementById(idline = "id"+i).value + ' description=' + document.getElementById("desc"+i).value + '> \n ');
			}
		}
}


//validate the form for the help page
function checkhelp ( form )
{
	if (form.email.value == "" ||
     form.email.value.indexOf('@') == -1 ||
     form.email.value.indexOf('.') == -1 ||
     form.email.value.length<10) {
	 
        alert("Please enter a valid email address");
        form.email.focus();
        return false ;
	}

	 if (form.desc.value == "") 
	{
        alert( "Please enter a problem descirption." );
        form.desc.focus();
        return false ;
	}	

}