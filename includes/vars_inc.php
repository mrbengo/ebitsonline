<?php
//path variables
//edit this as needed
$dw_files_path = "/absolute/path/to/your/files/"; 		     //Path where file repository directories will be create, should end with /files/ 
$dw_userpics_path = "/absolute/path/to/your/userpics/";     //Path where user pictures will be saved, should end with /userpics/


//database info
//edit this as needed
$dw_dbase	=	"uzuriweb_mdbtwo";							//name of mysql database
$dw_host 	=	"localhost";							//database host
$dw_user	=	"uzuriweb_mtu";							//db user name
$dw_pass	=	"mtumzuri1900:)";							//db user password

//Help email info.  This is an array of the people that should get an email from users on the help page
//This is a bit of a stop-gap measure.

$dw_help_mail = array("edit@this.com","edit2@this.com");    //edit this for the people that should get help messages


//config variables, probably shouldn't edit this.
$use_auth = true;									//whether or not to use authentication, keep it true unless you don't care

?>