<?php

global $page_owner;
global $USER;

if ($page_owner != -1) {
    if (user_type($page_owner) == "person") {

        if ($result = get_records_select('users',"owner = ? AND user_type = ? Limit 5",array($page_owner,'community'))) {
            //$body = "<ul>";
            foreach($result as $row) {
                    $row->name = run("profile:display:name",$row->ident);
                    $body = "<a href=\"" . url . $row->username . "/\">" . $row->name . "<br></a>";
            }
            $body .= "<a href=\"" . url . $USER->username."/communities/owned" . "\"><br>"."[" . __gettext("All community") ."]". "<br><br></a>";;
            //$body .= "</ul>";
            // $run_result .= $body;
            //$run_result .= "<li id=\"community_owned\">";
            $run_result .= templates_draw(array(
                                                'context' => 'sidebarholder',
                                                'title' => __gettext("Owned communities"),
                                                'body' => $body
                                                )
                                          );
            //$run_result .= "</li>";
        } else {
            $run_result .= "";
        }
    }
}

?>