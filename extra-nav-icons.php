<?php
/*
Plugin Name: Mystique Extra Navigation Icons
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
		$settings = get_option( 'mystique-extra-nav-icons' );
		if ( array_key_exists( $name, $settings ) )
			return $settings[$name];
		else
			return false;
	}

} /* end Mystique_Extra_Nav_Icons class */
endif;


$mystique_eni = new Mystique_Extra_Nav_Icons();
add_action( 'mystique_navigation_extra', array( &$mystique_eni, 'get_nav' ), 20 );

?>
