<?php

    //    ELGG manage community memberships page

    // Run includes
        require_once(dirname(dirname(__FILE__))."/../includes.php");

    // Initialise functions for user details, icon management and profile management
        run("userdetails:init");
        run("profile:init");
        run("friends:init");
        run("communities:init");

        $context = (defined('COMMUNITY_CONTEXT_OUT'))?COMMUNITY_CONTEXT:"community_out";

        define("context", $context);
        templates_page_setup();

        $title = __gettext("All Communities");
        $body = run('allcommunities:out');

        templates_page_output($title, $body);

?>