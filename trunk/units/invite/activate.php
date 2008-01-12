<?php

global $CFG;
// Join

$sitename = sitename;
$textlib = textlib_get_instance();

$code = optional_param('invitecode');
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
        
    } else {
        
        $invalid = __gettext("Your invitation code appears to be invalid. Codes only last for seven days; it's possible that yours is older.");
        
        if ($CFG->publicreg) {
            $invalid .= sprintf(__gettext("If you still want to join %s, click the Register link."),$sitename);
        } else {
            $invalid .= sprintf(__gettext("If you still want to join %s, it may be worth getting in touch with the person who invited you."),$sitename);
        }
        
        
        $run_result .= <<< END
        
    <p>
        $invalid
    </p>
        
END;
        
    }
    
} else {
    if ($CFG->publicreg) {
        $invite = sprintf(__gettext("To join %s, click the Register link."),$sitename);
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