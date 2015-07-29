<?php
/*
Plugin Name: TrailerAPI
Plugin URI: http://www.trailerapi.com/
Version: 1.2
Author: TrailerApi
Description: Add latest movie trailers to your WordPress site automatically or manually. You can select and customize these fields : Movie Name, Actor Names, IMDB Points, Producers, Film Poster, Movie Short Description, Movie Genre.
*/
$ltm_global_version="1.2";
define( 'LMT_PATH',plugin_dir_path( __FILE__ ) );
add_action( 'admin_menu', 'LMT_Menu' );
require_once( LMT_PATH . 'public/function.php' );
?>
