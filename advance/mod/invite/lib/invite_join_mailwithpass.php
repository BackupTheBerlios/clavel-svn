<?php
/*
 * invite_join_mailwithpass.php
 *
 * Created on Apr 19, 2007
 *
 * @author Diego Andr�s Ram�rez Arag�n <diego@somosmas.org>
 */
if(is_array($parameter)){
  $sitename = $parameter[0];
  $username = $parameter[1];
  $displaypassword = $parameter[2];
  $url = $parameter[3];
  $urlactivate = $parameter[4];

  $run_result= sprintf(__gettext("Thanks for joining %s!\n\nFor your records, your username and password in %s are:\n\n\t")
                                        .__gettext("Username: %s\n\tPassword: %s\n\n")
										.__gettext("To active your acount, please visit the following url: %s \n\n")
                                        .__gettext("We hope you enjoy using the system.\n\nRegards,\n\nThe %s Team")
                                ,$sitename,$sitename,$username,$displaypassword,$urlactivate,$url,$sitename);

}
?>
