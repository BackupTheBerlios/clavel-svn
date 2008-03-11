<?php

/*
 * This script loads and append to $run_result the data for the specified message
 *
 * @param object $msg ($parameter) The message to be showed
 * @param int $sent If the list must to show the sent messages (optional)
 *
 * @uses $profile_id
 * @uses $CFG
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos Más - 2007
*/
if (isset ($parameter)) {
  global $CFG, $profile_id;
  $msg= $parameter;

  $sent= optional_param('sent');
  $author= new StdClass;

  $authorid= ($sent == 1) ? $msg->to_id : $msg->from_id;
  if ($authorInfo= get_record('users', 'ident', $authorid)) {
    $author->username= $authorInfo->username;
    $author->fullname= htmlspecialchars($authorInfo->name, ENT_COMPAT, 'utf-8');
    $author->ident  = $authorInfo->ident;
  } else {
    $author->username= "";
    $author->fullname= "";
    $author->ident= -1;
  }
  $author->icon= '<a href="' . url . $author->username . '/">' .user_icon_html($author->ident)."</a>";

  $date= strftime("%d %b %Y, %H:%M", $msg->posted);
  $title= run("weblogs:text:process", $msg->title);
  $body= run("weblogs:text:process", $msg->body);

  $reply= __gettext("Reply");
  $returnConfirm= __gettext("Are you sure you want to permanently delete this message?");
  $Delete= __gettext("Delete");
  $from_msg= __gettext("To:");
  if (!$sent) {
    $from_msg= __gettext("From:");
    $links= '<a href="' . $CFG->wwwroot . 'mod/messages/compose.php?action=reply&amp;msg_id=' . $msg->ident . '&amp;to=' . $msg->from_id . '">' . $reply . '</a> |';
  }
  $links .= '&nbsp;<a href="' . $CFG->wwwroot . 'mod/messages/messages_actions.php?action=delete&amp;sent=' . $sent . '&amp;msg_id=' . $msg->ident . '" onclick="return confirm(\'' . $returnConfirm . '\')">' . $Delete . '</a> |';

  // Updated the message to the read status
  if ($msg->status == "unread" && $msg->to_id == $profile_id) {
    $msg->status= "read";
    update_record('messages', $msg);
  }

  $run_result .= templates_draw(array (
    'context' => 'plug_detailedmessage',
    'date' => $date,
    'title' => $title,
    'from_username' => $author->username,
    'from_name' => $from_msg . ' <a href="' . url . $author->username . '/">' . $author->fullname . "</a>",
    'from_icon' => $author->icon,
    'body' => $body,
    'links' => $links
  ));
}
?>