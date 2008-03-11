<?php


/*
 * community_member_add.php
 *
 * Created on May 7, 2007
 *
 * @author Diego Andr�s Ram�rez Arag�n <diego@somosmas.org>
 * @copyright Corporaci�n Somos m�s - 2007
 */
 global $USER;

if (isset ($parameter)) {
  $friend_id= $parameter[0];
  if(!record_exists('friends','friend',$friend_id,'owner',$USER->ident)){
    $f = new StdClass;
    $f->owner = $USER->ident;
    $f->friend = $friend_id;
    insert_record('friends',$f);
  }
  $run_result= array ();
  if (!empty ($friend_id) && logged_on) {
    if (user_info("user_type", $friend_id) == "community") {
      if ($friend= get_record('users', 'ident', $friend_id)) {
        $owner= get_record('users', 'ident', $friend->owner);
        if ($friend->moderation == "no") {
          $run_result[]= sprintf(__gettext("You joined %s."), stripslashes($friend->name));
          if (user_flag_get("emailnotifications", $owner->ident)) {
            $message_body= sprintf(__gettext("%s has joined %s!\n\nTo visit this user's profile, click on the following link:\n\n\t" .
            "%s\n\nTo view all community members, click here:\n\n\t%s\n\nRegards,\n\nThe %s team."), $_SESSION['name'], $friend->name, $CFG->wwwroot . user_info("username", $USER->ident) . "/", $CFG->wwwroot . $friend->username . "/community/members", $CFG->sitename);
            $title= sprintf(__gettext("New %s member"), $friend->name);
            notify_user($owner->ident, $title, $message_body);
          }
        } else
          if ($friend->moderation == "yes") {
            $run_result[]= sprintf(__gettext("Membership of %s needs to be approved. Your request has been added to the list."), stripslashes($friend->name));
            if (user_flag_get("emailnotifications", $owner->ident)) {
              $message_body= sprintf(__gettext("%s has applied to join %s!\n\nTo visit this user's profile, click on the following link:\n\n\t" .
              "%s\n\nTo view all membership requests and approve or deny this user, click here:\n\n\t%s\n\nRegards,\n\nThe %s team."), $_SESSION['name'], $friend->name, $CFG->wwwroot . user_info("username", $USER->ident) . "/", $CFG->wwwroot . $friend->username . "/community/members", $CFG->sitename);
              $title= sprintf(__gettext("New %s member request"), $friend->name);
              notify_user($owner->ident, $title, $message_body);
            }
          } else
            if ($friend->moderation == "priv") {
              $run_result[]= sprintf(__gettext("%s is a private community. Your membership request has been declined."), stripslashes($friend->name));
            }
      }
    }
  }
}
?>
