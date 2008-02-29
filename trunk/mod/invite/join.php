<?php

    //    ELGG invite-a-friend page

    // Run includes
        define("context","external");
        require_once(dirname(dirname(__FILE__))."/../includes.php");

        run("invite:init");

		$sitename = sitename;
        //$title = sprintf(__gettext("Account Activated"));
		run("join:activate");
		$rssresult = run("weblogs:rss:publish", array($ident, false));
        $rssresult = run("files:rss:publish", array($ident, false));
        $rssresult = run("profile:rss:publish", array($ident, false));
        $_SESSION['messages'][] = sprintf(__gettext("Thank you for check your Email Address. Now your acount is active.\n\nYou can loged in %s"),$sitename);
                 //delete_records('invitations','code',$code);
        /*$body = run("content:invite:join");
        $body .= run("join:activate");*/
		//templates_page_setup();
        //templates_page_output($title, $body);
		header("Location: " . $CFG->wwwroot);
?>