<?php

function MENI_update_sprites() {
	global $mystique_eni;

	$dirpath = $mystique_eni->plugin_dir . '/icons';
	if ( ! is_dir( $dirpath ) ) return false;
	if ( ! $mystique_eni->check_sprites_dir( false ) ) return false;

	// Read the .png files in the directory
	$icons = array();
	list( $en_order, ) = $mystique_eni->get_ordered_list();
	foreach ( $en_order as $icon ) {
		$icon_file = "$dirpath/nav-{$icon->name}.png";
		if ( ! is_file( $icon_file ) ) continue;
		$icon_files[] = array( $icon_file, $icon->name );
	}

	$num_icons = sizeof( $icon_files );
	if ( ! $num_icons ) return false;

	$row_size = 6;

	/* Put it into a bidimensional matrix. */
	$icon_matrix = array();
	$cur_row = -1;
	for ( $i = 0; $i < $num_icons; $i++ ) {
		if ( $i % $row_size == 0 ) {
			// Beginning of a new row
			$icon_matrix[] = array();
			$cur_row++;
		}
		$icon_matrix[$cur_row][] = $icon_files[$i];
	}


	/******** IMAGE CREATION **********/
	$icon_size = $mystique_eni->get_option( 'icon_size' );
	$icon_size = intval( $icon_size );
	if ( $icon_size <= 0 ) $icon_size = 64;
	if ( $icon_size > 150 ) $icon_size = 150;

	$columns = sizeof( $icon_matrix[0] );
	$rows = sizeof( $icon_matrix );
	$sprite_w = $icon_size * $columns;
	$sprite_h = $icon_size * $rows;


	$sprite = imagecreatetruecolor( $sprite_w, $sprite_h );
	// Set the background as transparent
	$transparent = imagecolorallocatealpha( $sprite, 0, 0, 0, 127 );
	imagefill( $sprite, 0, 0, $transparent );
	imagesavealpha( $sprite, true );

	$css = '';
	for ( $i = 0; $i < $rows; $i++ ) {
		for ( $j = 0; $j < $columns; $j++ ) {
			// The last row could be shorter
			if ( $j >= sizeof( $icon_matrix[$i] ) ) continue;
			$iconfile = $icon_matrix[$i][$j][0];
			if ( ! $iconfile ) continue;

			$img = imagecreatefrompng( $iconfile );
			$left = $icon_size*$j;
			$top = $icon_size*$i;
			imagecopyresampled( $sprite, $img, $left, $top, 0, 0, $icon_size, $icon_size, 64, 64);
			imagedestroy( $img );
			/***** CSS Stuff ***/
			#header a.nav-extra.rss{background:url("/wp-content/uploads/mystique_icons/nav-rss.png") no-repeat scroll right top transparent;}
			$name = $icon_matrix[$i][$j][1];
			$css .= "#header a.nav-extra.meni-$name {background:url('{$mystique_eni->sprites_url}/sprite.png') no-repeat scroll -{$left}px -{$top}px transparent;}\n";
		}
	}


	$spritefile = $mystique_eni->sprites_dir . '/sprite.png';
	imagepng( $sprite, $spritefile );
	imagedestroy( $sprite );
	// Adapt these sizes from 64px to a custom size.
	$h1 = round( 0.844 * $icon_size ); // 54px vs 64px
	$h2 = round( 0.937 * $icon_size ); // 60px vs 64px
	$h3 = round( -0.719 * $icon_size ); // -46px vs 64px
	$css = <<<EOF
#header p.nav-extra {top:{$h3}px;height:{$h1}px;}
#header a.nav-extra {height:{$h2}px;width:{$icon_size}px;}

EOF
	. $css;
	$cssfile = $mystique_eni->sprites_dir . '/sprite.css';

	/* Save the file */
	$f = @fopen($cssfile, 'w');
	if (!$f) {
		return false;
	} else {
		$bytes = fwrite($f, $css);
		fclose($f);
	}
}


?>
