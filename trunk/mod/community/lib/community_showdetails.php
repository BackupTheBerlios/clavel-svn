<?php

//    ELGG community details

// Run includes
require_once(dirname(dirname(__FILE__)) . '/includes.php');
require_once($CFG->dirroot . 'profile/profile.class.php');

$profile_name = optional_param('profile_name', '', PARAM_ALPHANUM);
if (!empty($profile_name)) {
    $profile_id = user_info_username('ident', $profile_name);
}
if (empty($profile_id)) {
    $profile_id = optional_param('profile_id', -1, PARAM_INT);
}
$page_owner = $profile_id;

//Show the details

$community = get_record('users','ident',$page_owner);
$community_name = htmlspecialchars(stripslashes(user_name($community->ident)), ENT_COMPAT, 'utf-8');
$community_owner = get_record('users','ident',$community->owner);
$community_nameowner = htmlspecialchars(stripslashes($community_owner->name." ".$community_owner->lastname), ENT_COMPAT, 'utf-8');
$community_details = get_record('community_details','owner',$page_owner);
$community_description = htmlspecialchars(stripslashes($community_details->description), ENT_COMPAT, 'utf-8');
$community_email = htmlspecialchars(stripslashes($community_details->email), ENT_COMPAT, 'utf-8');
$community_city = htmlspecialchars(stripslashes($community_details->city), ENT_COMPAT, 'utf-8');
$community_name_title = __gettext("Community Name");
$community_owner_title = __gettext("Owner");
$community_description_title = __gettext("Description");
$community_email_title = __gettext("Email");
$community_city_title = __gettext("City");

$run_result = <<< END

        <h2>$community_name_title</h2>
        <p>
            $community_name
        </p>

END;
$run_result .= <<< END

        <h2>$community_owner_title</h2>
        <p>
            $community_nameowner
        </p>

END;
$run_result .= <<< END

        <h2>$community_description_title</h2>
        <p>
            $community_description
        </p>

END;
$run_result .= <<< END

        <h2>$community_email_title</h2>
        <p>
            $community_email
        </p>

END;
$run_result .= <<< END

        <h2>$community_city_title</h2>
        <p>
            $community_city
        </p>

END;

//Comment wall

if (function_exists("commentwall_displayonprofile")) {

			$offset = optional_param('offset', 0);
			$limit = optional_param('limit', 3);
			$run_result .= commentwall_displayonprofile($page_owner, $limit, $offset);
}

$view = array();
$view['body'] = $run_result;

$run_result = '';

//$username = user_info('username',$this->id);
$run_result .= '<div id="profile_widgets">'."\n";
$run_result .= widget_page_display($page_owner,'profile',0,2);

$run_result .= "</div>\n";
$view['body'] .= $run_result;

$run_result .= $view['body'];

?>
