<?php

    $comm_name = '';
    if (isset($_SESSION['comm_name'])) {
        $comm_name = $_SESSION['comm_name'];
    }
    $comm_username = '';
    if (isset($_SESSION['comm_username'])) {
        $comm_username = $_SESSION['comm_username'];
    }

    global $page_owner, $CFG, $USER;

    if (logged_on && $page_owner == $_SESSION['userid'] &&
        ($CFG->community_create_flag == "" || user_flag_get($CFG->community_create_flag, $USER->ident))) {

    $title = __gettext("Create a new community"); // gettext variable
    $communityName = __gettext("Community name:"); // gettext variable
    $buttonValue = __gettext("Create"); // gettext variable
    $description = __gettext("Description:"); // gettext variable
    $email = __gettext("Email -- Opcional:"); // gettext variable
    $city = __gettext("City -- Opcional:"); // gettext variable

    /*$fields = templates_draw(array('context' => 'databox1',
                                   'name' => $communityName,
                                   'column1' => "<input type=\"text\" name=\"comm_name\" value=\"$comm_name\" size=\"50\"/>"
                                   )
                            );*/


	$fields .= templates_draw(array(
                                   'context' => 'databox1',
                                   'name' => $communityName,
                                   'column1' => display_input_field(array("comm_name","","text"))
                            )
                            );

    $fields .= templates_draw(array(
                                   'context' => 'databox1',
                                   'name' => $description,
                                   'column1' => display_input_field(array("comm_description","","mediumtext"))
                            )
                            );

	$fields .= templates_draw(array(
                                   'context' => 'databox1',
                                   'name' => $email,
                                   'column1' => display_input_field(array("comm_email","","text"))
                            )
                            );

	$fields .= templates_draw(array(
                                   'context' => 'databox1',
                                   'name' => $city,
                                   'column1' => display_input_field(array("comm_city","","text"))
                            )
                            );

    $run_result .= templates_draw(array('context'=>"community_create",
                                        'title'=> $title,
                                        'form_fields'=> $fields,
                                        'button' => $buttonValue
                                        )
                                );

    }

?>