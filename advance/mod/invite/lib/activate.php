<?php
/*
 * activate.php
 *
 * Created on Jan 25, 2008
 *
 * @author Andrea Ximena Bocanegra Soto <sistemas@treszero.com>
 */
global $CFG, $metatags;
// Join

$metatags .= <<< END
\n<script type="text/javascript">
         function keypressed() {
             var username = document.getElementById("id_join_username").value;
             document.getElementById("username_ok").src = '{$CFG->wwwroot}mod/invite/lib/check_username.php?username=' + username;
         }

         function setup() {
            document.getElementById("id_join_username").onkeyup = keypressed;
         }

         window.onload = setup; 
</script>
END;

$sitename = sitename;
$textlib = textlib_get_instance();

$code = optional_param('invitecode');
$registrationpage = "<a href=\"".$CFG->wwwroot."register\">".__gettext("register page")."</a>";
if (!empty($code)) {
    
if ($details = get_record('invitations','code',$code)) {

		$ident = get_field('users', 'ident', 'email', $details->email); //Obtiene el identificador del usuario

		$yes = "yes";
        //Activa la cuenta en la tabla de usuarios
		$user->active = $yes;
    	$user->ident = $ident;
    	update_record('users',$user);
		
        //$invite_id = (int) $details->ident;
        $thankYou = sprintf(__gettext("Thank you for check your Email Address. Now your acount is active.\n\nYou can loged in %s"),$sitename);
	
        $run_result .= <<< END
        
    <p>
        $thankYou
    </p>

END;
    }

} else {
    if ($CFG->publicreg) {
        $invite = sprintf(__gettext("To join %s, go to the %s."),$sitename,$registrationpage);
    } else {
        $invite = sprintf(__gettext("For the moment, joining %s requires a specially tailored invite code. If you know someone who's a member, it may be worth asking them for one."),$sitename);
    }
    $run_result .= <<< END

    <p>
        $invite
    </p>

END;

}

?>