<?php 

if ( ! defined( 'ABSPATH' ) ) exit;

global $admg_form;

switch( @$_REQUEST['action'] ) {
	case "update" :
		include __DIR__ . "/update.php";
		include __DIR__ . "/form.php";
	break;
	default :
		include __DIR__ . "/new.php";
		include __DIR__ . "/form.php";
	break;
}
?>