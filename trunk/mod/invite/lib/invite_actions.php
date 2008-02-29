<?php
global $USER;
global $CFG;

// Kill all old invitations
delete_records_select('invitations',"added < ?",array(time() - (86400 * 7)));

// Get site name
$sitename = $CFG->sitename;

$action = optional_param('action');

switch ($action) {
    // Add a new invite code
     case "invite_invite":
         $invite = new StdClass;
         $invite->name = trim(optional_param('invite_name'));
         $invite->email = trim(optional_param('invite_email'));
         if (!empty($invite->name) && !empty($invite->email)) {
             if (logged_on || ($CFG->publicinvite == true)) {
                 if (!maxusers_limit()) {
                     if (validate_email(stripslashes($invite->email))) {
                         $strippedname = stripslashes($invite->name); // for the message text.
                         $invitations = count_records('invitations','email',$invite->email);
                         if ($invitations == 0) {
                            if (!$account = get_record('users','email',$invite->email)) {
                              $invite->code = 'i' . substr(base_convert(md5(time() . $USER->username), 16, 36), 0, 7);
                              $invite->added = time();
                              $invite->owner = $USER->ident;
                              //insert_record('invitations',$invite);
                              $url = url . "mod/invite/register.php";
                              if (!logged_on) {
                                $invitetext = '';
                                $greetingstext = sprintf(__gettext("Thank you for registering with %s."),$sitename);
                                $subjectline = sprintf(__gettext("%s account verification"),$sitename);
                                $from_email = email;
                              } else {
                                $invitetext = trim(optional_param('invite_text'));
                                if (!empty($invitetext)) {
                                  $invitetext = __gettext("They included the following message:") . "\n\n----------\n" . $invitetext . "\n----------";
                                }
                                $greetingstext = $USER->name . " " . __gettext("has invited you to join") ." $sitename, ". __gettext("a learning landscape system.") ."";
                                $subjectline = $USER->name . " " . __gettext("has invited you to join") ." $sitename";
                                $from_email = $USER->email;
                              }
                              $emailmessage = sprintf(__gettext("Dear %s,\n\n%s %s\n\nTo join, visit the following URL:\n\n\t%s\n\nYour email address has not been passed onto any third parties.\n\nRegards,\n\nThe %s team."),$strippedname,$greetingstext,$invitetext,$url, $sitename);
                              $emailmessage = wordwrap($emailmessage);
                              $messages[] = sprintf(__gettext("Your invitation was sent to %s at %s."),$strippedname,$invite->email);
                              email_to_user($invite,null,$subjectline,$emailmessage);
                              if(INVITE_NO_RETURN_TO_REGISTER_PAGE){
                                $messages['invitation_success'] = "";
                              }
                            } else {
                              $messages[] = __gettext("The email address is already in use. Invitation not sent.");
                            }
                        } else {
                             $messages[] = __gettext("Someone with that email address has already been invited to the system. ");
                         }
                     } else {
                         $messages[] = __gettext("Invitation failed: The email address was not valid.");
                     }
                 } else {
                     $messages[] = __gettext("Error: This community has reached its maximum number of users.");
                 }
             } else {
                 $messages[] = __gettext("Invitation failed: you are not logged in.");
             }
         } else {
                 $messages[] = __gettext("Invitation failed: you must specify both a name and an email address.");
         }
         break;

         // Join using an invitation
     case "invite_join":
         $code = trim(optional_param('invitecode'));
		 $name = trim(optional_param('join_firstname'));
		 $lastname = trim(optional_param('join_lastname'));
		 $mail = trim(optional_param('join_email'));
         $username = trim(strtolower(optional_param('join_username')));
         $password1 = trim(optional_param('join_password1'));
         $password2 = trim(optional_param('join_password2'));
		 $accept = trim(optional_param('accept'));


         if (isset($name) && isset($code)) {
             if (maxusers_limit()) {
                 $messages[] = __gettext("Unfortunately this community has reached its account limit and you are unable to join at this time.");
                 break;
             }
             if (empty($name)) {
                $messages[] = __gettext('You must provide a first name.');
                break;
             }
			 if (empty($lastname)) {
                $messages[] = __gettext('You must provide a lastname.');
                break;
             }
			 if (empty($mail)) {
                $messages[] = __gettext('You must provide an email.');
                break;
             }
			 $mail = strtolower($mail);
             if (record_exists('users','email',$mail)) {
                 $messages[] = __gettext("The email '$mail' is already taken by another user. You will need to pick a different one.");
                 break;
             }
			 if (!validate_username($username)) {
                 $messages[] = __gettext("Your username must contain letters and numbers only, cannot be blank, and must be between 3 and 12 characters in length.");
                 break;
             }
             if (!username_is_available($username)) {
                 $messages[] = __gettext("The username '$username' is already taken by another user. You will need to pick a different one.");
                 break;
             }
			 if ($password1 != $password2 || strlen($password1) < 6 || strlen($password2) > 16) {
                 $messages[] = __gettext("Invalid password. Your passwords must match and be between 6 and 16 characters in length.");
                 break;
             }
			 /*if (!validate_password($password1, $password2)) {
                 $messages[] = __gettext("Invalid password. Your passwords must match and be between 6 and 16 characters in length.");
                 break;
             }*/ //Se comentó porque no hacia la comparación entre la contraseña y la verificación de la contraseña
             if (empty($accept)) {
                 $messages[] = __gettext("You must accept the Terms and Conditios to join.");
                 break;
             }
             /*if (!$details = get_record('invitations','code',$code)) {
                 $messages[] = __gettext("Error! Invalid invite code.");
                 break;
             }*/

			 $code = 'i' . substr(base_convert(md5(time() . $USER->username), 16, 36), 0, 7);

			 $no = "no"; //Para que por defecto el usuario no quede activado
             $displaypassword = $password1;
             $u = new StdClass;
             $u->name = $name;
			 $u->lastname = $lastname;
			 $u->email = $mail;
			 $u->username = $username;
			 $u->active = $no;
			 $u->code = $code;
             $u->password = md5($password1);
             $u = plugin_hook("user","create",$u);

			 //Ingreso de la base de datos para la activación
			 $invite->name = $name;
			 $invite->email = $mail;
			 $invite->code = $code;
             $invite->added = time();
             $invite->owner = $USER->ident;
             insert_record('invitations',$invite);
             $url = url . "mod/invite/join.php?invitecode=" . $invite->code;

             if (!empty($u)) {
                 $ident = insert_record('users',$u);
                 $u->ident = $ident;
                 //    Calendar code is in the wrong place!
                 global $function;
                 if(isset($function["calendar:init"])) {
                     $c = new StdClass;
                     $c->owner = $ident;
                     insert_record('calendar',$c);
                 }
                 $owner = (int)$details->owner;
                 if ($owner != -1) { // invited by someone - set up mutual friendship
                     $f = new StdClass;
                     $f->owner = $owner;
                     $f->friend = $ident;
                     insert_record('friends',$f);
                     $f->owner = $ident;
                     $f->friend = $owner;
                     insert_record('friends',$f);
                 }
                 // make them friend the news user

                 /*if(INVITE_AUTOADD_NEWS_FRIEND){
                   $f = new StdClass;
                   $f->owner = $ident;
                   $f->friend = 1;
                   insert_record('friends',$f);
                 }*/

                 $u = plugin_hook("user","publish",$u);

                 $rssresult = run("weblogs:rss:publish", array($ident, false));
                 $rssresult = run("files:rss:publish", array($ident, false));
                 $rssresult = run("profile:rss:publish", array($ident, false));
                 $_SESSION['messages'][] = __gettext("Your account was created! You can now log in using the username and password you supplied. You have been sent an email containing these details for reference purposes.");
                 //delete_records('invitations','code',$code);

                 if(INVITE_MAIL_CLEAR_PASSWORD===true){
				 echo "entra a este if";
                   $msg=run("invite:join:default:mailwithpass",array($sitename,$username,$displaypassword,url,$url));
                   if(array_key_exists("invite:join:mailwithpass",$function)){
                      $msg=run("invite:join:mailwithpass",array($sitename,$username,$displaypassword,url));
                    }
                   email_to_user($u,null,sprintf(__gettext("Your %s account"),$sitename),$msg);
                 }
                 else{
                   $msg=run("invite:join:default:mailwithoutpass",array($sitename,url));
                   if(array_key_exists("invite:join:mailwithoutpass",$function)){
                      $msg=run("invite:join:mailwithoutpass",array($sitename,$username,url));
                    }
                   email_to_user($u,null,sprintf(__gettext("Your %s account"),$sitename),$msg);
                 }

                 if(INVITE_AUTO_LOGIN){
                    // It would append the passthru_url to the default URL for the user
                    // http://yoursite.com/<redirect>
                    // You can use the following keywords to be replaced at this time
                    // {{username}} User name
                    // {{user_id}}  User id
                    $redirect_url = trim(optional_param('passthru_url','{{username}}'));
                    $redirect_url = str_replace('{{username}}',$username,$redirect_url);
                    $redirect_url = str_replace('{{user_id}}',$ident,$redirect_url);
                    $redirect_url = $CFG->wwwroot.$redirect_url;

                    $ok = authenticate_account($username, $displaypassword);
                    if ($ok) {
                        //$messages[] = __gettext("You have been logged on.");
                        if (md5($p) == md5("password")) {
                            $_SESSION['messages'][] = __gettext("The password for this account is extremely insecure and represents a major security risk. You should change it immediately.");
                        }
                        define('redirect_url', $redirect_url);
                        header("Location: " . redirect_url);
                        exit;
                    } else {
                        $messages[] = __gettext("Unrecognised username or password. The system could not log you on, or you may not have activated your account.");
                    }
                 }

                 header("Location: " . $CFG->wwwroot);
                 exit();

            }
         }
         break;

     // Request a new password
     case "invite_password_request":
         $email = optional_param('password_request_email');
         /*if(INVITE_ALLOW_EMAIL_BY_USERNAME){
           require_once $CFG->dirroot . "lib/validateurlsyntax.php";
           if(validateEmailSyntax($username)){
             if ($_username= get_field('users', 'username', 'email', $username)) {
               $username = $_username;
             }
           }
         }*/
         if (!empty($email)) {
             if ($user = get_record('users','email',trim($email),'user_type','person')) {
                 $pwreq = new StdClass;
                 $pwreq->code = 'i' . substr(base_convert(md5(time() . $username), 16, 36), 0, 7);
                 $pwreq->owner = $user->ident;
                 insert_record('password_requests',$pwreq);
                 $url = url . "newpassword/" . $pwreq->code;
                 email_to_user($user,null,sprintf(__gettext("Verify your %s account password request"),$sitename),
                               sprintf(__gettext("A request has been received to generate your account at %s a new password.\n\n")
               .__gettext("To confirm this request and receive a new password by email, please click the following link:\n\n\t%s\n\n")
                                               .__gettext("Please let us know if you have any further problems.\n\nRegards,\n\nThe %s Team")
                                       ,$sitename,$url,$sitename));
                 $messages[] = __gettext("Your verification email was sent. Please check your inbox.");
             } else {
                 $messages[] = __gettext("No user with that email was found.");
             }
         }
         break;

	// Request a new password
     case "invite_create_password":
         $password1 = trim(optional_param('join_password1'));
         $password2 = trim(optional_param('join_password2'));
		 $ident = trim(optional_param('id'));

         if (isset($password1) && isset($password2)) {

			 if ($password1 != $password2 || strlen($password1) < 6 || strlen($password2) > 16) {
                 $messages[] = __gettext("Error! Invalid password. Your passwords must match and be between 6 and 16 characters in length.");
                 break;
             }

			 $newpassword = strtolower($password1);
          	 $sitename = sitename;
			 $user = new StdClass;
             $user->ident = $ident;
			 $user->email = $mailaddress;

			 $mailuser = get_record('users','ident',trim($ident),'user_type','person');

        	 email_to_user($mailuser, null, sprintf(__gettext("Your %s password"), $sitename), sprintf(__gettext("Your %s password has been reset.\n\nFor your records, your new password is:\n\n\tPassword: %s\n\nPlease consider changing your password as soon as you have logged in for security reasons.\n\nWe hope you continue to enjoy using the system.\n\nRegards,\n\nThe %s Team"),$sitename, $newpassword, $sitename));
        	 $newpassword = md5($newpassword);
        	 set_field('users','password',$newpassword,'ident',$ident);
        	 //delete_records('password_requests','owner',$ident);
             $messages[] = __gettext("Your password has been changed. Check your email.");

         }

         break;
}

?>