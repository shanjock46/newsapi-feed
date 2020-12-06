<?php
/*
Plugin Name: News Api Feed
Description: News Api Feed Plugin
Version: 0.1
Author: Shanjock46
*/

if ( ! function_exists( 'add_action' ) ) {
	echo 'This plugin needs to be installed on WordPress';
	exit;
}

define( 'NEWSAPI_FEED_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'NEWSAPI_FEED_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'NEWSAPI_FEED_PLUGIN_PATH', parse_url( NEWSAPI_FEED_PLUGIN_URL, PHP_URL_PATH ) );
define( 'NEWSAPI_FEED_VERSION', '0.1' );

// We'll use this constant for script/style versions. "BUILD_NUM" is updated every Jenkins deploy.
define( 'NEWSAPI_FEED_BUILD', NEWSAPI_FEED_VERSION . ( defined( 'BUILD_NUM' ) ? '-' . BUILD_NUM : '' ) );

require_once( 'functions.php' );
require_once( 'classes/class-newsapi-feed.php' );

$NewsApiFeed = new NewsApiFeed();