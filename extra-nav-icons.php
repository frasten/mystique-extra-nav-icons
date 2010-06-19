<?php
/*
Plugin Name: Mystique Extra Nav Icons
Plugin URI: http://polpoinodroidi.com/wordpress-plugins/mystique-extra-navigation-icons/
Description: 
Version: 0.0.1
Text Domain: mystique-extra-nav-icons
Author: Frasten
Author URI: http://polpoinodroidi.com
License: GPL3
*/

if ( ! class_exists( 'Mystique_Extra_Nav_Icons' ) ):
class Mystique_Extra_Nav_Icons {
	var $plugin_slug = 'mystique-extra-nav-icons';
	var $plugin_url;
	var $default_icons = array(
		'contact',
		'googlewave',
		'steam',
		'slideshare',
		'wiki',
		'myspace',
		'mirc',
		'coffee',
		'googlebuzz',
		'blogger',
		'xing',
		'formspring',
		'sourceforge',
		'ubuntu',
		'youtube',
		'twitter',
		'spotify',
		'discogs',
		'apple',
		'vimeo',
		'email',
		'flickr',
		'delicious',
		'beer',
		'skype',
		'digg',
		'email_subscription',
		'facebook',
		'android',
		'hyves',
		'picasa',
		'lastfm',
		'paypal',
		'rss',
		'github',
		'yahoo',
		'gmail',
		'soundcloud',
		'friendfeed',
		'linkedin',
		'goodreads',
		'identica',
		'dailymotion',
		'windows_live',
	);

	function init() {
		$dir = basename(dirname(__FILE__));
		$this->plugin_url = WP_PLUGIN_URL . "/$dir";
	}


	function get_nav( $nav_extra ) {
		$nav_extra = '';
		
		$nav_extra .= '<a href="'.get_bloginfo('rss2_url').'" class="nav-extra rss" title="RSS Feeds"><span>RSS Feeds</span></a>';
		return $nav_extra;
	}

	function get_css() {
		$css = $this->get_option( 'css' );
		if ( $css ) return $css;

		// TODO: If no CSS rule is already set, we generate some default settings
		/*
		 * #header p.nav-extra {top:-46px;height:54px;}
		#header a.nav-extra {height:60px;}
		#header a.nav-extra.rss{background:url("/wp-content/uploads/mystique_icons/nav-rss.png") no-repeat scroll right top transparent;}
	*/
	}

	function get_option( $name ) {
		$settings = get_option( $this->plugin_slug );
		if ( array_key_exists( $name, $settings ) )
			return $settings[$name];
		else
			return false;
	}

	/**
	 * Creates a link to the plugin's configuration page.
	 */
	function admin_menu() {
		if ( current_user_can( 'manage_options' ) ) {
			$menutitle = __( 'Mystique Nav Icons', $this->plugin_slug );
			$page = add_options_page(
			        __( 'Mystique Nav Icons', $this->plugin_slug ),
			        $menutitle,
			        'administrator', $this->plugin_slug,
			        array( &$this, 'options_page' )
			);
		}
	}

	/**
	 * Adds a link to the configuration page in the plugins list.
	 * 
	 * Directly called by filter 'plugin_action_links'.
	 */
	function add_action_link( $links, $file ) {
		/* create link */
		if ( $file == plugin_basename( __FILE__ ) ) {
			array_unshift(
				$links,
				sprintf( '<a href="%s">%s</a>', $this->plugin_options_url(), __( 'Settings' ) )
			);
		}

		return $links;
	}

	function options_page() {
		foreach ( $this->default_icons as $icon ) {
			echo "<img src='{$this->plugin_url}/icons/nav-$icon.png' />\n";
		}
	}

	/**
	 * Returns the url for the configuration page.
	 * 
	 * @return string The URL for the configuration page.
	 */
	function plugin_options_url() {
		// WP >= 3.0: use menu_page_url() if it exists.
		if ( function_exists('menu_page_url') )
			return menu_page_url( $this->plugin_slug, false );

		return admin_url( 'options-general.php?page=' . $this->plugin_slug );
	}

} /* end Mystique_Extra_Nav_Icons class */
endif;



$mystique_eni = new Mystique_Extra_Nav_Icons();

add_action( 'init', array( &$mystique_eni, 'init' ) );

// Mystique hooks
add_action( 'mystique_navigation_extra', array( &$mystique_eni, 'get_nav' ), 20 );


// Add settings menu to admin interface
add_action( 'admin_menu', array( &$mystique_eni, 'admin_menu' ) );
add_filter( 'plugin_action_links', array( &$mystique_eni, 'add_action_link' ) );

?>
