<?php

    //    ELGG get new password page

    // Run includes
        define("context","external");
        require_once(dirname(dirname(__FILE__))."/../includes.php");
        
        run("invite:init");
        templates_page_setup();
        $title = sprintf(__gettext("Create new password"));
        
        $body = run("invite:password:createnew");
        
        templates_page_output($title, $body);

?>