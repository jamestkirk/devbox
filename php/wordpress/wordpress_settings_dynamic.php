<?php

// Configure the vars from Start Configuring to Stop Configuring.
// Drop this file in your blog web root and add the line below to the end of wp-config.php.
// require_once ABSPATH . 'wordpress_settings_dynamic.php';

class Config
{
	// Start Configuring
	public static $config = array(
		'development' => array(
			'servers' => array('domain.dev'),
			'web_root' => '/'
		),
		'staging' => array(
			'servers' => array('domain.com'),
			'web_root' => '/'
		)
	);
	private static $show_update_notice = false;
	// Stop Configuring

	private static $wp_config = array('siteurl', 'home', 'upload_path');

	private function __construct() {}

	public static function set($config = array(), $namespace = 'core')
	{
		foreach ($config as $k => $v)
		{
			self::$config[$namespace][$k] = $v;
		}
	}

	public static function set_core($host = NULL)
	{
		
		// Allows testing outside of browser by being able to pass host
		if (is_null($host))
			$host = $_SERVER['HTTP_HOST'];
		

		// Load $core settings into object
		foreach (self::$config as $name => $settings)
		{
			// Search server array to see if where we are matches, if true, then we know what settings to use
			if (in_array($host, $settings['servers']))
			{
				$settings['home'] = $settings['siteurl'] = rtrim($settings['web_root'], '/');
				$settings['upload_path'] = dirname(__FILE__) . '/wp-content/uploads';
				self::set($settings);
				self::set_wp($settings);
				return true;
			}
		}
		return false;
	}
	
	public static function set_wp($settings)
	{
		global $wpdb;

		$update = array();
		foreach (self::$wp_config as $v)
		{
			if (get_option($v) != Config::$config['core'][$v])
			{
				$update[$v] = Config::$config['core'][$v];
				update_option($v, Config::$config['core'][$v]);
			}
		}
		if (self::$show_update_notice && count($update))
		{
			echo '<h3>Updated Wordpress Configuration!</h3>
				<p><a href="">Refresh This Shit.</a></p>
				<pre>', print_r($update, true), '</pre>';
			exit;
		}
	}
}

Config::set_core();
