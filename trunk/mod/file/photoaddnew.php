<?php

        require_once(dirname(dirname(__FILE__))."/../includes.php");

    // Initialise functions for user details, icon management and profile management
        run("userdetails:init");
        run("profile:init");
        run("files:init");

        define("context", "files");
        templates_page_setup();

    // Whose friends are we looking at?
        global $page_owner;

    // You must be logged on to view this!
    //    protect(1);

        $title = user_info("name", page_owner()) . " :: ". __gettext("Add new Photo");
        $body = run('photo:add');

        templates_page_output($title, $body);

?>