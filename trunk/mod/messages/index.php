<?php
/*
 * This script initialize the enviroment for show the message list
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos Más - 2007
 */
require_once (dirname(dirname(__FILE__)) . "/../includes.php");

run("profile:init");
run("messages:init");

define("context", "messages");
templates_page_setup();

$title = run("profile:display:name") . " :: " . __gettext("Messages");

$body = run("content:messages:view");
$body .= run("messages:view");

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