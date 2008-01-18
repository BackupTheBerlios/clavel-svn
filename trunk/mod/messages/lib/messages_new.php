<?php
/*
 * This script loads and append to $run_result the data for the specified message
 *
 * @param int $from The sender Id
 * @param int $to The recipient Id
 * @param int $msg_id The messge id (optional, used if the new message its a reply)
 * @param int $action If the message is a reply (optional)
 *
 * @uses $profile_id
 * @uses $CFG
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos Más - 2007
*/

global $CFG, $profile_id;

$from_param_id = optional_param('from', $profile_id, PARAM_INT);
$from_param_name = (isset ($from_param_id)) ? user_info('name', $from_param_id) : "";

$to_param_id = optional_param('to', -1, PARAM_INT);
if ($to_param_id == -1) {
  // If the 'to' param its not specified load the list of friends/communities
  $friends = get_records_select('friends', "owner=$profile_id", null, '', 'friend');
  if (is_array($friends)) {
    foreach ($friends as $friend) {
      $friend = get_record("users", "ident", $friend->friend);
      if ($friend) {
        if ($friend->user_type == "community" && MESSAGES_COMMUNITY_MESSAGES) {
          $to_param[$friend->ident] = $friend->name." " . __gettext("(Community)");
        }
        else if($friend->user_type != "community"){
          $to_param[$friend->ident] = $friend->name;
        }
      }
    }
  }
  else{
    $to_param = __gettext("No friends on your friends list to send to!");
  }
} else {
  $to_param = user_info('name', $to_param_id);
}

$msg_param = optional_param('msg_id', -1, PARAM_INT);
$msg = "";
$subject_param = "";
if ($msg_param != -1) {
  function prepend($string) {
    return "> $string";
  }
  $msg = get_record("messages", "ident", $msg_param);
  $subject_param = "Re: " . $msg->title;
  $msg_array = explode("\n", $msg->body);
  $msg_array = array_map("prepend", $msg_array);
  $msg = implode("\n<br>", $msg_array);
}

$action = optional_param('action');

$redirect = url . "mod/messages/messages_actions.php?action=compose";

// Initializing the label messages
$addMsg = __gettext("New message");
$from = __gettext("From:");
$to = __gettext("To:");
$subject = __gettext("Subject:");
$message = __gettext("Message:");
$submitButton = ($action == "reply") ? __gettext("Reply") : __gettext("Send");

$run_result =<<< END
<form method="post" name="elggform" action="$redirect" onsubmit="return submitForm();">
    <input type="hidden" name="new_msg_from" value="$from_param_id"/>
    <h2>$addMsg</h2>
END;

$run_result .= templates_draw(array (
  'context' => 'databoxvertical',
  'name' => $from,
  'contents' => $from_param_name
));

// @todo Mirar como se hace lo de los selects usando las funciones de ellos o si hay que hacerlo a manoplas
if (is_array($to_param)) {
  $run_result .= templates_draw(array (
    'context' => 'databoxvertical',
    'name' => $to,
    'contents' => display_input_field(array (
      "new_msg_to",
      "",
      "as_select",
      null,
      null,
      null,
      $to_param
    )
  )));
} else {
  $run_result .= "<input type=\"hidden\" name=\"new_msg_to\" value=\"$to_param_id\">";
  $run_result .= templates_draw(array (
    'context' => 'databoxvertical',
    'name' => $to,
    'contents' => $to_param
  ));
}

$run_result .= templates_draw(array (
  'context' => 'databoxvertical',
  'name' => $subject,
  'contents' => display_input_field(array (
    "new_msg_subject",
    $subject_param,
    "text"
  )
)));

$run_result .= templates_draw(array (
  'context' => 'databoxvertical',
  'name' => $message,
  'contents' => display_input_field(array (
    "new_msg_body",
    stripslashes($msg
  ),
  "weblogtext"
))));

$run_result .=<<< END
    <p>
        <input type="submit" value="$submitButton" />
    </p>
</form>
END;

?>