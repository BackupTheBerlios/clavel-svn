<?php

    global $CFG;
    // Join
        
        if ($CFG->publicreg == true) {
            
            $sitename = sitename;
            $partOne = sprintf(__gettext("Please, complete the following form:")); // gettext variable
            $terms = __gettext("Terms and Conditions"); // gettext variable
            $privacy = __gettext("Privacy Policy"); // gettext variable
            $partFour = __gettext("When you fill in the details below, we will send an \"invitation code\" to your email address in order to validate it. You must then click on this within seven days to create your account."); // gettext variable
			$checkterms = __gettext("Yes, I accept the Terms and Conditions and the Privacy Policy");
			$termsandconditions = __gettext("You must be read the");
			$and = __gettext("and");
                            
                $run_result .= <<< END
                
    <p>
        $partOne
    </p>

    <form action="" method="post">
                
END;
                
                $run_result .= templates_draw(array(
                                                'context' => 'databoxvertical',
                                                'name' => __gettext("First Name"),
                                                'contents' => display_input_field(array("join_firstname","","text"))
                    )
                    );
				$run_result .= templates_draw(array(
                                                'context' => 'databoxvertical',
                                                'name' => __gettext("Last Name"),
                                                'contents' => display_input_field(array("join_lastname","","text"))
                    )
                    );
                $run_result .= templates_draw(array(
                                                'context' => 'databoxvertical',
                                                'name' => __gettext("Email Address"),
                                                'contents' => display_input_field(array("join_email","","text"))
                    )
                    );
				$run_result .= <<< END
<br>
END;

				$run_result .= templates_draw(array(
                                                'context' => 'databoxvertical',
                                                'name' => __gettext("Username"),
                                                'contents' => display_input_field(array("join_username","","text"))
                    )
                    );
					
				$run_result .= templates_draw(array(
                                            'context' => 'databoxvertical',
                                            'name' => __gettext("Password"),
                                            'contents' => display_input_field(array("join_password1","","password"))
                    )
                	);
        		$run_result .= templates_draw(array(
                                            'context' => 'databoxvertical',
                                            'name' => __gettext("Confirm your Password"),
                                            'contents' => display_input_field(array("join_password2","","password"))
                    )
                    );
						
            $buttonValue = __gettext("Register");
            $run_result .= <<< END
			<ul>
				<li> $termsandconditions <a href="{$CFG->wwwroot}content/terms.php" target="_blank"> $terms</a> $and <a href="{$CFG->wwwroot}content/privacy.php" target="_blank">$privacy</a></li>
			</ul>
			<p align="center">
                <label for="acceptcheckbox"><input type="checkbox" id="acceptcheckbox" name="accept" value="yes" /> <strong>$checkterms</strong></label>
            </p>
            <p align="center">
                <input type="hidden" name="action" value="invite_join" />
                <input type="submit" value=$buttonValue />
            </p>
        </form>
                
END;
        } else {
            $nope = __gettext("Self-registration is currently disabled."); // gettext variable
            $run_result .= <<< END
    <p>
        $nope
    </p>
END;
        }

?>