<?php
global $etheme_theme_data;
$etheme_theme_data = wp_get_theme( 'idstore' );
define('ETHEME_DOMAIN', 'idstore');
define('ETHEME_OPTIONS', 'site_basic_options');
require_once( get_template_directory() . '/code/init.php' );

