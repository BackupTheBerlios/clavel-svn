<?php
/*
 * This script shows the 'Send Message' link in the sidebar
 *
 * @uses $CFG,$USER
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos Más - 2007
*/

global $CFG, $USER;
$page_owner = page_owner();

$community_membership_query = 'SELECT COUNT(u.ident) FROM ' . $CFG->prefix . 'friends f';
$community_membership_query .= ' LEFT JOIN ' . $CFG->prefix . 'users u ON u.ident = f.friend';
$community_membership_query .= ' WHERE f.owner = ? AND f.friend = ?';

if (isloggedin() && $page_owner != $_SESSION['userid'] && $page_owner != -1) {
  $messages_link = '<ul><li><a href="' . $CFG->wwwroot . "mod/messages/compose.php?action=compose&to=$page_owner" . '">' . __gettext("Send Message") . '</a></li></ul>';
  if (!MESSAGES_SIDEBAR_NO_MEMBER_LINK
       && user_type($page_owner) == "community" 
       && count_records_sql($community_membership_query, array ($USER->ident,$page_owner)) == 0) {
    $messages_link = '';
  }
  else if(!MESSAGES_COMMUNITY_MESSAGES && user_type($page_owner) == "community" ){
  	$messages_link = '';
  }
  $run_result .= $messages_link;
} else {
  $run_result .= '';
}
?>