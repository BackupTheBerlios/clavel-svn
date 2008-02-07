<?php

    //    ELGG manage files page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/../includes.php");
        
    // Initialise functions for user details, icon management and profile management
        run("userdetails:init");
        run("profile:init");
        run("pictures:init");
        
        define("context", "pictures");
        templates_page_setup();

    // Whose files are we looking at?

        global $CFG, $page_owner, $owner, $folder;
        $title = user_info("name", page_owner()) . " :: ". __gettext("Pictures") ."";

        $folder_object = get_record('file_folders','files_owner',$owner,'ident',$folder);

        $body = run("content:pictures:view");
                
        if (!is_object($folder_object) || $folder_object->handler == "elgg"
            || !isset($folder_object->handler)
            || !isset($CFG->folders->handler[$folder_object->handler]->function_name)
            || !is_callable($CFG->folders->handler[$folder_object->handler]->function_name)) {
            $body .= run("pictures:view",$folder_object);
        } else {
            $body .= $CFG->folders->handler[$folder_object->handler]->function_name($folder_object);
        }
        
        templates_page_output($title, $body);
                
?>