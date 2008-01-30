<?php
global $CFG;
// Generate a new password

$sitename = sitename;

$code = trim(optional_param('passwordcode'));
if (!empty($code)) {
    if ($details = get_record_sql('SELECT pr.ident AS passcodeid,u.* FROM '.$CFG->prefix.'password_requests pr
                                   JOIN '.$CFG->prefix."users u ON u.ident = pr.owner
                                   WHERE pr.code = ? AND u.user_type = ?",array($code,'person'))) {
        
        $createpassword = sprintf(__gettext("Please, enter a new Password:"));
        $run_result .= <<< END
    
    <p>
        $createpassword
    </p>
        
			<form action="" method="post">
	
END;

				$run_result .= templates_draw(array(
                                            'context' => 'databoxvertical',
                                            'name' => __gettext("New Password"),
                                            'contents' => display_input_field(array("join_password1","","password"))
                    )
                	);
        		$run_result .= templates_draw(array(
                                            'context' => 'databoxvertical',
                                            'name' => __gettext("Confirm your new Password"),
                                            'contents' => display_input_field(array("join_password2","","password"))
                    )
                    );

		$id = $details->ident;
		$email = $details->email;
		$buttonValue = __gettext("Create");
            $run_result .= <<< END
			<p align="center">
                <input type="hidden" name="action" value="invite_create_password" />
				<input type="hidden" name="id" value=$id />
				<input type="hidden" name="email" value=$email />
                <input type="submit" value=$buttonValue />
            </p>			
    </form>
END;

        /*$validcharset = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz234567898765432";
        $newpassword = "";
        for ($i = 0; $i < 8; $i++) {
            $newpassword .= $validcharset[mt_rand(0, (strlen($validcharset) - 1))]; 
        }
        $newpassword = strtolower($newpassword);
        
        $sitename = sitename;
        
        email_to_user($details, null, sprintf(__gettext("Your %s password"), $sitename), sprintf(__gettext("Your %s password has been reset.\n\nFor your records, your new password is:\n\n\tPassword: %s\n\nPlease consider changing your password as soon as you have logged in for security reasons.\n\nWe hope you continue to enjoy using the system.\n\nRegards,\n\nThe %s Team"),$sitename, $newpassword, $sitename));
        $newpassword = md5($newpassword);
        set_field('users','password',$newpassword,'ident',$details->ident);
        delete_records('password_requests','owner',$details->ident);*/
        
    } else {
        
        $passwordDesc2 = __gettext("Your password request code appears to be invalid. Try generating a new one?");
        $run_result .= <<< END
    
    <p>
        $passwordDesc2
    </p>
    
END;
        
    }
    
} else {
    $passwordDesc3 = __gettext("Your password request code appears to be invalid. Try generating a new one?");
    $run_result .= <<< END
    
    <p>
        $passwordDesc3
    </p>
    
END;

}

?>