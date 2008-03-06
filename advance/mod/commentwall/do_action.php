<?php

	@require_once("../../includes.php");

	global $messages, $CFG;

	/**
	 * Handle comment post.
	 */

	$ident = optional_param('ident','');
	$action = optional_param('action','');
	$owner = optional_param('owner','');
	$wallowner = optional_param('wallowner','');
	$comment_owner = optional_param('comment_owner','');
	$reply = optional_param('reply','');
	$displaymode = optional_param('displaymode','noxml');
	$text = optional_param('text','');
	$returnurl = urldecode(optional_param('return_url',''));
	
	$page = "";

	/**
	 * Post comments etc.
	 */
	if ($action == "commentwall::post")
	{
			
		// Store the rating
        $success = (empty($text)) ? false : commentwall_addcomment($wallowner, $comment_owner, $text);

		// Message
		if ($success) 
		{
			$messages[] = __gettext("Comment posted.");  
			if ($displaymode=="xml") 
				$messages[] = __gettext(" Click here to see.");

			// Hook for the river plugin (if installed)
			if (function_exists('river_save_event'))
			{
				$verb = __gettext("left");
				if ($reply!="")
					$verb = __gettext("replied to");
				
				// Wall owner
				$string = sprintf(__gettext("<a href=\"%s\">%s</a> $verb a message on <a href=\"%s\">%s's comment wall</a>"),
					river_get_userurl($comment_owner),
					user_info("name", $comment_owner),
					river_get_userurl($wallowner),
					user_info("name", $wallowner)
				);
				river_save_event($wallowner, $success, $comment_owner, "commentwall::post", $string);
				
				// Comment owner
				$noun = __gettext("A message");
				if ($reply!="")
					$noun = __gettext("A reply");
				$string = sprintf(__gettext("$noun was left by <a href=\"%s\">%s</a> in <a href=\"%s\">%s's comment wall</a>"),
					river_get_userurl($comment_owner),
					user_info("name", $comment_owner),
					river_get_userurl($wallowner),
					user_info("name", $wallowner)
				);
				river_save_event($comment_owner, $success, $comment_owner, "commentwall::post", $string);
			}
		}
		else
		{
			$messages[] = __gettext("Comment could not be posted.");
		}
		
		// Are we outputing XML or text
		if ($displaymode=="xml")
		{
			$message = implode(" \n", $messages);
			if ($success) 
				$err = "0";
			else
				$err = "1";	
	
			$page = "<ajax>\n<message>$message</message>\n<error>$err</error>\n</ajax>\n";
		}
		else
		{
			header("Location: $returnurl");
			exit;
		}
	}
	else if($action == "commentwall::delete")
	{
		if (commentwall_deletecomment($ident))
		{
			// Success
		}
		else
		{
			// Fail
		}
		
		// Redirect
		header("Location: $returnurl");
		exit;
	}
	
	// Output the page
	if ($displaymode=="xml") {
		header("Content-type: text/xml");
		
		echo $page;
	}
	
	
?>