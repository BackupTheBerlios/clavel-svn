<?php
function messages_pagesetup() {
  // register links --
  global $profile_id,$PAGE,$CFG,$metatags,$function,$USER;

  $pgowner= $profile_id;

  require_once $CFG->dirroot . "mod/messages/lib/messages_config.php";
  require_once $CFG->dirroot . "mod/messages/default_template.php";

  if (isloggedin() && user_info("user_type", $_SESSION['userid']) != "external") {
    // Add the JavaScript functions
    // Lose the trailing slash
    $url= substr($CFG->wwwroot, 0, -1);
    $metatags .= "<script language=\"javascript\" type=\"text/javascript\" src=\"$url/mod/messages/messages.js\"></script>";
    $metatags .= "<link rel=\"stylesheet\" href=\"" . $CFG->wwwroot . "mod/messages/css.css\" type=\"text/css\" media=\"screen\" />";

    $messages = count_records_select('messages','to_id='.$USER->ident." AND status='unread'");
    if (defined("context") && context == "messages" && $pgowner == $_SESSION['userid']) {
      $PAGE->menu[]= array (
        'name' => 'messages',
        'html' => '<li><a href="' . $CFG->wwwroot . $_SESSION['username'] . '/messages/" class="selected">' .
                  __gettext("Messages") . " ($messages)".'</a></li>');
    } else {
      $PAGE->menu[]= array (
        'name' => 'messages',
        'html' => '<li><a href="' . $CFG->wwwroot . $_SESSION['username'] . '/messages/">' . __gettext("Messages") .
                  " ($messages)". '</a></li>');
    }

    if (profile_permissions_check("profile") && defined("context") && context == "messages") {

      if (user_type($pgowner) == "person") {
        $PAGE->menu_sub[]= array (
          'name' => 'messages:list',
          'html' => '<a href="' . $CFG->wwwroot . $_SESSION['username'] . '/messages/">' . __gettext("View Messages") . '</a>');

        $PAGE->menu_sub[]= array (
          'name' => 'messages:compose',
          'html' => '<a href="' . $CFG->wwwroot . $_SESSION['username'] . '/messages/compose">' . __gettext("Compose") . '</a>');

        $PAGE->menu_sub[]= array (
          'name' => 'messages:sent',
          'html' => '<a href="' . $CFG->wwwroot . $_SESSION['username'] . '/messages/sent">' . __gettext("Sent Messages") . '</a>');
      }
    }
  }
}

function messages_init() {
  global $CFG, $function, $db, $METATABLES;
  if (in_array($CFG->prefix . "messages", $METATABLES)) {
    $messagesTable= $db->MetaColumnNames($CFG->prefix . "messages", true);
    // If dosn't exists adding the colummns 'hidden_from' and 'hidden_to'
    if (!in_array("hidden_from", $messagesTable) || !in_array("hidden_to", $messagesTable)) {
      if (file_exists($CFG->dirroot . "mod/messages/$CFG->dbtype.sql")) {
        modify_database($CFG->dirroot . "mod/messages/$CFG->dbtype.sql");
      } else {
        error("Error: Your database ($CFG->dbtype) is not yet fully supported by the Elgg messages plug-in.  See the mod/messages directory.");
      }
    }
  }

  // Functions to perform initializacion

  $function['messages:init'][] = $CFG->dirroot . "mod/messages/lib/messages_init.php";

  // Compose / Delete messages
  $function['messages:new'][] = $CFG->dirroot . "mod/messages/lib/messages_new.php";
  $function['messages:new:body'][] = $CFG->dirroot . "mod/tinymce/tinymce_js.php";

  // View a message
  $function['messages:view'][] = $CFG->dirroot . "mod/messages/lib/messages_view.php";
  $function['messages:message:view'][] = $CFG->dirroot . "mod/messages/lib/messages_message_view.php";
  $function['messages:detailedview'][] = $CFG->dirroot . "mod/messages/lib/messages_message_detailedview.php";

  // Sidebar display function
  $function['messages:contact:link'][] = $CFG->dirroot ."/mod/messages/lib/messages_sidebar_link.php";
  $function['users:infobox:menu:text'][] = $CFG->dirroot ."/mod/messages/lib/messages_sidebar_link.php";


  // Inits the display field module param for the 'select' input field
  // I know, I know this is a big cannon to kill a fly, but I want to use the display_input_field function :P
  if (!isset ($CFG->display_field_module)) {
    $CFG->display_field_module= array ();
  }
  if (!array_key_exists("as_select", $CFG->display_field_module)) {
    $CFG->display_field_module["as_select"]= "messages";
  }
}

/**
 * Function that extends the display_input_field functionality for support:<br>
 * <ul>
 *  <li>the 'select' input field that receives an assosiative array as parameter</li>
 * </ul>
 * @param array $parameter an array where:
 *                      0 => input name to display (for forms etc)
 *                      1 => data
 *                      2 => type of input field
 *                      3 => reference name (for tag fields and so on)
 *                      4 => ID number (if any)
 *                      5 => Owner
 *                      6 => Array()
 *@return string the string that represent the specified input type
 */
function messages_display_input_field($parameter) {
  $cleanid= $parameter[0];
  switch ($parameter[2]) {
    case "asoc_select":
    case "as_select" :
      $run_result = "<select name=\"" . $parameter[0] . "\" id=\"" . $cleanid . "\" />";
      foreach ($parameter[6] as $option_value => $option) {
        $run_result .= "<option value=\"" . htmlspecialchars(stripslashes($option_value), ENT_COMPAT, 'utf-8') . "\" ";
        if ($parameter[1] == $option_value) {
          $run_result .= " selected ";
        }
        $run_result .= " >$option</option>";

      }
      $run_result .= "</select><br>";
      break;
  }
  return $run_result;
}

/**
 * Function that replaces the '{{contact}}' keyword by the 'Send Message' link
 */
function messages_contact_user(){
  return run("messages:contact:link");
}

?>