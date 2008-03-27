<?php


//    ELGG join-with-no-invite page

// Run includes
define("context", "external");
require_once (dirname(dirname(__FILE__)) . "/../includes.php");

run("invite:init");
templates_page_setup();
if (!array_key_exists('invitation_success', $messages)) {
  $title= sprintf(__gettext("Contact us"));

  $body= <<< END
	<h3>Bogot&aacute;</h3>
	<h2>Sede Chapinero</h2>
	<p>
		<b>Representante:</b> Paula Rojas
		<br>
		<b>Direcci&oacute;n:</b> Calle 54 No. 10 - 39 Piso 7
		<br>
		<b>Celular:</b> 3006741465
	</p>
	<h2>Sede Fontib&oacute;n -- Zona Franca</h2>
	<p>
		<b>Representante:</b> Marisol Bustos
		<br>
		<b>Direcci&oacute;n:</b> Carrera 106 # 15A - 25 - Bodega 52A - Carrera 4 con Calle 6
		<br>
		<b>Tel&eacute;fono:</b> 7423171
		<br>
		<b>Celular:</b> 3114846573
	</p>
	<h3>Medell&iacute;n</h3>
	<p>
		<b>Representante:</b> Jaime Ram&iacute;rez
		<br>
		<b>Direcci&oacute;n:</b> Calle 65 No 55 - 46 Parque del emprendimiento, Parquesoft medellin
		<br>
		<b>Celular:</b> 2319181 Ext. 139
	</p>
END;

} else {
  $title= sprintf(__gettext("Welcome to %s"), sitename);

  if (array_key_exists("invite:register:welcome:success", $function)) {
    $body = run("invite:register:welcome:success");
  } else {
    $body = run("invite:register:default:welcome:success");
  }
}

templates_page_output($title, $body);

?>
