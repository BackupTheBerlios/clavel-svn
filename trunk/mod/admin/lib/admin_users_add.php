<?php

    // Add multiple users
    
        if (logged_on && user_flag_get("admin", $_SESSION['userid'])) {
            
            global $admin_users_add;
            for ($i = 0; $i < 12; $i++) {
                if (!isset($admin_users_add[$i])) {
                    //$admin_users_add[$i] = "";
                    $admin_users_add[$i]->username = "";
                    $admin_users_add[$i]->name = "";
					$admin_users_add[$i]->lastname = "";
                    $admin_users_add[$i]->email = "";
                }
            }
            
            $run_result .= "<p>" . __gettext("You can create up to 12 users below; passwords will be autogenerated and emailed to the account owners. You must include all fields, but you may leave rows blank.") . "</p>";
            $run_result .= "<form action=\"\" method=\"post\">";
            
            $name = "<h3>" . __gettext("Username") . "</h3>";
            $column1 = "<h3>" . __gettext("First name") . "</h3>";
			$column3 = "<h3>" . __gettext("Last Name") . "</h3>";
            $column2 = "<h3>" . __gettext("Email address") . "</h3>";
            
            for ($i = 0; $i < 12; $i++) {
                
                $tab = (($i + 1) * 3);
                
                $name .= "<input type=\"text\" name=\"new_username[$i]\" value=\"\" tabindex=\"".($tab)."\" value=\"".htmlspecialchars($admin_users_add[$i]->username, ENT_COMPAT, 'utf-8')."\" /><br />";
                $column1 .= "<input type=\"text\" name=\"new_name[$i]\" value=\"\" tabindex=\"".($tab + 1)."\" value=\"".htmlspecialchars($admin_users_add[$i]->name, ENT_COMPAT, 'utf-8')."\" /><br />";
				$column3 .= "<input type=\"text\" name=\"new_lastname[$i]\" value=\"\" tabindex=\"".($tab + 2)."\" value=\"".htmlspecialchars($admin_users_add[$i]->lastname, ENT_COMPAT, 'utf-8')."\" /><br />";
                $column2 .= "<input type=\"text\" name=\"new_email[$i]\" value=\"\" tabindex=\"".($tab + 3)."\" value=\"".htmlspecialchars($admin_users_add[$i]->email, ENT_COMPAT, 'utf-8')."\" /><br />";

            }    
            
            $name .= "<input type=\"hidden\" name=\"action\" value=\"admin:users:add\" />";
            $column1 .= "<br /><input type=\"submit\" value=\"" . __gettext("Add users") . "\" tabindex=\"39\" />";
            
            $run_result .= templates_draw(array(
                    'context' => 'adminTable',
                    'name' => $name,
                    'column1' => $column1,
					'column3' => $column3,
                    'column2' => $column2	
                )
                );
                
                
            $run_result .= "</form>";
            
        }

?>