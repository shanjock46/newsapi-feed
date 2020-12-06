<?php

class NewsApiFeed {
	public $site_settings;

	public function __construct() {
		$this->init_requires();
		$this->actions();
	}

	/**
	 * To keep things organized, this is where we should do all includes/requires after the the initial one
	 */
	private function init_requires() {
		require_once( NEWSAPI_FEED_PLUGIN_DIR . 'widgets/widget-newsapi-post-feed.php' );
	}

	/**
	 * Keeping things organized.
	 */
	private function actions() {
		add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ], 8 );
		add_action( 'widgets_init', [ $this, 'widgets_init' ] );
	}

	/**
	 * Eventually will be used for all Angular + dependency scripts needed on non-apply pages
	 */
	public function register_scripts() {
		wp_register_style( 'newsapi-feed-main', NEWSAPI_FEED_PLUGIN_URL . 'css/main.css', [], NEWSAPI_FEED_BUILD );
		wp_enqueue_style( 'newsapi-feed-main' );
	}

	public function widgets_init() {
		register_widget( 'newsApiPostFeed' );
	}
}