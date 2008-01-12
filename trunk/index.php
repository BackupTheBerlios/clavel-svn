<?php
    /** ELGG index.php
    *Show de main page depending if the user is logged on or is the administrator.
    *@author Johan Eduardo Quijano <gerencia@treszero.com>
    *@version 1.0
    */
    global $CFG;
    global $inviteFriend;
    global $showTags;
    global $showNewsLatinPyme;
    global $showFriends;

    require_once(dirname(__FILE__)."/includes.php");

/**
 * Function standar in all ELGG for organizate the page. 
 * @method logged_on
 */
    templates_page_setup();

    if (logged_on) {
/**
    * Special global variable: Show the ELGG's main body.
    * @global array $body
    * @uses context,frontpage_loggedin
    * @var array
    * CONTEXT is a global varible tha change depending the page where the user in surfing
    */
        $body = templates_draw(array(


                                        'context' => 'frontpage_loggedin'
                                )
                                );

} else {
        $body = templates_draw(array(
                                        'context' => 'frontpage_loggedout'
                                )
                                );
        $register = templates_draw(array(
                                        'context' => 'register_loggedout'
                                )
                                );

    
    }
/**
 * Templates_page_draw
 * @global array $CFG
 * @name 'sitename'
 */   
    echo templates_page_draw( array(
                    $CFG->sitename,
                    $body,
                    '_sidebar',
                    $register
            )
            );
    

?>