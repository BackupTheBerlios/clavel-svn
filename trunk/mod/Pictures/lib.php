<?php

require_once($CFG->dirroot.'lib/filelib.php');

function pictures_pagesetup() {
    // register links -- 
    global $profile_id;
    global $PAGE;
    global $CFG;
    global $metatags;
    
    //require_once (dirname(__FILE__)."/lib/file_config.php");
    $page_owner = $profile_id;
    
    if (isloggedin()) {
        if (defined("context") && context == "pictures" && $page_owner == $_SESSION['userid']) {
            $PAGE->menu[] = array( 'name' => 'pictures',
                                   'html' => "<li><a href=\"{$CFG->wwwroot}{$_SESSION['username']}/pictures/\" class=\"selected\" >" .__gettext("Your Pictures").'</a></li>');
        } else {
            $PAGE->menu[] = array( 'name' => 'pictures',
                                   'html' => "<li><a href=\"{$CFG->wwwroot}{$_SESSION['username']}/pictures/\" >" .__gettext("Your Pictures").'</a></li>');
        }
    }

    if (defined("context") && context == "pictures") {
        
        $files_username = user_info('username', $page_owner);
                
        /*if ($page_owner != -1) {
            if ($page_owner == $_SESSION['userid'] && $page_owner != -1) {
                $PAGE->menu_sub[] = array( 'name' => 'file:rss', 
			'html' => '<a href="' . $CFG->wwwroot . $_SESSION['username'] . '/files/rss/"><img src="' . $CFG->wwwroot . 'mod/template/icons/rss.png" border="0" alt="rss" /></a>');
            }
        }

        if ($page_owner == $_SESSION['userid'] && $page_owner != -1) {
            $PAGE->menu_sub[] = array( 'name' => 'file:add',
                                       'html' => a_href( "#addFile",
                                                          __gettext("Add a file or a folder")));
		}*/
    }

  // Adding the file's selector wizard
  $options = array('options'=> 'width=600,height=300,left=20,top=20,scrollbars=yes,resizable=yes',
                   'name'=> 'mediapopup',
                   // 'url' => $CFG->wwwroot."mod/file/file_include_wizard.php?owner=".$_SESSION['userid']);
                   'url' => $CFG->wwwroot."mod/file/file_include_wizard.php?owner=".page_owner());
  add_content_tool_button("mediapopup",__gettext("Add File"), "image.png", "f", $options);

}
?>