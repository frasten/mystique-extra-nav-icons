#!/usr/bin/php
<?php
/* TODO: including all the images results in a huge sprite (280kB),
 * so I should include only the icons chosen by the user. */

$dirpath = 'icons';
$dirpath = realpath( $dirpath );


// Read the .png files in the directory
$handle = opendir( $dirpath );
$icon_files = array();
while ( false !== ( $file = readdir( $handle ) ) ) {
	if ( is_dir( "$dirpath/$file" ) ) continue;
	if ( ! preg_match( "/\.png$/i", $file ) ) continue;
	// Don't want to include this:
	if ( $file == 'nav-icons1.png' ) continue;
	$icon_files[] = "$dirpath/$file";
}
closedir($handle);

$num_icons = sizeof( $icon_files );
$row_size = 6;

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
$columns = sizeof( $icon_matrix[0] );
$rows = sizeof( $icon_matrix );
$sprite_w = 64 * $columns;
$sprite_h = 64 * $rows;


$sprite = imagecreatetruecolor( $sprite_w, $sprite_h );
// Set the background as transparent
$transparent = imagecolorallocatealpha( $sprite, 0, 0, 0, 127 );
imagefill( $sprite, 0, 0, $transparent );
imagesavealpha( $sprite, true );

for ( $i = 0; $i < $rows; $i++ ) {
	for ( $j = 0; $j < $columns; $j++ ) {
		// The last row could be shorter
		if ( $j >= sizeof( $icon_matrix[$i] ) ) continue;
		$iconfile = $icon_matrix[$i][$j];
		if ( ! $iconfile ) continue;

		$img = imagecreatefrompng( $iconfile );
		imagecopy( $sprite, $img, 64*$j, 64*$i, 0, 0, 64, 64);
		imagedestroy( $img );
	}
}
imagepng( $sprite, 'sprite.png' );
imagedestroy( $sprite );


?>
