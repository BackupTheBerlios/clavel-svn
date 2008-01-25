<?php
/*
 * invite_functions.php
 *
 * Created on Apr 19, 2007
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 */
global $CFG;

 $sitename = sitename;
 $partOne = sprintf(__gettext("Please, complete the following form:")); // gettext variable
 //$terms = __gettext("terms and conditions"); // gettext variable
 //$privacy = __gettext("Privacy policy"); // gettext variable
 //$partFour = __gettext("When you fill in the details below, we will send an \"invitation code\" to your email address in order to validate it. You must then click on this within seven days to create your account."); // gettext variable

    $run_result .= <<< END

    <p>
        $partOne
    </p>
END;

?>
