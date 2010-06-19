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
		'android' => '',
		'apple' => '',
		'beer' => '',
		'blogger' => '',
		'coffee' => '',
		'contact' => '',
		'dailymotion' => '',
		'delicious' => '',
		'digg' => '',
		'discogs' => '',
		'email' => '',
		'email_subscription' => '',
		'facebook' => '',
		'flickr' => '',
		'formspring' => '',
		'friendfeed' => '',
		'github' => '',
		'gmail' => '',
		'goodreads' => '',
		'googlebuzz' => '',
		'googlewave' => '',
		'hyves' => '',
		'identica' => '',
		'lastfm' => '',
		'linkedin' => '',
		'mirc' => '',
		'myspace' => '',
		'paypal' => '',
		'picasa' => '',
		'rss' => '',
		'skype' => '',
		'slideshare' => '',
		'soundcloud' => '',
		'sourceforge' => '',
		'spotify' => '',
		'steam' => '',
		'twitter' => '',
		'ubuntu' => '',
		'vimeo' => '',
		'wiki' => '',
		'windows_live' => '',
		'xing' => '',
		'yahoo' => '',
		'youtube' => '',
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


	function update_option( $key, $value ) {
		$settings = get_option( $this->plugin_slug );
		$settings[$key] = $value;
		update_option( $this->plugin_slug, $settings );
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

			/* Using registered $page handle to hook script load */
			add_action( 'admin_print_scripts-' . $page, array( &$this, 'admin_options_scripts' ) );
		}
	}


	/**
	 * Loads needed scripts for admin interface.
	 */
	function admin_options_scripts() {
		/* ui.sortable.js from jQuery UI v1.7.1 provided with WP <= 2.9.2 is
		 * buggy. So I'm using a custom version, backporting a fix to bug #4551 */
		wp_enqueue_script( 'menijQueryUIsortable', $this->plugin_url . '/js/ui.sortable.js', array( 'jquery-ui-core' ) );
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
		printf( "<div class='wrap'>\n<h2>%s</h2>", __( 'Mystique Extra Nav Icons settings', $this->plugin_slug ) );

		list( $en_order, $dis_order) = $this->get_ordered_list();

		echo "<ul id='meni_enabled_icons' class='iconSortable'>\n";
		// Get the visible smilies in the (eventually) custom order
		foreach ( $en_order as $icon => $url ) {
			echo "<li id='sttelement|$icon'><img src='{$this->plugin_url}/icons/nav-$icon.png'";
			echo " class='wp-smiley'";
			echo " alt='" . str_replace( "'", '&#039;', $text ) . "'";
			echo " title='" . str_replace( "'", '&#039;', $text ) . "'";
			echo " /></li>\n";
		}
		echo "</ul>\n";

		echo "<div class='meni_icon_settings'>";
		echo "<div id='meni_url_form'>";
		echo "<span></span>";
		echo "<input type='text' id='meni_url_textbox' />";
		echo "</div>";
		echo "<div id='meni_saving'>";
		echo "<img src='{$this->plugin_url}/ajax-loader.gif' alt='Loading' /> ";
		_e( 'Saving...' );
		echo "</div>";
		echo "</div>";


		echo "<br style='clear:both'/>\n";

		echo '<h3>' . __( "Disabled icons:", $this->plugin_slug ) . '</h3>';
		echo "<ul id='meni_disabled_icons' class='iconSortable'>\n";
		foreach ( $dis_order as $icon => $url ) {
			echo "<li id='sttelement|$icon'><img src='{$this->plugin_url}/icons/nav-$icon.png'";
			echo " class='wp-smiley'";
			echo " alt='" . str_replace( "'", '&#039;', $text ) . "'";
			echo " title='" . str_replace( "'", '&#039;', $text ) . "'";
			echo " /></li>\n";
		}
		echo "</ul>";


		?>
<style type="text/css">
	.iconSortable { list-style-type: none; margin: 10px 10px 20px; padding: 5px; width: 50%;min-height: 20px;}
	.iconSortable img {cursor: move;}
	.iconSortableSelected {background-color: #F2F4FF;border-color: #8d9dff !important;}
	#meni_enabled_icons {background-color: #cfc;border: 1px solid #8a8;float: left;}
	#meni_disabled_icons {background-color: #aaa;border: 1px solid #666;float: left;}
	#meni_enabled_icons li, #meni_disabled_icons li { margin: 3px 3px 3px 0; padding: 1px; display: block; float: left; border: 1px solid #ddd}
	#meni_url_form {padding: 5px;border: 1px solid #666;background-color: #ddd;}
	#meni_saving {visibility: hidden;}
	.meni_icon_settings {float: left; margin-top: 10px;margin-left: 20px;}
</style>
<script type="text/javascript">
/* <![CDATA[ */
var meni_urls = {
<?php
	$temp = array();
	$urls = $this->merge_urls_from_db();
	foreach ( $urls as $icon => $url ) {
		$temp[] = "$icon: '" . urlencode( $url ) . "'";
	}
	echo implode( ",\n", $temp);
?>
};
var meni_selected_icon;

(function($) {
	$("#meni_enabled_icons, #meni_disabled_icons").sortable({
		connectWith: '.iconSortable',
		stop: function (event, ui) {
			enabled_order = $("#meni_enabled_icons").sortable('toArray');
			disabled_order = $("#meni_disabled_icons").sortable('toArray');
			for (i = 0;i < enabled_order.length;i++) {
				enabled_order[i] = enabled_order[i].split('|')[1]
			}
			for (i = 0;i < disabled_order.length;i++) {
				disabled_order[i] = disabled_order[i].split('|')[1]
			}
			$("#meni_saving").css('visibility', '');
			$.ajax({
				type: 'post',
				url: 'admin-ajax.php',
				traditional: true,
				data: {
					'meni_enabled[]': enabled_order,
					'meni_disabled[]': disabled_order,
					action: 'meni_saveorder',
					_ajax_nonce: '<?php echo wp_create_nonce( 'meni_order' ) ?>'
				},
				success: function(data) {
					$("#meni_saving").css('visibility', 'hidden');
				}
			});
		}
	}).disableSelection();

	$("#meni_enabled_icons img, #meni_disabled_icons img").click(function() {
		$("#meni_enabled_icons li, #meni_disabled_icons li").removeClass('iconSortableSelected');
		$(this).parent().addClass('iconSortableSelected');
		var url = $(this).attr('src');
		meni_selected_icon = /\/nav-(.+)\.png$/.exec(url)[1];
		$("#meni_url_textbox").val(meni_urls[meni_selected_icon]);
		$(".meni_icon_settings span").html("");
	});

	$("#meni_url_textbox").keyup(function (e) {
		meni_urls[meni_selected_icon] = $("#meni_url_textbox").val();
	})
})(jQuery);
/* ]]> */
</script>
<?php
		echo '</div>';
	}


	function merge_urls_from_db() {
		$list = $this->default_icons;
		$saved_list = $this->get_option( 'urls' );
		if ( $saved_list && is_array( $saved_list ) ) {
			// I already have some settings saved
			foreach ( $saved_list as $key => $value ) {
				if ( array_key_exists( $key, $list ) )
					$list[$key] = $value;
			}
		}
		return $list;
	}


/**
	 * Returns the list of the icons. If a custom order is set, they will
	 * be returned in that order.
	 * 
	 * @return array Double list of visible + disabled icons:
	 * $result[0] = visible
	 * $result[1] = disabled
	 */
	function get_ordered_list() {
		// $result[0]: visible ones
		// $result[1]: disabled ones
		$result = array( array(), array() );
		$urls = $this->merge_urls_from_db();

		$en_order = $this->get_option( 'enabled_order' );
		// Have I saved the icon order into the database?
		if ( is_array( $en_order ) ) {
			/* Visible icons ($result[0]) */
			foreach ( $en_order as $code ) {
				if ( array_key_exists( $code, $urls ) )
					$result[0][$code] = $urls[$code];
			}

			/* Disabled icons ($result[1]) */
			$dis_order = $this->get_option( 'disabled_order' );
			if ( is_array( $dis_order ) ) {
				foreach ( $dis_order as $code ) {
					if ( array_key_exists( $code, $urls ) )
						$result[1][$code] = $urls[$code];
				}
			}
			/* Icons that aren't in any array (errors?)
			 * Added to disabled icons ($result[1]) */
			foreach ( $urls as $code => $url ) {
				if ( ! array_key_exists( $code, $result[0] ) &&
				     ! array_key_exists( $code, $result[1] ) ) {
					$result[1][$code] = $url;
				}
			}
			return $result;
		}
		else {
			// No order set. Return the default order.
			$result[1] = $urls;
			return $result;
		}
	}


/**
	 * Receives the order through Ajax, and saves it to the database.
	 */
	function save_ajax_order() {
		check_ajax_referer( 'meni_order' );
		if ( ! current_user_can( 'manage_options' ) ) die( '1' );

		$this->update_option( 'enabled_order', $_POST['meni_enabled'] );
		$this->update_option( 'disabled_order', $_POST['meni_disabled'] );
		die( '0' );
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


// Manage ajax communications when ordering icons (admin only)
add_action( 'wp_ajax_meni_saveorder', array( &$mystique_eni, 'save_ajax_order' ) );

?>
