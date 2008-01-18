<?php
/*
 * This script initialize the enviroment for view a message
 * @param int $message The msg Id
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos Más - 2007
 */
require_once (dirname(dirname(__FILE__)) ."/../includes.php");

run("profile:init");
run("messages:init");

define("context", "messages");
templates_page_setup();

$title = run("profile:display:name") . " :: " . __gettext("Messages");
$message = optional_param('message');

$msg = get_record('messages', 'ident', $message);

templates_page_setup();

$body = run("content:messages:detailedview");
$body .= run("messages:detailedview",$msg);

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