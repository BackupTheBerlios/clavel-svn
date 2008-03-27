<?php
global $CFG, $db, $PAGE;

if (isset($parameter)) {

    if (!empty($PAGE->search_type_unformatted)) {
                $typeline = " AND user_type = " . $PAGE->search_type;
            } else {
                $typeline = "";
            }

    /*if ($CFG->dbtype == 'mysql') {
        $dbname = $db->qstr($parameter);
        $searchline = "SELECT DISTINCT name,lastname,username,MATCH(name) AGAINST (" . $dbname . ") AS score
                       FROM ".$CFG->prefix."users WHERE (MATCH(name) AGAINST (" . $dbname . ") > 0) $typeline LIMIT 20";
    } else {
        $dbname = $db->qstr("%" . $parameter . "%");
        $searchline = "SELECT DISTINCT name,username
                       FROM ".$CFG->prefix."users WHERE (name LIKE " . $dbname . ") $typeline LIMIT 20";
    }

    if ($results = get_records_sql($searchline)) {
        if ($PAGE->search_type_unformatted == "person") {
            $run_result .= "<h2>" . __gettext("Matching users:") . "</h2><p>";
        } else if ($PAGE->search_type_unformatted == "community") {
            $run_result .= "<h2>" . __gettext("Matching communities:") . "</h2><p>";
        } else {
            $run_result .= "<h2>" . __gettext("Matching users and communities:") . "</h2><p>";
        }
        foreach($results as $returned_name) {
            $run_result .= "<a href=\"" . url . $returned_name->username . '/">' . htmlspecialchars($returned_name->name." ".$returned_name->lastname) . "</a> <br />";
        }
        $run_result .= "</p>";

    }*/

	if ($CFG->dbtype == 'mysql') {
        $dbname = $db->qstr($parameter);
        $searchlineuser = "SELECT DISTINCT ident,name,lastname,username,MATCH(name) AGAINST (" . $dbname . ") AS score
                       FROM ".$CFG->prefix."users WHERE user_type='person' and (MATCH(name) AGAINST (" . $dbname . ") > 0) $typeline LIMIT 20";

        $searchlinecommunity = "SELECT DISTINCT ident,name,lastname,username,MATCH(name) AGAINST (" . $dbname . ") AS score
                       FROM ".$CFG->prefix."users WHERE user_type='community' and (MATCH(name) AGAINST (" . $dbname . ") > 0) $typeline LIMIT 20";

    } else {
        $dbname = $db->qstr("%" . $parameter . "%");
        $searchline = "SELECT DISTINCT name,username
                       FROM ".$CFG->prefix."users WHERE (name LIKE " . $dbname . ") $typeline LIMIT 20";
    }


	$run_result .= "<h2>" . __gettext("Matching users:") . "</h2><p>";

	if ($resultsuser = get_records_sql($searchlineuser)) {
        foreach($resultsuser as $returned_name_user) {
            $run_result .= "<a href=\"" . url . $returned_name_user->username . '/">' . htmlspecialchars($returned_name_user->name." ".$returned_name_user->lastname) . "</a> <br />";
        }
        $run_result .= "</p>";

    }

	$run_result .= "<h2>" . __gettext("Matching communities:") . "</h2><p>";

	if ($resultscommunity = get_records_sql($searchlinecommunity)) {
        foreach($resultscommunity as $returned_name_community) {
            $run_result .= "<a href=\"" . url ."mod/community/community_showdetails.php?profile_id=" . $returned_name_community->ident.'">' . htmlspecialchars($returned_name_community->name." ".$returned_name_community->lastname) . "</a> <br />";
        }
        $run_result .= "</p>";

    }

}

?>