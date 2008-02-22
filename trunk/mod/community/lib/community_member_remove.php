<?php


/*
 * community_member_remove.php
 *
 * Created on May 7, 2007
 *
 * @author Diego Andr�s Ram�rez Arag�n <diego@somosmas.org>
 * @copyright Corporaci�n Somos m�s - 2007
 */
global $USER;
global $CFG;

if (isset ($parameter)) {
  $friend_id= $parameter[0];
  $action= (count($parameter) >= 2) ? $parameter[1] : "";
  $user_id= (count($parameter) >= 3) ? $parameter[2] : $USER->ident;

  error_log("Elimiminando con default!");
  $run_result= array ();
  if (!empty ($friend_id) && logged_on) {
    switch ($action) {
      case "leave" :
      case "separate" :
        $community_name= user_info('name', $user_id);
        error_log("Eliminando owner = $friend_id friend=$user_id");
        if (delete_records("friends", "owner", $friend_id, "friend", $user_id)) {
          if ($action == "leave") {
            $run_result[]= sprintf(__gettext("You left %s"), $community_name);
            $_SESSION['messages']= $run_result;
            header("Location: " . $CFG->wwwroot . user_info('username', $user_id) . "/");
            exit;
          } else {
            $run_result[]= sprintf(__gettext("The user was removed from your community."));
          }
        } else {
          if ($action == "leave") {
            $run_result[]= sprintf(__gettext("You coundn't left %s"), $community_name);
          } else {
            $run_result[]= sprintf(__gettext("The user coundn't be removed from your community."));
          }
        }
        break;
      default :
        $community_name= user_info('name', $friend_id);
        $run_result[]= sprintf(__gettext("You left %s."), $community_name);
    }
  }
}
?>
