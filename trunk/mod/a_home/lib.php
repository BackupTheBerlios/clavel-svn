<?php

function a_home_pagesetup() {
    // register links --
    global $profile_id;
    global $PAGE;
    global $CFG;

    $page_owner = $profile_id;
    $rss_username = user_info('username', $page_owner);
    define("home", $context);

    if (isloggedin()) {
    	if (defined("context") && context == "home" && $page_owner == $_SESSION['userid']) {

      		$PAGE->menu[]= array (
        		'name' => 'home',
        		'html' => "<li><a href=\"{$CFG->wwwroot}/ \" class=\"selected\" >" . __gettext("Home") . '</a></li>');

    	} else {
      		$PAGE->menu[]= array (
        		'name' => 'home',
       	 		'html' => "<li><a href=\"{$CFG->wwwroot} \" >" . __gettext("Home") . '</a></li>');
    	};
  	}

}





function a_home_init() {

    global $CFG;

    // Delete users
    listen_for_event("user","delete","newsclient_user_delete");



}




?>