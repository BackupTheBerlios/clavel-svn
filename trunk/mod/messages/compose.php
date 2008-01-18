<?php
/*
 * This script initialize the enviroment for create a new message
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos Más - 2007
 */
require_once (dirname(dirname(__FILE__)) . "/../includes.php");

run("profile:init");
run("messages:init");

define("context", "messages");
templates_page_setup();

$title = run("profile:display:name") . " :: " . __gettext("Messages");

$body = run("content:messages:new");
$body .= run("messages:new");
// Needed for add the tinymce editor
$body .= run("messages:new:body",array(array("new_msg_body")));

$body = templates_draw(array (
  'context' => 'contentholder',
  'title' => $title,
  'body' => "<div id=\"view_own_blog\">" . $body . "</div>"
));

echo templates_page_draw(array (
  $title,
  $body
));
?>