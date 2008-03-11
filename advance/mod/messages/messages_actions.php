<?php
/*
 * This script defines the actions avaible for the private messages plug-in.
 *
 * Actions avaible:
 *      - Delete    Allows to delete the specified message
 *                  Params:
 *                    $msg_id
 *                  Uses:
 *                    $USER
 *      - Compose   Allows to create a new message
 *                  Params:
 *                    $new_msg_from
 *                    $new_msg_to
 *                    $new_msg_subject
 *                    $new_msg_body
 *                  Uses:
 *                    $USER
 *      - Multiple  Allows to do the following operations over several messages:
 *                  (Mark as read | mark as unread | delete)
 *                  Params:
 *                    $message_action_type (read | unread | delete)
 *                    $selected array with the messages ids
 *                    $sent if the requirement comes from the sent messages list
 *                  Uses:
 *                    $USER
 *
 * @param string $action Action to be executed
 *
 * @uses $USER
 * @uses $CFG
 * @uses $messages
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos Más - 2007
*/

require_once (dirname(dirname(__FILE__)) . "/../includes.php");
require_once (dirname(__FILE__) . "/lib/messages_config.php");

/**
 * Deletes the specified message
 * @param int $msg Message id
 * @param int $user Current user id
 * @param boolean $sent If the message its a sent message or not
 */
function deleteMessage($msg, $user,$sent=0) {
  global $messages;
  if ($msg_info = get_record('messages', 'ident', $msg)) {
    if($sent){
      $msg_info->hidden_from = '1';
    }
    else{
      $msg_info->hidden_to = '1';
    }
    $msg_info->status = "read";
    update_record('messages', $msg_info);
    if ($msg_info->hidden_from && $msg_info->hidden_to) {
      delete_records('messages', 'ident', $msg);
    }
    $messages[] = __gettext("The selected message was deleted.");
  } else {
    $messages[] = __gettext("The message ID its not valid!.");
  }
  return $sent;
}

run("messages:init");

global $USER;
global $CFG;
// Actions to perform
$action = optional_param('action');
switch ($action) {
  case "delete" :
    $msg = optional_param('msg_id', 0, PARAM_INT);
    $sent = optional_param('sent',0,PARAM_INT);
    if (logged_on && !empty ($msg)) {
      $redirect_url = url . user_info('username', $USER->ident) . "/messages/";
      $sent = deleteMessage($msg, $USER->ident,$sent);
      if ($sent) {
        $redirect_url .= "sent";
      }
      define('redirect_url', $redirect_url);
    }
    break;

  case "compose" :
    $redirect_url = url . user_info('username', $_SESSION['userid']) . "/messages/compose";

    $from = optional_param('new_msg_from', -1, PARAM_INT);
    $to = optional_param('new_msg_to', -1, PARAM_INT);
    $subject = optional_param('new_msg_subject');
    $body = optional_param('new_msg_body');

    if ($from != -1 && $to != -1) {
      if (trim($body) != "" && trim($subject) != "") {
        $msg = new StdClass;
        $msg->from_id = trim($from);
        $msg->title = trim($subject);
        $msg->body = trim($body);
        $msg->posted = time();

        $recipients = array ();
        $recipient = get_record("users", "ident", $to);
       
        if($recipient->user_type == "community" && !MESSAGES_COMMUNITY_MESSAGES){
        	$messages[] = __gettext("The administrator doesn't allow send community messages");
          define('redirect_url', $redirect_url);
          break;
        }
        
        if ($recipient->user_type == "community") {
          $members = get_records_sql('SELECT u.*, f.ident AS friendident FROM ' . $CFG->prefix . 'friends f ' .
                                     'JOIN ' . $CFG->prefix . 'users u ON u.ident = f.owner ' .
                                     'WHERE f.friend = ? AND u.user_type = ?', array ($to,'person'));

          foreach ($members as $key => $infoMember) {
            $recipients[] = $key;
          }
        } else {
          $recipients[] = $to;
        }

        $msgs = 0;
        foreach ($recipients as $rcpt) {
          $msg->to_id = trim($rcpt);
          $msg->title = $subject;
          if ($recipient->user_type == "community") {
            $msg->title = "[" . $recipient->name . "] " . $subject;
          }
          $insert_id = insert_record('messages', $msg);
          if ($insert_id != -1) {
            $msgs++;
          }
          // Send the email confirmation if configured
          // Don't use the weblog plug-in hook because it inserts data in the messages table too
          $notifications = user_flag_get("emailnotifications", $msg->to_id);
          if ($notifications) {
            $email_from = new StdClass;
            $email_from->email = $CFG->noreplyaddress;
            $email_from->name = $CFG->sitename;
            $email_message = sprintf(__gettext("You have received a message from %s."), user_info("name", $msg->from_id));
            $email_message .= "\n\n";
            $email_message .= sprintf(__gettext("To reply, click here: %s"), $CFG->wwwroot . user_info("username", $msg->to_id) . "/messages/");
            $email_message = wordwrap($email_message);

            if ($email_to = get_record_sql("select * from " . $CFG->prefix . "users where ident = " . $msg->to_id)) {
              if (!email_to_user($email_to, $email_from, $msg->title, $email_message . "\n\n\n" . __gettext("You cannot reply to this message by email."))) {
                $messages[] = __gettext("Failed to send email. An unknown error occurred.");
              }
            }
          }
        }

        if ($msgs == count($recipients)) {
          $messages[] = __gettext("Your message was sent");
          $redirect_url = url . user_info('username', $USER->ident) . "/messages/sent";
        }

      } else {
        $messages[] = __gettext("You can't send an empty message!");
      }
    } else {
      $messages[] = __gettext("You must specify the sender a recipient!");
    }
    define('redirect_url', $redirect_url);
    break;
  case "multiple" :
    $action_type = optional_param('message_action_type', -1, PARAM_ALPHA);
    $selected = optional_param('selected');
    $sent = optional_param('sent');
    if (is_array($selected)) {
      foreach ($selected as $option) {
        $msg = get_record('messages', 'ident', $option);
        switch ($action_type) {
          case "read";
            if ($msg->status == "unread") {
              $msg->status = "read";
              update_record('messages', $msg);
            }
            break;
          case "unread";
            if ($msg->status == "read") {
              $msg->status = "unread";
              update_record('messages', $msg);
            }
            break;
          case "delete";
            deleteMessage($option, $USER->ident,$sent);
            break;
        }
      }
    }

    $redirect_url = url . user_info('username', $USER->ident) . "/messages/";
    if ($sent) {
      $redirect_url .= "sent";
    }
    define('redirect_url', $redirect_url);

    break;
}

if (defined('redirect_url')) {
  $_SESSION['messages'] = $messages;
  header("Location: " . redirect_url);
  exit;
}
?>