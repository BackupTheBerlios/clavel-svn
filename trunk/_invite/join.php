<?php

    //    ELGG invite-a-friend page

    // Run includes
        define("context","external");
        require_once(dirname(dirname(__FILE__))."/includes.php");
        
        run("invite:init");
        templates_page_setup();
        
        $title = sprintf(__gettext("Your acount is active"));
        
        $body = run("content:invite:join");
        $body .= run("join:activate"); //Se cambia para que en vez de registro sea activación
        
        $body = templates_draw(array(
                        'context' => 'contentholder',
                        'title' => $title,
                        'body' => $body
                    )
                    );
        
        echo templates_page_draw( array(
                        $title, $body
                    )
                    );

?>