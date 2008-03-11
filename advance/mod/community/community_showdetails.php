<?php

    // Run includes
    require_once(dirname(dirname(__FILE__))."/../includes.php");

    // Initialise functions for user details, icon management and profile management
    run("userdetails:init");
    run("profile:init");
    run("friends:init");
    run("communities:init");

    $context = (defined('COMMUNITY_CONTEXT'))?COMMUNITY_CONTEXT:"community";

    define("context", $context);
    templates_page_setup();

    // Whose friends are we looking at?
    global $page_owner,$profile_id;

        $title = run("profile:display:name");
        $body = run('communities:showdetails', array($page_owner));

        templates_page_output($title, $body);

?>