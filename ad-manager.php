<?php
/*
	Plugin Name: Advert Manage
	Plugin URI: http://admanager.williamwise.net/
	Description: The Wordpress Advert Manager plugin is a robust advert manager for Wordpress sites that allows you to control and distribute advertisements from a central dashboard.
	Author: Will Wise
	Version: 1.0.0
	Author URI: http://williamwise.net/
	License: GPLv2
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'ADMG_Plugin_Path', plugin_dir_path( __FILE__ ) );

include __DIR__ . "/admin/admin.php";
include __DIR__ . "/front-end/front-end.php";

/*
	Activate
*/
register_activation_hook( __FILE__, "admg_activate" );
function admg_activate() {
	include __DIR__ . "/install.php";
	admg_install();
}







