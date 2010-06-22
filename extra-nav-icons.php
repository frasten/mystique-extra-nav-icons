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

require_once( 'create-sprites.php' );

/* Internal data structure */
if ( ! class_exists( 'MENI_Icon' ) ):
class MENI_Icon {
	var $name;
	var $url;
	var $text;
	function MENI_Icon( $name, $url, $text ) {
		// Constructor
		$this->name = $name;
		$this->url = $url;
		$this->text = $text;
	}
}
endif;


if ( ! class_exists( 'Mystique_Extra_Nav_Icons' ) ):
class Mystique_Extra_Nav_Icons {
	var $plugin_slug = 'mystique-extra-nav-icons';
	var $plugin_url;
	var $default_icons;

	function init() {
		$dir = basename(dirname(__FILE__));
		$this->plugin_url = WP_PLUGIN_URL . "/$dir";
		$this->plugin_dir = WP_PLUGIN_DIR . "/$dir";

		$this->default_icons = array(
			new MENI_Icon( 'android', '', '' ),
			new MENI_Icon( 'apple', '', '' ),
			new MENI_Icon( 'beer', '', '' ),
			new MENI_Icon( 'blogger', 'http://YOUR_USERNAME.blogspot.com/', '' ),
			new MENI_Icon( 'coffee', '', '' ),
			new MENI_Icon( 'contact', '', '' ),
			new MENI_Icon( 'dailymotion', 'http://dailymotion.com/YOUR_USERNAME', '' ),
			new MENI_Icon( 'delicious', 'http://delicious.com/YOUR_USERNAME', '' ),
			new MENI_Icon( 'digg', 'http://digg.com/users/YOUR_USERNAME', '' ),
			new MENI_Icon( 'discogs', 'http://www.discogs.com/artist/YOUR_USERNAME', '' ),
			new MENI_Icon( 'email', 'mailto:YOUR_EM@IL.COM', '' ),
			new MENI_Icon( 'email_subscription', '', '' ),
			new MENI_Icon( 'facebook', 'http://www.facebook.com/YOUR_USERNAME', '' ),
			new MENI_Icon( 'flickr', 'http://www.flickr.com/photos/YOUR_USERNAME', '' ),
			new MENI_Icon( 'formspring', 'http://www.formspring.me/YOUR_USERNAME', '' ),
			new MENI_Icon( 'friendfeed', 'http://friendfeed.com/YOUR_USERNAME', '' ),
			new MENI_Icon( 'github', 'http://github.com/YOUR_USERNAME', '' ),
			new MENI_Icon( 'gmail', 'mailto:YOUR_USERNAME@gmail.com', '' ),
			new MENI_Icon( 'goodreads', 'http://www.goodreads.com/user/show/YOUR_USERID', '' ),
			new MENI_Icon( 'googlebuzz', 'http://www.google.com/profiles/YOUR_USERNAME', '' ),
			new MENI_Icon( 'googlewave', '', '' ),
			new MENI_Icon( 'hyves', 'http://YOUR_USERNAME.hyves.nl/', '' ),
			new MENI_Icon( 'identica', 'http://identi.ca/YOUR_USERNAME', '' ),
			new MENI_Icon( 'lastfm', 'http://www.lastfm.it/user/YOUR_USERNAME', '' ),
			new MENI_Icon( 'linkedin', 'http://linkedin.com/in/YOUR_PUBLIC_PROFILE', '' ),
			new MENI_Icon( 'mirc', '', '' ),
			new MENI_Icon( 'myspace', 'http://www.myspace.com/YOUR_USERNAME', '' ),
			new MENI_Icon( 'paypal', '', '' ),
			new MENI_Icon( 'picasa', 'http://picasaweb.google.it/YOUR_USERNAME', '' ),
			new MENI_Icon( 'rss', get_bloginfo( 'rss2_url' ), '' ),
			new MENI_Icon( 'skype', 'skype:YOUR_USERNAME', '' ),
			new MENI_Icon( 'slideshare', 'http://www.slideshare.net/YOUR_USERNAME', '' ),
			new MENI_Icon( 'soundcloud', 'http://soundcloud.com/YOUR_USERNAME', '' ),
			new MENI_Icon( 'sourceforge', 'http://YOUR_PROJECT.sourceforge.net/', '' ),
			new MENI_Icon( 'spotify', 'spotify:artist:YOUR_ID', '' ),
			new MENI_Icon( 'steam', '', '' ),
			new MENI_Icon( 'twitter', 'http://twitter.com/YOUR_USERNAME', '' ),
			new MENI_Icon( 'ubuntu', 'http://ubuntuforums.org/member.php?u=YOUR_USERID', '' ),
			new MENI_Icon( 'vimeo', 'http://vimeo.com/YOUR_USERNAME', '' ),
			new MENI_Icon( 'wiki', 'http://en.wikipedia.org/wiki/CHANGE_ME', '' ),
			new MENI_Icon( 'windows_live', 'http://YOUR_USERNAME.profile.live.com/', '' ),
			new MENI_Icon( 'xing', 'http://www.xing.com/profile/YOUR_USERNAME', '' ),
			new MENI_Icon( 'yahoo', 'http://pulse.yahoo.com/YOUR_USERID', '' ),
			new MENI_Icon( 'youtube', 'http://www.youtube.com/user/YOUR_USERNAME', '' ),
		);

		// FOR DEBUG PURPOSES:
		//delete_option($this->plugin_slug);
	}


	function get_nav( $nav_extra ) {
		// TODO: choose some default values to show
		list( $en_order, $dis_order) = $this->get_ordered_list();

		$nav_extra = '';
		for ( $i = sizeof( $en_order ) - 1; $i >= 0; $i-- ) {
			$icon = $en_order[$i];
			$nav_extra .= "<a href='" . esc_attr( $icon->url ) .
				"' class='nav-extra meni-{$icon->name}' title='" .
				esc_attr( $icon->text ) . "'><span>" .
				esc_html( $icon->text ) . "</span></a>\n";
		}

		return $nav_extra;
	}

// TODO: this should be removed
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

		echo '<h3>' . __( "Enabled icons:", $this->plugin_slug ) . '</h3>';
		echo "<ul id='meni_enabled_icons' class='iconSortable'>\n";
		// Get the visible icons in the (eventually) custom order
		// TODO: Avoid duplicating this, put it into a function
		foreach ( $en_order as $icon ) {
			echo "<li id='menielement|{$icon->name}'>";
			echo "<img src='{$this->plugin_url}/icons/nav-{$icon->name}.png'";
			// TODO: also update alt&title on change, if it's worthwhile
			echo " alt='" . esc_attr( $icon->text ) . "'";
			echo " title='" . esc_attr( $icon->text ) . "'";
			echo " /></li>\n";
		}
		echo "</ul>\n";

		echo "<div class='meni_icon_settings'>";
		echo "<div id='meni_url_form'>";
		echo "<span></span>";
		echo "<input type='text' id='meni_url_textbox' /><br/>";
		echo "<input type='text' id='meni_text_textbox' />";
		echo "</div>";
		echo "<div id='meni_saving'>";
		echo "<img src='{$this->plugin_url}/ajax-loader.gif' alt='Loading' /> ";
		_e( 'Saving...' );
		echo "</div>";
		echo "</div>";


		echo "<br style='clear:both'/>\n";

		echo '<h3>' . __( "Disabled icons:", $this->plugin_slug ) . '</h3>';
		echo "<ul id='meni_disabled_icons' class='iconSortable'>\n";
		foreach ( $dis_order as $icon ) {
			echo "<li id='menielement|{$icon->name}'>";
			echo "<img src='{$this->plugin_url}/icons/nav-{$icon->name}.png'";
			echo " alt='" . esc_attr( $icon->text ) . "'";
			echo " title='" . esc_attr( $icon->text ) . "'";
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
	#meni_enabled_icons li, #meni_disabled_icons li { margin: 3px 3px 3px 0; padding: 1px; display: block; float: left; border: 1px solid #ddd;border-radius: 4px;}
	#meni_url_form {padding: 5px;border: 1px solid #666;background-color: #ddd;}
	#meni_saving {display: none;margin-top: 4px;}
	.meni_icon_settings {float: left; margin-top: 10px;margin-left: 20px;width: 40%;}
	#meni_url_textbox, #meni_text_textbox {width: 100%;}
</style>
<script type="text/javascript">
/* <![CDATA[ */
var meni_icons = {
<?php
	$temp = array();
	$icons = $this->merge_icons_from_db();
	foreach ( $icons as $icon ) {
		$temp[] = "{$icon->name}: new Array('" . esc_attr( $icon->url ) . "', '" .
		  esc_attr( $icon->text ) . "')";
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
			$("#meni_saving").show();
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
					$("#meni_saving").hide();
				}
			});
		}
	}).disableSelection();

	$("#meni_enabled_icons img, #meni_disabled_icons img").click(function() {
		$("#meni_enabled_icons li, #meni_disabled_icons li").removeClass('iconSortableSelected');
		$(this).parent().addClass('iconSortableSelected');
		var url = $(this).attr('src');
		meni_selected_icon = /\/nav-(.+)\.png$/.exec(url)[1];
		$("#meni_url_textbox").val(meni_icons[meni_selected_icon][0]);
		$("#meni_text_textbox").val(meni_icons[meni_selected_icon][1]);
		meni_oldvalue[0] = meni_icons[meni_selected_icon][0];
		meni_oldvalue[1] = meni_icons[meni_selected_icon][1];
		$(".meni_icon_settings span").html("");
	});

	var meni_keypress_timeout;
	var meni_oldvalue = new Array('', '');
	$("#meni_url_textbox, #meni_text_textbox").keyup(function (e) {
		meni_icons[meni_selected_icon][0] = $("#meni_url_textbox").val();
		meni_icons[meni_selected_icon][1] = $("#meni_text_textbox").val();
		if (meni_keypress_timeout != undefined) {
			clearTimeout(meni_keypress_timeout);
		}
		meni_keypress_timeout = setTimeout(function() {
			if (meni_oldvalue[0] == meni_icons[meni_selected_icon][0] &&
			  meni_oldvalue[1] == meni_icons[meni_selected_icon][1]) {
				return;
			}
			meni_keypress_timeout = undefined;
			$("#meni_saving").show();
			$.ajax({
				type: 'post',
				url: 'admin-ajax.php',
				data: {
					action: 'meni_saveicons',
					_ajax_nonce: '<?php echo wp_create_nonce( 'meni_icons' ) ?>',
					name: meni_selected_icon,
					text: meni_icons[meni_selected_icon][1],
					url: meni_icons[meni_selected_icon][0]
				},
				success: function(data) {
					$("#meni_saving").hide();
					meni_oldvalue[0] = meni_icons[meni_selected_icon][0];
					meni_oldvalue[1] = meni_icons[meni_selected_icon][1];
				}
			});
		}, 500);


	})
})(jQuery);
/* ]]> */
</script>
<?php
		echo '</div>';
	}


	function merge_icons_from_db() {
		$output_list = $this->default_icons;
		$saved_list = $this->get_option( 'icons' );
		if ( $saved_list && is_array( $saved_list ) ) {
			// I already have some settings saved
			foreach ( $saved_list as $i => $icon ) {
				// Check if this icon is still valid
				foreach( $output_list as $j => $iconcheck ) {
					if ( $iconcheck->name == $icon->name ) {
						if ( ! empty( $icon->url ) ) $iconcheck->url = $icon->url;
						if ( ! empty( $icon->text ) ) $iconcheck->text = $icon->text;
						$output_list[$j] = $iconcheck;
						break;
					}
				}
			}
		}
		return $output_list;
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
		$icons = $this->merge_icons_from_db();
		$added_list = array();

		$en_order = $this->get_option( 'enabled_order' );
		// Have I saved the icon order into the database?
		if ( is_array( $en_order ) ) {
			/* Visible icons ($result[0]) */
			foreach ( $en_order as $name ) {
				for ( $i = 0; $i < sizeof( $icons ); $i++ ) {
					$icon = $icons[$i];
					if ( $icon->name == $name ) {
						$result[0][] = $icon;
						$added_list[] = $name;
						break;
					}
				}
			}

			/* Disabled icons ($result[1]) */
			$dis_order = $this->get_option( 'disabled_order' );
			if ( is_array( $dis_order ) ) {
				foreach ( $dis_order as $name ) {
					for ( $i = 0; $i < sizeof( $icons ); $i++ ) {
						$icon = $icons[$i];
						if ( $icon->name == $name ) {
							$result[1][] = $icon;
							$added_list[] = $name;
							break;
						}
					}
				}
			}
			/* Icons that aren't in any array (errors?)
			 * Added to disabled icons ($result[1]) */
			foreach ( $icons as $i => $icon ) {
				if ( ! in_array( $icon->name, $added_list ) ) {
					$result[1][] = $icon;
				}
			}
			return $result;
		}
		else {
			// No order set. Return the default order.
			$result[1] = $icons;
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
		// TODO: only call this when necessary (the icons have changed)
		// Note: if only the order has changed, it is not necessary to rebuild
		// the sprites.
		MENI_update_sprites();
		die( '0' );
	}


	/**
	 * Receives the urls through Ajax, and saves them to the database.
	 */
	function save_ajax_icons() {
		check_ajax_referer( 'meni_icons' );
		if ( ! current_user_can( 'manage_options' ) ) die( '1' );

		$icons = $this->merge_icons_from_db();
		$name = $_POST['name'];
		foreach ( $icons as $i => $icon ) {
			if ( $icon->name == $name ) {
				if ( ! empty( $_POST['url'] ) ) $icon->url = $_POST['url'];
				if ( ! empty( $_POST['text'] ) ) $icon->text = $_POST['text'];
				$icons[$i] = $icon;
				break;
			}
		}
		$this->update_option( 'icons', $icons );
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


	function load_sprites_css() {
		if ( is_file( $this->plugin_dir . '/sprites/sprite.css' ) ) {
			wp_enqueue_style( 'meni-sprites', $this->plugin_url . '/sprites/sprite.css' );
		}
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

// ... and when saving urls
add_action( 'wp_ajax_meni_saveicons', array( &$mystique_eni, 'save_ajax_icons' ) );

add_action( 'template_redirect', array( &$mystique_eni, 'load_sprites_css' ) );

?>
