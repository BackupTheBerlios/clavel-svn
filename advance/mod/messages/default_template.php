<?php
/*
 * This script configure the templates used for the private messages plug-in
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos Más - 2007
*/

// Add CSS
templates_add_context('css', 'mod/messages/css.css', true, true);

// Add page templates

templates_add_context('plug_messages',"mod/messages/templates/messages_messages.html");
templates_add_context('plug_message',"mod/messages/templates/messages_message.html");
templates_add_context('plug_detailedmessage',"mod/messages/templates/messages_detailed_message.html");

?>