<?php

/*
 * This script loads and append to $run_result the basic data for the specified message
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
  $msg= $parameter[0];
  $sent= $parameter[1];
  $index= $parameter[2];

  $sent= optional_param('sent', 0, PARAM_INT);
  $author= new StdClass;

  $authorid= ($sent === 1) ? $msg->to_id : $msg->from_id;
  if ($authorInfo= get_record('users', 'ident', $authorid)) {
    $author->username= $authorInfo->username;
    $author->fullname= htmlspecialchars($authorInfo->name, ENT_COMPAT, 'utf-8');
    $author->ident = $authorInfo->ident;
  } else {
    $author->username= "";
    $author->fullname= "";
    $author->ident= -1;
  }

  $author->icon= '<a href="' . url . $author->username . '/">' .user_icon_html($author->ident,50). "</a>";

  $mark= "<input type=\"checkbox\" name=\"selected[]\" value=\"" . $msg->ident . "\" onclick=\"mark(this)\">";

  $date= strftime("%d/%m/%Y, %H:%M", $msg->posted);
  $username= user_info('username', $msg->from_id);
  $title= run("weblogs:text:process", $msg->title);
  $msg_style= "";
  if ($msg->status == "read" || $sent === 1) {
    $msg_style= "class='message_read'";
  }

  $run_result .= templates_draw(array (
    'context' => 'plug_message',
    'date' => $date,
    'title' => '<a href="' . url . $_SESSION['username'] . '/messages/view/' . $msg->ident . "/$sent\">" . $title . "</a>",
    'from_username' => $author->username,
    'from_name' => '<a href="' . url . $author->username . '/">' . $author->fullname . "</a>",
    'from_icon' => $author->icon,
    'msg_style' => $msg_style,
    'mark' => $mark,
    'index' => $index
  ));
}
?>