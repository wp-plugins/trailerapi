<?php
/*
Plugin Name: Last Movie Trailer
Plugin URI: http://www.trailerapi.com/
Version: 1.1
Author: Trailer Api
Description: Movie Trailer Add 
*/
define( 'LMT_PATH',plugin_dir_path( __FILE__ ) );
add_action( 'admin_menu', 'LMT_Menu' );
?>


<?php
require_once( LMT_PATH . 'public/function.php' );

?>
