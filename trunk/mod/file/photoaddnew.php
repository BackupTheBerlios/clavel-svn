<?php

        require_once(dirname(dirname(__FILE__))."/../includes.php");

    // Initialise functions for user details, icon management and profile management
        run("userdetails:init");
        run("profile:init");
        run("files:init");

        define("context", "file");
		templates_page_setup();

        $title = user_info("name", page_owner()) . " :: ". __gettext("Add new Photo");
        $body = run('photo:add');

        templates_page_output($title, $body);

?>