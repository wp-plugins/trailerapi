<?php
ob_start();
error_reporting(0);
/*

assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_BAIL, 1);
assert_options(ASSERT_QUIET_EVAL, 1);
error_reporting(E_ALL);
ini_set('display_errors', 1);
*/
require_once( ABSPATH . 'wp-includes/pluggable.php' );
//require_once( ABSPATH . 'wp-includes/functions.php ' );
require_once( ABSPATH . 'wp-admin/includes/taxonomy.php' );
require_once('lib.php' );
//require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
function css_and_js() {
wp_register_style('css', plugins_url('style.css',__FILE__ ));
wp_enqueue_style('css');
wp_register_script( 'ajax', plugins_url('ajax.js',__FILE__ ));
wp_enqueue_script('ajax');

}

add_action( 'admin_init','css_and_js');
function LTM_install() {
	global $wpdb;
    $ltm_options = $wpdb->prefix.'ltm_options';
	$ltm_trailer = $wpdb->prefix.'ltm_trailer';
	$charset_collate = $wpdb->get_charset_collate();

	$ltm_options_sql = "CREATE TABLE $ltm_options (
  id int(11) NOT NULL AUTO_INCREMENT,
  trailer int(11) NOT NULL DEFAULT '1',
  language varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  auto int(11) NOT NULL,
  embed_code text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  width int(11) NOT NULL,
  height int(11) NOT NULL,
  title text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  category varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  category_title text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  actor varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  actor_title text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  actorauto int(11) NOT NULL,
  year varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  year_title text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
   producer varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  producer_title text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  gender varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  gende_title text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  gendeauto int(11) NOT NULL,
  UNIQUE KEY id (id)
)  $charset_collate;";

	$ltm_trailer_sql = "CREATE TABLE $ltm_trailer (
  id int(11) NOT NULL AUTO_INCREMENT,
  movie_name text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  movie_poster varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  movie_description text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  movie_id varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  movie_year int(11) NOT NULL,
  movie_imdb varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  movie_cast text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  movie_producer text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  movie_genre text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  movie_status int(11) NOT NULL,
  add_time date NOT NULL,
  UNIQUE KEY id (id),
  UNIQUE KEY movie_id (movie_id)
) $charset_collate;";


require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	dbDelta( $ltm_options_sql );
	dbDelta( $ltm_trailer_sql );
	add_option('LTM_version','1.0');

	

	
	
	
/*	$option_sql='SELECT COUNT(*) FROM '.$ltm_options.' WHERE id=1 ';
		$wpdb->get_results($option_sql);
$optCount = $wpdb->num_rows;
if($optCount==0){ */
$wpdb->delete( $ltm_options, array( 'id' => 1 ), array( '%d' ) );
$wpdb->insert(  
	$ltm_options, 
	array(  
		'id'=>1,
		'trailer'=>0,
		'language' => 'en',	// string
		'embed_code' => '<!–nextpage–><!–baslik:Fragman–>%%embedcode%%',	// string
		'width' => 720,	// string
		'height' => 480,	// string
		'title' => '%%title%%  Watch',
		'category' => '',
		'category_title' => '%%tagtitle%% Movies Watch,%%tagtitle%% Movie,%%tagtitle%% Trailer',
		'actor' => '',
		'actor_title' => '%%actortitle%% Movies',
		'actorauto' => 0,
		'year' => '',
		'year_title' => '%%yeartitle%% Movies',
		'producer' => '',
		'producer_title' => '%%producertitle%% Movies',
		'gender' => '',
		'gende_title' => '%%gendetitle%% Movies',
		'gendeauto' => 0,
		'auto' => 0	
	), 
	array( 
	    '%d', //id
		'%d', //trailer
		'%s',	//language
		'%s',	//embed_code
		'%d',	//width
		'%d',	//height
		'%s',	//title
		'%s',	//category
		'%s',	//category_title
		'%s',	//actor
		'%s',	//actor_title
		'%d',   //actorauto
		'%s',	//year
		'%s',	//year_title
		'%s',	//producer
		'%s',	//producer_title
		'%s',	//gender
		'%s',	//gende_title
		'%d',	//gendeauto
		'%d'	//auto
	)

);
/*
}else{

}
*/
	//add_option( 'LTM_db_install', 'yes' );
}
$ltm_current_version=get_option('LTM_version');
if($ltm_global_version!=$ltm_current_version){
LTM_update();
}

if(!empty($_GET['repair'])){
LTM_drop();
}

function LTM_drop() {
	global $wpdb;
    $ltm_options = $wpdb->prefix.'ltm_options';
	$ltm_trailer = $wpdb->prefix.'ltm_trailer';
	$ltm_options_drop_sql="DROP TABLE ".$ltm_options;
	$ltm_trailer_drop_sql="DROP TABLE ".$ltm_trailer;
	$wpdb->query($ltm_options_drop_sql,ARRAY_A);
	$wpdb->query($ltm_trailer_drop_sql,ARRAY_A);
	delete_option('LTM_version');
	}
	
function LTM_installed() {
	global $wpdb;
    $ltm_options = $wpdb->prefix.'ltm_options';
	$ltm_trailer = $wpdb->prefix.'ltm_trailer';
	$ltm_options_control_sql="SHOW TABLES LIKE '%".$ltm_options."%'";
	$ltm_options_Count_array=$wpdb->get_results($ltm_options_control_sql,ARRAY_A);
		$ltm_trailer_control_sql="SHOW TABLES LIKE '%".$ltm_trailer."%'";
	$ltm_trailer_Count_array=$wpdb->get_results($ltm_trailer_control_sql,ARRAY_A);
    if ( empty($ltm_options_Count_array[0]) OR empty($ltm_trailer_Count_array[0])) {

        LTM_install();
    }else{
	if($ltm_options_Count_array[0] == ""){
	$GLOBALS['optionserror']=$ltm_options."- Database Table  Exits";
	}
	if($ltm_trailer_Count_array[0]==""){
	 $GLOBALS['optionserror']=$ltm_trailer."- Database Table  Exits";
	}
	}
}
add_action( 'plugins_loaded', 'LTM_installed' );

function LMT_Menu(){

	 	global $wpdb;
$ltm_options = $wpdb->prefix . 'ltm_options';
$user_options = $wpdb->get_results("SELECT * FROM {$ltm_options} WHERE id = 1", ARRAY_A);
$language_req='language_'.$user_options[0]['language'].'.php';
require_once($language_req);
if (wp_next_scheduled('ltm_movie_save')===false) {
$cron_menu=$GLOBALS['language']['cronmenu']."<span class='update-plugins count-1'><span class='update-count'>1</span></span>";
}else{
$cron_menu=$GLOBALS['language']['cronmenu'];
}
	add_menu_page('Admin', 'Last Movie Trailer', 'manage_options', 'LMT_admin', 'LMT_admin', plugin_dir_url( __FILE__ ).'icon-other.png' ); 
	add_submenu_page( 'LMT_admin', $GLOBALS['language']['adminsettings'],  $GLOBALS['language']['adminsettings'], 'manage_options', 'LMT_admin', 'LMT_admin');
	add_submenu_page( 'LMT_admin', $GLOBALS['language']['search'],  $GLOBALS['language']['search'], 'manage_options', 'LMT_search', 'LMT_search');
	add_submenu_page( 'LMT_admin', $GLOBALS['language']['addmovie'], $GLOBALS['language']['addmovie'], 'manage_options', 'LMT_add', 'LMT_add');
	add_submenu_page( 'LMT_admin',$GLOBALS['language']['addedmovie'], $GLOBALS['language']['addedmovie'], 'manage_options', 'LMT_added', 'LMT_added');
	add_submenu_page( 'LMT_admin', $GLOBALS['language']['notaddedmovie'], $GLOBALS['language']['notaddedmovie'], 'manage_options', 'LMT_notadd', 'LMT_notadd');
	add_submenu_page( 'LMT_admin', $cron_menu, $cron_menu, 'manage_options', 'LMT_cronlist', 'LMT_cronlist');
}

 if ( isset($_POST['settingsubmit'] ) ) {
 settingssave();
 }
  if ( isset($_POST['moviessubmit'] ) ) {
moviesave();
 }
   if ( isset($_POST['moviessearchsubmit'] ) ) {
moviesearchsave();
 }
 function moviesearchsave(){
	global $wpdb;
	  	$ltm_trailer = $wpdb->prefix . 'ltm_trailer';
			$ltm_options = $wpdb->prefix . 'ltm_options';
			$api_url=$_POST['api_url'];
			$wp_version=get_bloginfo('version');
$api_args = array(
    'timeout'     => 5,
    'redirection' => 5,
    'httpversion' => '1.0',
    'user-agent'  => 'WordPress/' . $wp_version . '; ' . get_bloginfo( 'url' ),
    'blocking'    => true,
    'headers'     => array(),
    'cookies'     => array(),
    'body'        => null,
    'compress'    => false,
    'decompress'  => true,
    'sslverify'   => true,
    'stream'      => false,
    'filename'    => null
); 
$api_url_content_array=wp_remote_get( $api_url,$api_args);
$api_url_content=$api_url_content_array['body'];
if(!$xml = simplexml_load_string($api_url_content))
{ 
}else{
if(empty($xml->error_status)){

	foreach( $xml as $film ) {
	if(!empty($_POST['movie_add_list'])){
    if(in_array($film->did,$_POST['movie_add_list'])) {
	$user_options = $wpdb->get_results("SELECT * FROM {$ltm_options} WHERE id = 1", ARRAY_A);
$ltm_trailer_sql= "SELECT movie_id FROM ".$ltm_trailer." WHERE movie_id='".$film->did."'";
	$wpdb->get_results($ltm_trailer_sql);
	$trailertCount = $wpdb->num_rows;
$movie_status=1;
	if($trailertCount==0){
$wpdb->insert( 
	$ltm_trailer, 
	array( 
		'movie_name' => $film->name,
		'movie_poster' => $film->poster,
		'movie_id' => $film->did,
		'movie_description' =>$film->description,
		'movie_year' =>$film->year,
		'movie_producer' =>$film->producer,
		'movie_imdb' =>$film->imdb,
		'movie_cast' =>$film->cast,
		'movie_genre' =>$film->genre,
		'movie_status' => $movie_status
	), 
	array( 
		'%s', //movie_name
		'%s', //movie_poster
		'%s', //movie_did
		'%s', //movie_description
		'%s', // movie_year
		'%s',// movie_producer
		'%s', //movie_imdb
		'%s', //movie_cast
		'%s', //movie_genre
		'%d' //movie_status
	) 
);

}

$movie_content='<iframe src="//www.dailymotion.com/embed/video/{embed}" width="{width}" height="{height}" frameborder="0" allowfullscreen="allowfullscreen"></iframe>';

$movie_content=str_replace("{embed}",$film->did,$movie_content);

$movie_content=str_replace("{width}",$user_options[0]['width'],$movie_content);

$movie_content=str_replace("{height}",$user_options[0]['height'],$movie_content);

$movie_content=str_replace("%%embedcode%%",$movie_content,$user_options[0]['embed_code']);
$movie_content=$movie_content."<br>".str_replace(array("%%title%%","%%cast%%","%%year%%"),array($film->name,$film->cast,$film->year),$user_options[0]['post_description']);
//$movie_content='[code language="html" ]'.$movie_content.'[/code]';

$movie_name=str_replace("%%title%%",$film->name,$user_options[0]['title']);
	$post = array(
     'post_author' => 1,
     'post_content' =>$movie_content,
     'post_status' => "future",
     'post_title' => $movie_name,
    // 'post_date'	=> date('Y-m-d H:i:s', strtotime(($time*45).' minutes +'.mt_rand(20, 200).' seconds')),
     // 'post_parent' => '',
     'post_type' => "post"
     );

	      $post_id = wp_insert_post( $post);
		 

$image_ext=substr( str_replace("-xlarge","",$film->poster), -4);
$image_ext=str_replace(".","",$image_ext);
$image_save_name=$film->did.".".$image_ext;
$file=image_save(str_replace("-xlarge","",$film->poster),$image_save_name);
$wp_filetype = wp_check_filetype( $file, null );
	$attachment = array(
    'post_mime_type' => $wp_filetype['type'],
    'post_title' => preg_replace('/.[^.]+$/', '', basename( $file) ),
    //'post_content' => '',
    //'post_author' => 1,
    //'post_status' => 'inherit',
    'post_type' => 'attachment',
    'post_parent' =>  $post_id,
    'guid' => $wp_upload_dir['url'] . '/' . basename( $file)
	);
require_once( ABSPATH . 'wp-admin/includes/image.php' );
	$attach_id = wp_insert_attachment( $attachment, $file);
	$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
wp_update_attachment_metadata( $attach_id, $attach_data );
$movie_tag=str_replace('%%tagtitle%%',$film->name,$user_options[0]['category_title']);
wp_set_post_tags($post_id,$movie_tag);
if(!empty($user_options[0]['gender'])){
if(taxonomy_exists($user_options[0]['gender'])){
if($user_options[0]['gendeauto']==1){
$movie_genre_array=explode(",",$film->genre);
for($g=0;$g<count($movie_genre_array);$g++){
$movie_genre=str_replace("%%gendetitle%%",$movie_genre_array[$g],$user_options[0]['gende_title']);
if($user_options[0]['gender']=="category"){
	if(is_category( $movie_genre)){
		$movie_genre_cat_id[]=get_cat_ID($movie_genre);
		wp_set_post_categories( $post_id,$movie_genre_cat_id) ;
	}else{
	
		$movie_genre_cat_id[]=  wp_create_category($movie_genre);
		wp_set_post_categories( $post_id,$movie_genre_cat_id) ;
	}
	}else{
 wp_set_post_terms( $post_id,$movie_genre , $user_options[0]['gender'] );
 }
}
}else{
$movie_genre=str_replace("%%gendetitle%%",$film->genre,$user_options[0]['gende_title']);	
 wp_set_post_terms( $post_id,$movie_genre, $user_options[0]['gender'] );
}

}else{
if($user_options[0]['gendeauto']==1){
$movie_genre_array=explode(",",$film->genre);
for($g=0;$g<count($movie_genre_array);$g++){
$movie_genre=str_replace("%%gendetitle%%",$movie_genre_array[$g],$user_options[0]['gende_title']);
add_post_meta( $post_id, $user_options[0]['gender'],$movie_genre );
}
}else{
add_post_meta( $post_id, $user_options[0]['gender'],$film->genre );
}
}
}else{

}
if(!empty($user_options[0]['year'])){
$movie_year=str_replace("%%yeartitle%%",$film->year,$user_options[0]['year_title']);
if(taxonomy_exists($user_options[0]['year'])){
if($user_options[0]['year']=="category"){
	if(is_category( $movie_year)){
		$movie_year_cat_id[]=get_cat_ID($movie_year);
		wp_set_post_categories( $post_id,$movie_year_cat_id) ;
	}else{
	
		$movie_year_cat_id[]=  wp_create_category($movie_year);
		wp_set_post_categories( $post_id,$movie_year_cat_id) ;
	}
	}else{
 wp_set_post_terms( $post_id,$movie_year , $user_options[0]['year'] );
 }
}else{
add_post_meta( $post_id, $user_options[0]['year'],$film->year);
}
}else{

}
if(!empty($user_options[0]['producer'])){
$movie_producer=str_replace("%%producertitle%%",$film->producer,$user_options[0]['producer_title']);
if(taxonomy_exists($user_options[0]['producer'])){
if($user_options[0]['producer']=="category"){
	if(is_category( $movie_producer)){
		$movie_producer_cat_id[]=get_cat_ID($movie_producer);
		wp_set_post_categories( $post_id,$movie_producer_cat_id) ;
	}else{
	
		$movie_producer_cat_id[]=  wp_create_category($movie_producer);
		wp_set_post_categories( $post_id,$movie_producer_cat_id) ;
	}
	}else{
 wp_set_post_terms( $post_id,$movie_producer , $user_options[0]['producer'] );
 }
}else{
add_post_meta( $post_id, $user_options[0]['producer'],$film->producer );
}
}else{

}
if(!empty($user_options[0]['actor'])){
if(taxonomy_exists($user_options[0]['actor'])){
if($user_options[0]['actorauto']==1){
$movie_genre_array=explode(",",$film->cast);
for($g=0;$g<count($movie_cast_array);$g++){
$movie_cast=str_replace("%%actortitle%%",$movie_cast_array[$g],$user_options[0]['actor_title']);
if($user_options[0]['actor']=="category"){
	if(is_category( $movie_cast)){
		$movie_cast_cat_id[]=get_cat_ID($movie_cast);
		wp_set_post_categories( $post_id,$movie_cast_cat_id) ;
	}else{
	
		$movie_cast_cat_id[]=  wp_create_category($movie_cast);
		wp_set_post_categories( $post_id,$movie_cast_cat_id) ;
	}
	}else{
 wp_set_post_terms( $post_id,$movie_cast, $user_options[0]['actor'] );
 }
}
}else{
 $movie_cast=str_replace("%%actortitle%%",$movie_cast_array[$g],$user_options[0]['actor_title']);
 wp_set_post_terms( $post_id,$movie_cast, $user_options[0]['actor'] );
}

}else{
if($user_options[0]['actorauto']==1){
$movie_cast_array=explode(",",$film->cast);
for($g=0;$g<count($movie_cast_array);$g++){
$movie_cast=str_replace("%%actortitle%%",$movie_cast_array[$g],$user_options[0]['actor_title']);
add_post_meta( $post_id, $user_options[0]['actor'],$movie_cast );
}
}else{
add_post_meta( $post_id, $user_options[0]['actor'],$film->cast);
}
}
}else{

}
set_post_thumbnail( $post_id, $attach_id );

	
}
}
if(!empty($_POST['movie_not_add_list'])){
if(in_array($film->did,$_POST['movie_not_add_list'])){
	$movie_status=2;
$wpdb->insert( 
	$ltm_trailer, 
	array( 
		'movie_name' => $film->name,
		'movie_poster' => $film->poster,
		'movie_id' => $film->did,
		'movie_description' =>$film->description,
		'movie_year' =>$film->year,
		'movie_producer' =>$film->producer,
		'movie_imdb' =>$film->imdb,
		'movie_cast' =>$film->cast,
		'movie_genre' =>$film->genre,
		'movie_status' => $movie_status
	), 
	array( 
		'%s', //movie_name
		'%s', //movie_poster
		'%s', //movie_did
		'%s', //movie_description
		'%s', // movie_year
		'%s',// movie_producer
		'%s', //movie_imdb
		'%s', //movie_cast
		'%s', //movie_genre
		'%d' //movie_status
	) 
);
}
}
}
}
}

 }
   if ( isset($_POST['reruncron'] ) ) {
ltm_movie_save();
 }
   function LMT_search() {
     global $wpdb;
	 $ltm_options = $wpdb->prefix . 'ltm_options';
	 	$ltm_trailer = $wpdb->prefix . 'ltm_trailer';
	 $user_options = $wpdb->get_results("SELECT * FROM {$ltm_options} WHERE id = 1", ARRAY_A);
 if ( isset($_POST['searchsubmit'] ) ) {
 $language_req='language_'.$user_options[0]['language'].'.php';
require_once($language_req);
$api_url='http://trailerapi.com/api/api.php?user_trailer_limit=1&language='.$user_options[0]['language'];
if(!empty($_POST['genresearch'])){
$api_url.='&genre='.$_POST['genresearch'];

}
if(!empty($_POST['imdbidsearch'])){
$api_url.='&imdbid='.$_POST['imdbidsearch'];
}
if(!empty($_POST['namesearch'])){
$api_url.='&name='.$_POST['namesearch'];
}
if(!empty($_POST['castsearch'])){
$api_url.='&cast='.$_POST['castsearch'];
}
if(!empty($_POST['first_datesearch'])){
$api_url.='&first_date='.$_POST['first_datesearch'];
}
if(!empty($_POST['last_datesearch'])){
$api_url.='&last_date='.$_POST['last_datesearch'];
}
if(!empty($_POST['orderbydsearch'])){
$api_url.='&order='.$_POST['orderbydsearch'];
}
if(!empty($_POST['yearsearch'])){
$api_url.='&year='.$_POST['yearsearch'];
}
$wp_version=get_bloginfo('version');
$api_args = array(
    'timeout'     => 5,
    'redirection' => 5,
    'httpversion' => '1.0',
    'user-agent'  => 'WordPress/' . $wp_version . '; ' . get_bloginfo( 'url' ),
    'blocking'    => true,
    'headers'     => array(),
    'cookies'     => array(),
    'body'        => null,
    'compress'    => false,
    'decompress'  => true,
    'sslverify'   => true,
    'stream'      => false,
    'filename'    => null
); 
$api_url_content_array=wp_remote_get( $api_url,$api_args);
$api_url_content=$api_url_content_array['body'];
if(!$xml = simplexml_load_string($api_url_content))
{ 
}else{
?>
<form action="<?php echo  $_SERVER['REQUEST_URI'] ;?>" method="post" enctype="multipart/form-data" id="add_form">
	<input type="submit"  name="moviessearchsubmit" value="<?php echo $GLOBALS['language']['selectsadd']; ?>" class="alladd">
<br/><input id="alladd_checked" name="alladd_checked" type="checkbox" onclick="add_checked()" /> <?php echo $GLOBALS['language']['checkalladd']; ?>
&nbsp;&nbsp;
<input id="allnotadd_checked" name="allnotadd_checked" type="checkbox" onclick="notadd_checked()" /> <?php echo $GLOBALS['language']['checknotalladd']; ?>
<input id="api_url" name="api_url" type="hidden" value="<?php echo $api_url;?>" >
<?php
if(empty($xml->error_status)){
	$movie_list_id=1;
	foreach( $xml as $film ) {
	$ltm_trailer_sql= "SELECT movie_id FROM ".$ltm_trailer." WHERE movie_id='".$film->did."'";
	$wpdb->get_results($ltm_trailer_sql);
	$trailertCount = $wpdb->num_rows;
	if($trailertCount==0){
	?>

<div class="trailer normal caption"><div class="poster"><img src="<?php echo str_replace("-xlarge","",$film->poster); ?>" alt="<?php echo $film->name;?>" border="0"></div>
<div class="description"><legend><span class="number"><?php echo $movie_list_id; ?></span><?php echo $film->name;?></legend><p><?php echo $film->description;?><br/>İmdb:<?php echo $film->imdb;?><br/><?php echo $GLOBALS['language']['producer'];?>:<?php echo $film->producer;?><br/><?php echo $GLOBALS['language']['cast']; ?>:<?php echo $film->cast;?></p><h3><input type="checkbox" id="addcheckbox" name="movie_add_list[]" value="<?php echo $film->did;?>"><?php echo $GLOBALS['language']['addmovietrailer']; ?>&nbsp;<input type="checkbox" id="notaddcheckbox" name="movie_not_add_list[]" value="<?php  echo $film->did;?>"> <?php echo $GLOBALS['language']['notaddmovietrailer']; ?></p><p>&nbsp;</h3></div></div>

<?php
$movie_list_id++;
}
	}
	}else{
	echo $xml->error_status;
	}

}
if($movie_list_id==1){
echo '<div>'.$GLOBALS['language']['nottingmovietrailer'].'</div>';
}
 }else{
 ?>
 <form action="<?php echo  $_SERVER['REQUEST_URI'] ;?>" method="post" enctype="multipart/form-data" >

 <table class="table">
 <tr><td  align="center"> <?php
 echo $GLOBALS['language']['genre'];
 ?></td>
 <td>
         <select id="genresearch" name="genresearch">
		  <option  value=""><?php echo  $GLOBALS['language']['selectgenre']; ?></option>
 <?php
$genre_array=array(
			"Action",
			"Adventure",
			"Animation",
			"Biography",
			"Comedy",
			"Crime",
			"Documentary",
			"Drama",
			"Family",
			"Fantasy",
			"Film-Noir",
			"History",
			"Horror",
			"Music",
			"Musical",
			"Mystery",
			"Romance",
			"Sci-Fi",
			"Sport",
			"Thriller",
			"War"
		);

 foreach( $genre_array as $genre_list){ 

 ?>

 <option  value="<?php echo  $genre_list; ?>"><?php echo  genre_translate($genre_list,$user_options[0]['language']); ?></option>
 <?php

   }
   ?>
   </td>
   </tr>
 <tr><td > İMDB id:</td><td ><input name="imdbidsearch" type="text" /><?php  echo $GLOBALS['language']['example'];  ?>:tt1234567</td></tr>
  <tr><td ><?php  echo $GLOBALS['language']['name'];  ?></td><td ><input name="namesearch" type="text" /></td></tr>
    <tr><td ><?php  echo $GLOBALS['language']['cast'];  ?></td><td ><input name="castsearch" type="text" /><?php  echo $GLOBALS['language']['example'];  ?>: Bruce Willis,Jessica Alba</td></tr> 
	 <tr><td ><?php  echo $GLOBALS['language']['year'];  ?></td><td ><input name="yearsearch" type="text" /><?php  echo $GLOBALS['language']['example'];  ?>: 2015,2014</td></tr>  
	<tr><td ><?php  echo $GLOBALS['language']['first_date'];  ?></td><td ><input name="first_datesearch" type="text" /><?php  echo $GLOBALS['language']['example'];  ?>: 2015-12-25</td></tr>
	<tr><td ><?php  echo $GLOBALS['language']['last_date'];  ?></td><td ><input name="last_datesearch" type="text" /><?php  echo $GLOBALS['language']['example'];  ?>: 2015-12-25</td></tr>
	 <tr><td > <?php  echo $GLOBALS['language']['orderby'];  ?>:</td><td ><input name="orderbydsearch" type="text" /><?php  echo $GLOBALS['language']['example'];  ?>:ASC <?php  echo $GLOBALS['language']['or'];  ?> DESC</td></tr>
   <tr><td colspan="2" align="center">
        <button type="submit" name="searchsubmit" ><?php  echo $GLOBALS['language']['search'];?></button>
		</td></tr>
   </table>
 </form>
   <?php
   }
   }
  function LMT_cronlist() {
	  $last_time=get_option('LTM_cron_time');
$current_time=time();
$difference_time=$current_time-$last_time;
$crons = _get_cron_array();
_set_cron_array($crons);
$cron_ltm_varible=0;
foreach( $crons as $cron_array){
if(empty($cron_array['ltm_movie_save'])){

}else{
foreach($cron_array['ltm_movie_save'] as $ltm_cron_array){
	$invertal=$ltm_cron_array['interval'];
}
$cron_ltm_varible=1;
}

}
 global $wpdb;
$ltm_options = $wpdb->prefix . 'ltm_options';
	$user_options = $wpdb->get_results("SELECT * FROM {$ltm_options} WHERE id = 1", ARRAY_A);
if($user_options[0]['auto']==1){
if($cron_ltm_varible==1){

if($difference_time>$invertal){
	$cron_message=$GLOBALS['language']['timeout'];
}else{
$cron_message=$GLOBALS['language']['runing'];
	
}
 

	?>
	<div class="listtitle"><?php echo $GLOBALS['language']['cronmenutitle']; ?></div>
	
<div align="center">
	<table class="table"><tr><td><?php echo $GLOBALS['language']['cronname']; ?></td><td><?php echo $GLOBALS['language']['status']; ?></td><td><?php echo $GLOBALS['language']['runtime']; ?></td><td><?php echo $GLOBALS['language']['action'] ?></td></tr><tr><td>LTM Cron Jobs</td><td><?php echo $cron_message; ?></td><td><?php echo dateto($difference_time,$invertal,5); ?></td><td><?php if($difference_time>$invertal){ ?><form action="<?php echo  $_SERVER['REQUEST_URI'] ;?>" method="post" enctype="multipart/form-data" class="form"><button type="submit" name="reruncron" ></form><?php echo $GLOBALS['language']['run']; ?></button><?php }else{ ?><span class="run"><?php echo $cron_message; ?></span><?php } ?></td></tr></table>
	</div>
	<?php
}else{
	?>
	<table class="table"><tr><td><?php echo $GLOBALS['language']['cronnotexits']; ?></td><td><form action="<?php echo  $_SERVER['REQUEST_URI'] ;?>" method="post" enctype="multipart/form-data" class="form"><button type="submit" name="reruncron" ><?php echo $GLOBALS['language']['run']; ?></button></form></td></tr></table>
	<?php
	
}
}else{ ?>
	<table class="table"><tr><td><?php echo $GLOBALS['language']['cronnotauto']; ?></td></tr></table>
<?php }
  }
 function ltm_movie_save() {
 
  global $wpdb;
	$ltm_trailer = $wpdb->prefix . 'ltm_trailer';
			$ltm_options = $wpdb->prefix . 'ltm_options';
			$times=time();
update_option('LTM_cron_time',$times);


if (wp_next_scheduled('ltm_movie_save')===false) {
wp_schedule_event( time(),'hourly', 'ltm_movie_save' );
	add_action( 'ltm_mintues_cron_hook', 'ltm_movie_save' ); 
}
			
	$user_options = $wpdb->get_results("SELECT * FROM {$ltm_options} WHERE id = 1", ARRAY_A);
	$movie_list_sql="SELECT * FROM ".$ltm_trailer." WHERE movie_status='0'";
$movie_list = $wpdb->get_results($movie_list_sql, ARRAY_A);


$movie_content='<iframe src="//www.dailymotion.com/embed/video/{embed}" width="{width}" height="{height}" frameborder="0" allowfullscreen="allowfullscreen"></iframe>';

$movie_content=str_replace("{embed}",$movie_list[0]['movie_id'],$movie_content);

$movie_content=str_replace("{width}",$user_options[0]['width'],$movie_content);

$movie_content=str_replace("{height}",$user_options[0]['height'],$movie_content);

$movie_content=str_replace("%%embedcode%%",$movie_content,$user_options[0]['embed_code']);
$movie_content=$movie_content."<br>".str_replace(array("%%title%%","%%cast%%","%%year%%"),array($movie_list[0]['movie_name'],$movie_list[0]['movie_cast'],$movie_list[0]['movie_year']),$user_options[0]['post_description']);

//$movie_content='[code language="html" ]'.$movie_content.'[/code]';

$movie_name=str_replace("%%title%%",$movie_list[0]['movie_name'],$user_options[0]['title']);
	$post = array(
     'post_author' => 1,
     'post_content' =>$movie_content,
     'post_status' => "future",
     'post_title' => $movie_name,
    // 'post_date'	=> date('Y-m-d H:i:s', strtotime(($time*45).' minutes +'.mt_rand(20, 200).' seconds')),
     // 'post_parent' => '',
     'post_type' => "post"
     );

	      $post_id = wp_insert_post( $post);
		 

$image_ext=substr( str_replace("-xlarge","",$movie_list[0]['movie_poster']), -4);
$image_ext=str_replace(".","",$image_ext);
$image_save_name=$movie_list[0]['movie_id'].".".$image_ext;
$file=image_save(str_replace("-xlarge","",$movie_list[0]['movie_poster']),$image_save_name);
$wp_filetype = wp_check_filetype( $file, null );
	$attachment = array(
    'post_mime_type' => $wp_filetype['type'],
    'post_title' => preg_replace('/.[^.]+$/', '', basename( $file) ),
    //'post_content' => '',
    //'post_author' => 1,
    //'post_status' => 'inherit',
    'post_type' => 'attachment',
    'post_parent' =>  $post_id,
    'guid' => $wp_upload_dir['url'] . '/' . basename( $file)
	);
require_once( ABSPATH . 'wp-admin/includes/image.php' );
	$attach_id = wp_insert_attachment( $attachment, $file);
	$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
wp_update_attachment_metadata( $attach_id, $attach_data );
$movie_tag=str_replace('%%tagtitle%%',$movie_list[0]['movie_name'],$user_options[0]['category_title']);
wp_set_post_tags($post_id,$movie_tag);
if(!empty($user_options[0]['gender'])){
if(taxonomy_exists($user_options[0]['gender'])){
if($user_options[0]['gendeauto']==1){
$movie_genre_array=explode(",",$movie_list[0]['movie_genre']);
for($g=0;$g<count($movie_genre_array);$g++){
$movie_genre=str_replace("%%gendetitle%%",$movie_genre_array[$g],$user_options[0]['gende_title']);
if($user_options[0]['gender']=="category"){
	if(is_category( $movie_genre)){
		$movie_genre_cat_id[]=get_cat_ID($movie_genre);
		wp_set_post_categories( $post_id,$movie_genre_cat_id) ;
	}else{
	
		$movie_genre_cat_id[]=  wp_create_category($movie_genre);
		wp_set_post_categories( $post_id,$movie_genre_cat_id) ;
	}
	}else{
 wp_set_post_terms( $post_id,$movie_genre , $user_options[0]['gender'] );
 }
}
}else{
$movie_genre=str_replace("%%gendetitle%%",$movie_list[0]['movie_genre'],$user_options[0]['gende_title']);	
 wp_set_post_terms( $post_id,$movie_genre, $user_options[0]['gender'] );
}

}else{
if($user_options[0]['gendeauto']==1){
$movie_genre_array=explode(",",$movie_list[0]['movie_genre']);
for($g=0;$g<count($movie_genre_array);$g++){
$movie_genre=str_replace("%%gendetitle%%",$movie_genre_array[$g],$user_options[0]['gende_title']);
add_post_meta( $post_id, $user_options[0]['gender'],$movie_genre );
}
}else{
add_post_meta( $post_id, $user_options[0]['gender'],$movie_list[0]['movie_genre'] );
}
}
}else{

}
if(!empty($user_options[0]['year'])){
$movie_year=str_replace("%%yeartitle%%",$movie_list[0]['movie_year'],$user_options[0]['year_title']);
if(taxonomy_exists($user_options[0]['year'])){
if($user_options[0]['year']=="category"){
	if(is_category( $movie_year)){
		$movie_year_cat_id[]=get_cat_ID($movie_year);
		wp_set_post_categories( $post_id,$movie_year_cat_id) ;
	}else{
	
		$movie_year_cat_id[]=  wp_create_category($movie_year);
		wp_set_post_categories( $post_id,$movie_year_cat_id) ;
	}
	}else{
 wp_set_post_terms( $post_id,$movie_year , $user_options[0]['year'] );
 }
}else{
add_post_meta( $post_id, $user_options[0]['year'],$movie_list[0]['movie_year'] );
}
}else{

}
if(!empty($user_options[0]['producer'])){
$movie_producer=str_replace("%%producertitle%%",$movie_list[0]['movie_producer'],$user_options[0]['producer_title']);
if(taxonomy_exists($user_options[0]['producer'])){
if($user_options[0]['producer']=="category"){
	if(is_category( $movie_producer)){
		$movie_producer_cat_id[]=get_cat_ID($movie_producer);
		wp_set_post_categories( $post_id,$movie_producer_cat_id) ;
	}else{
	
		$movie_producer_cat_id[]=  wp_create_category($movie_producer);
		wp_set_post_categories( $post_id,$movie_producer_cat_id) ;
	}
	}else{
 wp_set_post_terms( $post_id,$movie_producer , $user_options[0]['producer'] );
 }
}else{
add_post_meta( $post_id, $user_options[0]['producer'],$movie_list[0]['movie_producer'] );
}
}else{

}
if(!empty($user_options[0]['actor'])){
if(taxonomy_exists($user_options[0]['actor'])){
if($user_options[0]['actorauto']==1){
$movie_genre_array=explode(",",$movie_list[0]['movie_cast']);
for($g=0;$g<count($movie_cast_array);$g++){
$movie_cast=str_replace("%%actortitle%%",$movie_cast_array[$g],$user_options[0]['actor_title']);
if($user_options[0]['actor']=="category"){
	if(is_category( $movie_cast)){
		$movie_cast_cat_id[]=get_cat_ID($movie_cast);
		wp_set_post_categories( $post_id,$movie_cast_cat_id) ;
	}else{
	
		$movie_cast_cat_id[]=  wp_create_category($movie_cast);
		wp_set_post_categories( $post_id,$movie_cast_cat_id) ;
	}
	}else{
 wp_set_post_terms( $post_id,$movie_cast, $user_options[0]['actor'] );
 }
}
}else{
 $movie_cast=str_replace("%%actortitle%%",$movie_cast_array[$g],$user_options[0]['actor_title']);
 wp_set_post_terms( $post_id,$movie_cast, $user_options[0]['actor'] );
}

}else{
if($user_options[0]['actorauto']==1){
$movie_cast_array=explode(",",$movie_list[0]['movie_cast']);
for($g=0;$g<count($movie_cast_array);$g++){
$movie_cast=str_replace("%%actortitle%%",$movie_cast_array[$g],$user_options[0]['actor_title']);
add_post_meta( $post_id, $user_options[0]['actor'],$movie_cast );
}
}else{
add_post_meta( $post_id, $user_options[0]['actor'],$movie_list[0]['movie_cast'] );
}
}
}else{

}
set_post_thumbnail( $post_id, $attach_id );
	$ltm_trailer = $wpdb->prefix . 'ltm_trailer';
		$wpdb->update( 
	$ltm_trailer, 
	array( 
		'movie_status' => 1
	), 
	array( 'id' => $movie_list[0]['id'] ), 
	array( 
		'%d'
	), 
	array( '%d' ) 
);
unset($movie_list);

}
	
 function moviesave(){
	global $wpdb;
	  	$ltm_trailer = $wpdb->prefix . 'ltm_trailer';
			$ltm_options = $wpdb->prefix . 'ltm_options';
	if(!empty($_POST['movie_add_list'])) {
    foreach($_POST['movie_add_list'] as $addcheck) {
	$user_options = $wpdb->get_results("SELECT * FROM {$ltm_options} WHERE id = 1", ARRAY_A);
	$movie_list_sql="SELECT * FROM ".$ltm_trailer." WHERE id =".$addcheck;
$movie_list = $wpdb->get_results($movie_list_sql, ARRAY_A);


$movie_content='<iframe src="//www.dailymotion.com/embed/video/{embed}" width="{width}" height="{height}" frameborder="0" allowfullscreen="allowfullscreen"></iframe>';

$movie_content=str_replace("{embed}",$movie_list[0]['movie_id'],$movie_content);

$movie_content=str_replace("{width}",$user_options[0]['width'],$movie_content);

$movie_content=str_replace("{height}",$user_options[0]['height'],$movie_content);

$movie_content=str_replace("%%embedcode%%",$movie_content,$user_options[0]['embed_code']);
$movie_content=$movie_content."<br>".str_replace(array("%%title%%","%%cast%%","%%year%%"),array($movie_list[0]['movie_name'],$movie_list[0]['movie_cast'],$movie_list[0]['movie_year']),$user_options[0]['post_description']);
//$movie_content='[code language="html" ]'.$movie_content.'[/code]';

$movie_name=str_replace("%%title%%",$movie_list[0]['movie_name'],$user_options[0]['title']);
	$post = array(
     'post_author' => 1,
     'post_content' =>$movie_content,
     'post_status' => "future",
     'post_title' => $movie_name,
    // 'post_date'	=> date('Y-m-d H:i:s', strtotime(($time*45).' minutes +'.mt_rand(20, 200).' seconds')),
     // 'post_parent' => '',
     'post_type' => "post"
     );

	      $post_id = wp_insert_post( $post);
		 

$image_ext=substr( str_replace("-xlarge","",$movie_list[0]['movie_poster']), -4);
$image_ext=str_replace(".","",$image_ext);
$image_save_name=$movie_list[0]['movie_id'].".".$image_ext;
$file=image_save(str_replace("-xlarge","",$movie_list[0]['movie_poster']),$image_save_name);
$wp_filetype = wp_check_filetype( $file, null );
	$attachment = array(
    'post_mime_type' => $wp_filetype['type'],
    'post_title' => preg_replace('/.[^.]+$/', '', basename( $file) ),
    //'post_content' => '',
    //'post_author' => 1,
    //'post_status' => 'inherit',
    'post_type' => 'attachment',
    'post_parent' =>  $post_id,
    'guid' => $wp_upload_dir['url'] . '/' . basename( $file)
	);
require_once( ABSPATH . 'wp-admin/includes/image.php' );
	$attach_id = wp_insert_attachment( $attachment, $file);
	$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
wp_update_attachment_metadata( $attach_id, $attach_data );
$movie_tag=str_replace('%%tagtitle%%',$movie_list[0]['movie_name'],$user_options[0]['category_title']);
wp_set_post_tags($post_id,$movie_tag);
if(!empty($user_options[0]['gender'])){
if(taxonomy_exists($user_options[0]['gender'])){
if($user_options[0]['gendeauto']==1){
$movie_genre_array=explode(",",$movie_list[0]['movie_genre']);
for($g=0;$g<count($movie_genre_array);$g++){
$movie_genre=str_replace("%%gendetitle%%",$movie_genre_array[$g],$user_options[0]['gende_title']);
if($user_options[0]['gender']=="category"){
	if(is_category( $movie_genre)){
		$movie_genre_cat_id[]=get_cat_ID($movie_genre);
		wp_set_post_categories( $post_id,$movie_genre_cat_id) ;
	}else{
	
		$movie_genre_cat_id[]=  wp_create_category($movie_genre);
		wp_set_post_categories( $post_id,$movie_genre_cat_id) ;
	}
	}else{
 wp_set_post_terms( $post_id,$movie_genre , $user_options[0]['gender'] );
 }
}
}else{
$movie_genre=str_replace("%%gendetitle%%",$movie_list[0]['movie_genre'],$user_options[0]['gende_title']);	
 wp_set_post_terms( $post_id,$movie_genre, $user_options[0]['gender'] );
}

}else{
if($user_options[0]['gendeauto']==1){
$movie_genre_array=explode(",",$movie_list[0]['movie_genre']);
for($g=0;$g<count($movie_genre_array);$g++){
$movie_genre=str_replace("%%gendetitle%%",$movie_genre_array[$g],$user_options[0]['gende_title']);
add_post_meta( $post_id, $user_options[0]['gender'],$movie_genre );
}
}else{
add_post_meta( $post_id, $user_options[0]['gender'],$movie_list[0]['movie_genre'] );
}
}
}else{

}
if(!empty($user_options[0]['year'])){
$movie_year=str_replace("%%yeartitle%%",$movie_list[0]['movie_year'],$user_options[0]['year_title']);
if(taxonomy_exists($user_options[0]['year'])){
if($user_options[0]['year']=="category"){
	if(is_category( $movie_year)){
		$movie_year_cat_id[]=get_cat_ID($movie_year);
		wp_set_post_categories( $post_id,$movie_year_cat_id) ;
	}else{
	
		$movie_year_cat_id[]=  wp_create_category($movie_year);
		wp_set_post_categories( $post_id,$movie_year_cat_id) ;
	}
	}else{
 wp_set_post_terms( $post_id,$movie_year , $user_options[0]['year'] );
 }
}else{
add_post_meta( $post_id, $user_options[0]['year'],$movie_list[0]['movie_year'] );
}
}else{

}
if(!empty($user_options[0]['producer'])){
$movie_producer=str_replace("%%producertitle%%",$movie_list[0]['movie_producer'],$user_options[0]['producer_title']);
if(taxonomy_exists($user_options[0]['producer'])){
if($user_options[0]['producer']=="category"){
	if(is_category( $movie_producer)){
		$movie_producer_cat_id[]=get_cat_ID($movie_producer);
		wp_set_post_categories( $post_id,$movie_producer_cat_id) ;
	}else{
	
		$movie_producer_cat_id[]=  wp_create_category($movie_producer);
		wp_set_post_categories( $post_id,$movie_producer_cat_id) ;
	}
	}else{
 wp_set_post_terms( $post_id,$movie_producer , $user_options[0]['producer'] );
 }
}else{
add_post_meta( $post_id, $user_options[0]['producer'],$movie_list[0]['movie_producer'] );
}
}else{

}
if(!empty($user_options[0]['actor'])){
if(taxonomy_exists($user_options[0]['actor'])){
if($user_options[0]['actorauto']==1){
$movie_genre_array=explode(",",$movie_list[0]['movie_cast']);
for($g=0;$g<count($movie_cast_array);$g++){
$movie_cast=str_replace("%%actortitle%%",$movie_cast_array[$g],$user_options[0]['actor_title']);
if($user_options[0]['actor']=="category"){
	if(is_category( $movie_cast)){
		$movie_cast_cat_id[]=get_cat_ID($movie_cast);
		wp_set_post_categories( $post_id,$movie_cast_cat_id) ;
	}else{
	
		$movie_cast_cat_id[]=  wp_create_category($movie_cast);
		wp_set_post_categories( $post_id,$movie_cast_cat_id) ;
	}
	}else{
 wp_set_post_terms( $post_id,$movie_cast, $user_options[0]['actor'] );
 }
}
}else{
 $movie_cast=str_replace("%%actortitle%%",$movie_cast_array[$g],$user_options[0]['actor_title']);
 wp_set_post_terms( $post_id,$movie_cast, $user_options[0]['actor'] );
}

}else{
if($user_options[0]['actorauto']==1){
$movie_cast_array=explode(",",$movie_list[0]['movie_cast']);
for($g=0;$g<count($movie_cast_array);$g++){
$movie_cast=str_replace("%%actortitle%%",$movie_cast_array[$g],$user_options[0]['actor_title']);
add_post_meta( $post_id, $user_options[0]['actor'],$movie_cast );
}
}else{
add_post_meta( $post_id, $user_options[0]['actor'],$movie_list[0]['movie_cast'] );
}
}
}else{

}
set_post_thumbnail( $post_id, $attach_id );
		$wpdb->update( 
	$ltm_trailer, 
	array( 
		'movie_status' => 1
	), 
	array( 'id' => $addcheck ), 
	array( 
		'%d'
	), 
	array( '%d' ) 
);
unset($movie_list);
    }
}
	if(!empty($_POST['movie_not_add_list'])) {
    foreach($_POST['movie_not_add_list'] as $notaddcheck) {
      $wpdb->update( 
	$ltm_trailer, 
	array( 
		'movie_status' => 2
	), 
	array( 'id' => $notaddcheck ), 
	array( 
		'%d'
	), 
	array( '%d' ) 
);
    }
}
 }
function settingssave() {
	$error="";
	 	global $wpdb;
$ltm_options = $wpdb->prefix . 'ltm_options';
$user_options = $wpdb->get_results("SELECT * FROM {$ltm_options} WHERE id = 1", ARRAY_A);
$language_req='language_'.$user_options[0]['language'].'.php';
require_once($language_req);
$embed_code=$_POST['embed'];
$langue=$_POST['language'];
if(isset($_POST['yes'])) { 
$yes=1;
} else {
 $yes=0;
}

if(isset($_POST['actoryes'])) { 
$actoryes=1;
} else {
 $actoryes=0;
}
if(isset($_POST['gendeyes'])) { 
$gendeyes=1;
} else {
 $gendeyes=0;
}

if(stristr($_POST['category_title'],'%%tagtitle%%')===false){
	$error.= $GLOBALS['language']['errortagtitle'];
}
if(stristr($_POST['title'],'%%title%%')===false){
	$error.= $GLOBALS['language']['errortitle'];

}
if(stristr($_POST['actor_title'],'%%actortitle%%')===false){
	$error.= $GLOBALS['language']['erroractortitle'];
}
if(stristr($_POST['year_title'],'%%yeartitle%%')===false){
	$error.= $GLOBALS['language']['erroryeartitle'];
}
if(stristr($_POST['producer_title'],'%%producertitle%%')===false){
	$error.= $GLOBALS['language']['errorproducertitle'];
}
if(stristr($_POST['gende_title'],'%%gendetitle%%')===false){
	$error.= $GLOBALS['language']['errorgendetitle'];
}
if(stristr($embed_code,'%%embedcode%%')===false){
	$error.= $GLOBALS['language']['errorembedcode'];
}

	$GLOBALS['posterror']=$error;
if(empty($error)){
if($yes==1){

if (wp_next_scheduled('ltm_movie_save')===false) {
$times=time();
wp_schedule_event( time(),'hourly', 'ltm_movie_save' );
    add_option( 'LTM_cron_time', $times );
}
}else{
wp_clear_scheduled_hook( 'ltm_movie_save');

}
	$ltm_options = $wpdb->prefix . 'ltm_options';
	$wpdb->update( 
	$ltm_options, 
	array( 
		'language' => $langue,	// string
		'embed_code' => $embed_code,	// string
		'width' => $_POST['width'],	// string
		'height' => $_POST['height'],	// string
		'title' => $_POST['title'],
		'post_description' => $_POST['post_description'],
		'category_title' => $_POST['category_title'],
		'actor' => $_POST['actor'],
		'actor_title' => $_POST['actor_title'],
		'year' => $_POST['year'],
		'year_title' => $_POST['year_title'],	
		'producer' => $_POST['producer'],
		'producer_title' => $_POST['producer_title'],
		'gender' => $_POST['gender'],
		'gende_title' => $_POST['gende_title'],
		'auto' => $yes,
		'actorauto'=>$actoryes,
		'gendeauto'=>$gendeyes	
	), 
	array( 'id' => 1 ), 
	array( 
		'%s',	//language
		'%s',	//embed_code
		'%d',	//width
		'%d',	//height
		'%s',	//title
		'%s',	//post_description
		'%s',	//category_title
		'%s',	//actor
		'%s',	//actor_title
		'%s',	//year
		'%s',	//year_title		
		'%s',	//year
		'%s',	//year_title
		'%s',	//gender
		'%s',	//gende_title
		'%d',	//auto
		'%d',	//actorauto
		'%d'	//gendeauto
	), 
	array( '%d' ) 
);
}

    
    
}



function LMT_admin(){ 

$last_time=get_option('LTM_cron_time');
  	global $wpdb;
$ltm_options = $wpdb->prefix . 'ltm_options';
$user_options = $wpdb->get_results("SELECT * FROM {$ltm_options} WHERE id = 1", ARRAY_A);
$language_req='language_'.$user_options[0]['language'].'.php';
require_once($language_req);
$api_url='http://trailerapi.com/api/api.php?user_trailer_limit='.$user_options[0]['trailer']."&language=".$user_options[0]['language'];
$wp_version=get_bloginfo('version');
$api_args = array(
    'timeout'     => 5,
    'redirection' => 5,
    'httpversion' => '1.0',
    'user-agent'  => 'WordPress/' . $wp_version . '; ' . get_bloginfo( 'url' ),
    'blocking'    => true,
    'headers'     => array(),
    'cookies'     => array(),
    'body'        => null,
    'compress'    => false,
    'decompress'  => true,
    'sslverify'   => true,
    'stream'      => false,
    'filename'    => null
); 
$api_url_content_array=wp_remote_get( $api_url,$api_args);
$api_url_content=$api_url_content_array['body'];
$user_trailer_last_id=$user_options[0]['trailer']+10;
if(!$xml = simplexml_load_string($api_url_content))
{
		$wpdb->update( 
	$ltm_options, 
	array( 
		'trailer' => 1
	), 
	array( 'id' => 1 ), 
	array( 
		'%d'
	), 
	array( '%d' ) 
);
	}else
	{


if(empty($xml->error_status)){
	foreach( $xml as $film ) {


	$ltm_post=$wpdb->prefix.'posts';
	$ltm_post_sql= "SELECT * FROM ".$ltm_post." WHERE post_title LIKE '%".addslashes($film->name)."%'";
	$wpdb->get_results($ltm_post_sql);
	$postCount = $wpdb->num_rows;
	$ltm_trailer = $wpdb->prefix . 'ltm_trailer';
$ltm_trailer_sql= "SELECT movie_id FROM ".$ltm_trailer." WHERE movie_id='".$film->did."'";
	$wpdb->get_results($ltm_trailer_sql);
	$trailertCount = $wpdb->num_rows;
if($postCount>0){
$movie_status=4;
}else{
$movie_status=0;
}
	if($trailertCount==0){
$wpdb->insert( 
	$ltm_trailer, 
	array( 
		'movie_name' => $film->name,
		'movie_poster' => $film->poster,
		'movie_id' => $film->did,
		'movie_description' =>$film->description,
		'movie_year' =>$film->year,
		'movie_producer' =>$film->producer,
		'movie_imdb' =>$film->imdb,
		'movie_cast' =>$film->cast,
		'movie_genre' =>$film->genre,
		'movie_status' => $movie_status
	), 
	array( 
		'%s', //movie_name
		'%s', //movie_poster
		'%s', //movie_did
		'%s', //movie_description
		'%s', // movie_year
		'%s',// movie_producer
		'%s', //movie_imdb
		'%s', //movie_cast
		'%s', //movie_genre
		'%d' //movie_status
	) 
);

}
}
$wpdb->update( 
	$ltm_options , 
	array( 
		'trailer' => $user_trailer_last_id,	// string
	
	), 
	array( 'id' => 1 ), 
	array( 
		'%d'	// value2
	), 
	array( '%d' ) 
);
}else{
		$wpdb->update( 
	$ltm_options, 
	array( 
		'trailer' => 1
	), 
	array( 'id' => 1 ), 
	array( 
		'%d'
	), 
	array( '%d' ) 
);
}
}
?>
<div class="listtitle"><?php echo $GLOBALS['language']['adminsettings']; ?></div>
<?php if(!empty( $GLOBALS['posterror'])){ ?>
<legend><?php echo  $GLOBALS['posterror']; unset( $GLOBALS['posterror']);  ?></legend>
<?php } ?>
<?php if(!empty( $GLOBALS['optionserror'])){ ?>
<legend><?php echo  $GLOBALS['optionserror']; unset( $GLOBALS['optionserror']);  ?><a href="<?php echo  $_SERVER['REQUEST_URI'] ;?>&repair=1">Repair Me</a>
</legend>
<?php } ?>
<table class="table"><tr>
<form action="<?php echo  $_SERVER['REQUEST_URI'] ;?>" method="post" enctype="multipart/form-data" class="form">
     <td>  <legend><span class="number">1</span><?php echo $GLOBALS['language']['language']; ?>:</legend>
       
        <select id="language" name="language">
            <option value="tr" <?php if($user_options[0]['language']=='tr'){ ?> selected="selected" <?php } ?>>Türkçe</option>
            <option value="en" <?php if($user_options[0]['language']=='en'){ ?> selected="selected" <?php } ?>>English</option>
            <option value="de" <?php if($user_options[0]['language']=='de'){ ?> selected="selected" <?php } ?>>Deutsch</option>
            <option value="fr" <?php if($user_options[0]['language']=='fr'){ ?> selected="selected" <?php } ?>>Français</option>
            <option value="es" <?php if($user_options[0]['language']=='es'){ ?> selected="selected" <?php } ?>>Español</option>
        </select>
        </td><td> <legend><span class="number">2</span><?php echo $GLOBALS['language']['automatic']; ?></legend>
      
          <input type="checkbox" id="yes" value="1" name="yes" <?php if($user_options[0]['auto']==1){ ?>  checked="checked" <?php } ?>><label class="light" for="development"><?php echo $GLOBALS['language']['yes']; ?></label>
		  </td><td>   <legend><span class="number">3</span><?php echo $GLOBALS['language']['tembedcode']; ?>:</legend>
     <textarea name="embed" cols="50" rows="4" ><?php echo $user_options[0]['embed_code']; ?></textarea>
	  </td></tr><tr><td>  <legend><span class="number">4</span><?php echo $GLOBALS['language']['posttitle']; ?>:</legend>
     <input name="title" type="text" value="<?php echo $user_options[0]['title']; ?>" />
	 </td><td><legend><span class="number">5</span><?php echo $GLOBALS['language']['postdescription']; ?>:</legend>
     <textarea name="post_description" cols="50" rows="4" ><?php echo $user_options[0]['post_description']; ?></textarea>
	     </td><td> <legend><span class="number">6</span><?php echo $GLOBALS['language']['tembedcodewh']; ?>:</legend>
		  <?php echo $GLOBALS['language']['width']; ?>:
		  <input name="width" type="text" value="<?php echo $user_options[0]['width']; ?>" />
		  <?php echo $GLOBALS['language']['height']; ?>:
		  <input name="height" type="text"  value="<?php echo $user_options[0]['height']; ?>"/>
		  	 
  </td></tr><tr><td>  <legend><span class="number">7</span><?php echo $GLOBALS['language']['tagtitle']; ?>:</legend>
    <textarea name="category_title"  cols="50" rows="4"><?php echo $user_options[0]['category_title']; ?></textarea>
  </td><td>  <legend><span class="number">8</span><?php echo $GLOBALS['language']['selectactortax']; ?> :</legend>
    <select id="actor" name="actor">
	    <option value="" > <?php echo $GLOBALS['language']['selectactortax']; ?></option>
<?php
$taxonomies = get_taxonomies(); 
foreach ( $taxonomies as $taxonomy ) { ?>
    <option value="<?php echo $taxonomy?>"  <?php if($user_options[0]['actor']==$taxonomy){ ?> selected="selected" <?php } ?>> <?php echo $taxonomy; ?></option>

	<?php
}
$meta_keys = $wpdb->get_results("SELECT DISTINCT (meta_key) as meta_key FROM $wpdb->postmeta ", ARRAY_A );
foreach($meta_keys as $meta_key){
?>
  <option value="<?php echo $meta_key['meta_key'];?>" <?php if($user_options[0]['actor']==$meta_key['meta_key']){ ?> selected="selected" <?php } ?> > <?php echo $meta_key['meta_key']; ?></option>
<?php 
}
?>
</select>
  </td><td> <legend><span class="number">9</span><?php echo $GLOBALS['language']['actortitle']; ?>:</legend>
     <input name="actor_title" type="text" value="<?php echo $user_options[0]['actor_title']; ?>" />
	   <legend><?php echo $GLOBALS['language']['automaticactortax']; ?></legend>
      
          <input type="checkbox" id="yes" value="1" name="actoryes" <?php if($user_options[0]['actorauto']==1){ ?>  checked="checked" <?php } ?>><label class="light" for="development"><?php echo $GLOBALS['language']['yes']; ?></label>
  </td></tr><tr><td> <legend><span class="number">10</span><?php echo $GLOBALS['language']['selectyeartax']; ?> :</legend>
    <select id="year" name="year">
	    <option value="" > <?php echo $GLOBALS['language']['selectyeartax']; ?></option>
<?php
$taxonomies = get_taxonomies(); 
foreach ( $taxonomies as $taxonomy ) { ?>
    <option value="<?php echo $taxonomy;?>" <?php if($user_options[0]['year']==$taxonomy){ ?> selected="selected" <?php } ?> > <?php echo $taxonomy; ?></option>

	<?php
}
$meta_keys = $wpdb->get_results("SELECT DISTINCT (meta_key) as meta_key FROM $wpdb->postmeta ", ARRAY_A );
foreach($meta_keys as $meta_key){
?>
  <option value="<?php echo $meta_key['meta_key'];?>" <?php if($user_options[0]['year']==$meta_key['meta_key']){ ?> selected="selected" <?php } ?> > <?php echo $meta_key['meta_key']; ?></option>
<?php 
}
?>
</select>

   </td><td> <legend><span class="number">11</span><?php echo $GLOBALS['language']['yeartitle']; ?>:</legend>
     <input name="year_title" type="text" value="<?php echo $user_options[0]['year_title']; ?>" />
  </td><td> <legend><span class="number">12</span><?php echo $GLOBALS['language']['selectgendetax']; ?> :</legend>
    <select id="gender" name="gender">
	 <option value="" > <?php echo $GLOBALS['language']['selectgendetax']; ?></option>
<?php
$taxonomies = get_taxonomies(); 
foreach ( $taxonomies as $taxonomy ) { ?>
    <option value="<?php echo $taxonomy?>" <?php if($user_options[0]['gender']==$taxonomy){ ?> selected="selected" <?php } ?> > <?php echo $taxonomy; ?></option>

	<?php
}
$meta_keys = $wpdb->get_results("SELECT DISTINCT (meta_key) as meta_key FROM $wpdb->postmeta ", ARRAY_A );
foreach($meta_keys as $meta_key){
?>
  <option value="<?php echo $meta_key['meta_key'];?>" <?php if($user_options[0]['gender']==$meta_key['meta_key']){ ?> selected="selected" <?php } ?> > <?php echo $meta_key['meta_key']; ?></option>
<?php 
}
?>
</select>
  <legend><?php echo $GLOBALS['language']['automaticgendetax']; ?></legend>
      
          <input type="checkbox" id="yes" value="1" name="gendeyes" <?php if($user_options[0]['gendeauto']==1){ ?>  checked="checked" <?php } ?>><label class="light" for="development"><?php echo $GLOBALS['language']['yes']; ?></label>
   </td></tr><tr><td><legend><span class="number">13</span><?php echo $GLOBALS['language']['gendetitle']; ?>:</legend>
     <input name="gende_title" type="text" value="<?php echo $user_options[0]['gende_title']; ?>" /></td>
	<td> <legend><span class="number">14</span><?php echo $GLOBALS['language']['producertitle']; ?>:</legend>
     <input name="producer_title" type="text" value="<?php echo $user_options[0]['producer_title']; ?>" />
  </td><td><legend><span class="number">15</span><?php echo $GLOBALS['language']['selectproducertax']; ?> :</legend>
    <select id="producer" name="producer">
	    <option value="" > <?php echo $GLOBALS['language']['selectproducertax']; ?></option>
<?php
$taxonomies = get_taxonomies(); 
foreach ( $taxonomies as $taxonomy ) { ?>
    <option value="<?php echo $taxonomy;?>" <?php if($user_options[0]['producer']==$taxonomy){ ?> selected="selected" <?php } ?> > <?php echo $taxonomy; ?></option>

	<?php
}
$meta_keys = $wpdb->get_results("SELECT DISTINCT (meta_key) as meta_key FROM $wpdb->postmeta ", ARRAY_A );
foreach($meta_keys as $meta_key){
?>
  <option value="<?php echo $meta_key['meta_key'];?>" <?php if($user_options[0]['producer']==$meta_key['meta_key']){ ?> selected="selected" <?php } ?> > <?php echo $meta_key['meta_key']; ?></option>
<?php 
}
?>
</select></td><tr><td colspan="3" align="center">
        <button type="submit" name="settingsubmit" >Okey</button>
		</td></tr>
		</table>
      </form>
<?php
}
function LMT_add(){ 
  	global $wpdb;
$ltm_options = $wpdb->prefix . 'ltm_options';
$user_options = $wpdb->get_results("SELECT * FROM {$ltm_options} WHERE id = 1", ARRAY_A);
$language_req='language_'.$user_options[0]['language'].'.php';
require_once($language_req);
$api_url='http://trailerapi.com/api/api.php?user_trailer_limit='.$user_options[0]['trailer']."&language=".$user_options[0]['language'];
$wp_version=get_bloginfo('version');
$api_args = array(
    'timeout'     => 5,
    'redirection' => 5,
    'httpversion' => '1.0',
    'user-agent'  => 'WordPress/' . $wp_version . '; ' . get_bloginfo( 'url' ),
    'blocking'    => true,
    'headers'     => array(),
    'cookies'     => array(),
    'body'        => null,
    'compress'    => false,
    'decompress'  => true,
    'sslverify'   => true,
    'stream'      => false,
    'filename'    => null
); 
$api_url_content_array=wp_remote_get( $api_url,$api_args);
$api_url_content=$api_url_content_array['body'];
$user_trailer_last_id=$user_options[0]['trailer']+10;
if(!$xml = simplexml_load_string($api_url_content))
{
		$wpdb->update( 
	$ltm_options, 
	array( 
		'trailer' => 1
	), 
	array( 'id' => 1 ), 
	array( 
		'%d'
	), 
	array( '%d' ) 
);
	}else
	{

?>
<div class="listtitle"><?php echo $GLOBALS['language']['lastmovietrailerlist']; ?></div>
<?php
if(empty($xml->error_status)){
	foreach( $xml as $film ) {


	$ltm_post=$wpdb->prefix.'posts';
	$ltm_post_sql= "SELECT * FROM ".$ltm_post." WHERE post_title LIKE '%".addslashes($film->name)."%'";
	$wpdb->get_results($ltm_post_sql);
	$postCount = $wpdb->num_rows;
	$ltm_trailer = $wpdb->prefix . 'ltm_trailer';
$ltm_trailer_sql= "SELECT movie_id FROM ".$ltm_trailer." WHERE movie_id='".$film->did."'";
	$wpdb->get_results($ltm_trailer_sql);
	$trailertCount = $wpdb->num_rows;
if($postCount>0){
$movie_status=4;
}else{
$movie_status=0;
}
	if($trailertCount==0){
$wpdb->insert( 
	$ltm_trailer, 
	array( 
		'movie_name' => $film->name,
		'movie_poster' => $film->poster,
		'movie_id' => $film->did,
		'movie_description' =>$film->description,
		'movie_year' =>$film->year,
		'movie_producer' =>$film->producer,
		'movie_imdb' =>$film->imdb,
		'movie_cast' =>$film->cast,
		'movie_genre' =>$film->genre,
		'movie_status' => $movie_status
	), 
	array( 
		'%s', //movie_name
		'%s', //movie_poster
		'%s', //movie_did
		'%s', //movie_description
		'%s', // movie_year
		'%s',// movie_producer
		'%s', //movie_imdb
		'%s', //movie_cast
		'%s', //movie_genre
		'%d' //movie_status
	) 
);

}
}
$wpdb->update( 
	$ltm_options , 
	array( 
		'trailer' => $user_trailer_last_id,	// string
	
	), 
	array( 'id' => 1 ), 
	array( 
		'%d'	// value2
	), 
	array( '%d' ) 
);
}else{
		$wpdb->update( 
	$ltm_options, 
	array( 
		'trailer' => 1
	), 
	array( 'id' => 1 ), 
	array( 
		'%d'
	), 
	array( '%d' ) 
);
}
$ltm_trailer = $wpdb->prefix . 'ltm_trailer';
$query = $wpdb->get_results("SELECT * FROM $ltm_trailer WHERE movie_status='0' or  movie_status='4' Limit 10", ARRAY_A);
	$movie_list_id=1;
	?>
	<?php if($xml->error_status=="refresh"){ }else{ echo $xml->error_status; } ?>
	<form action="<?php echo  $_SERVER['REQUEST_URI'] ;?>" method="post" enctype="multipart/form-data" id="add_form">
	<input type="submit"  name="moviessubmit" value="<?php echo $GLOBALS['language']['selectsadd']; ?>" class="alladd">
<br/><input id="alladd_checked" name="alladd_checked" type="checkbox" onclick="add_checked()" /> <?php echo $GLOBALS['language']['checkalladd']; ?>
&nbsp;&nbsp;
<input id="allnotadd_checked" name="allnotadd_checked" type="checkbox" onclick="notadd_checked()" /> <?php echo $GLOBALS['language']['checknotalladd']; ?>
	<?php
foreach($query as $row)
{
?>

<div class="trailer normal caption"><div class="poster"><img src="<?php echo str_replace("-xlarge","",$row['movie_poster']); ?>" alt="<?php echo $row['movie_name'];?>" border="0"></div>
<div class="description"><legend><span class="number"><?php echo $movie_list_id; ?></span><?php echo $row['movie_name'];?></legend><p><?php echo $row['movie_description'];?><br/>İmdb:<?php echo $row['movie_imdb'];?><br/><?php echo $GLOBALS['language']['producer'];?>:<?php echo $row['movie_producer'];?><br/><?php echo $GLOBALS['language']['cast']; ?>:<?php echo $row['movie_cast'];?></p><h3><input type="checkbox" id="addcheckbox" name="movie_add_list[]" value="<?php echo $row['id'];?>"><?php echo $GLOBALS['language']['addmovietrailer']; ?>&nbsp;<input type="checkbox" id="notaddcheckbox" name="movie_not_add_list[]" value="<?php echo $row['id'];?>"> <?php echo $GLOBALS['language']['notaddmovietrailer']; ?></p><p><?php if($row['movie_status']==4){?> <?php echo $GLOBALS['language']['problem']; ?><?php } ?>&nbsp;</h3></div></div>

<?php
$movie_list_id++;
}

}
?>
</from>

<?php
die();
return true;
}
function LMT_added(){ 
  	global $wpdb;
$ltm_options = $wpdb->prefix . 'ltm_options';
$user_options = $wpdb->get_results("SELECT * FROM {$ltm_options} WHERE id = 1", ARRAY_A);
$language_req='language_'.$user_options[0]['language'].'.php';
require_once($language_req);
?>
<div class="listtitle"><?php echo $GLOBALS['language']['addmovietrailerlist']; ?></div>
<?php

		$ltm_trailer = $wpdb->prefix . 'ltm_trailer';
		$ltm_trailer_sql= "SELECT movie_id FROM ".$ltm_trailer." WHERE movie_status='1'";
	$wpdb->get_results($ltm_trailer_sql);
	$pageCount = $wpdb->num_rows;
	$pageCount=ceil($pageCount/10);
		$page=@$_GET['page_number'];
		$page_start=($page/2)-5;
		if($page_start<0){
		$page_start=0;
		if($pageCount<10){
		$page_stop=$pageCount;
		}else{
			$page_stop=10;
		}
		}else{
		$page_stop=$page_start+10;
		if($pageCount<$page_stop){
		$page_stop=$pageCount;
		}else{
		}
		}
		if(empty($page)){
		$page=0;
		}else{
		$page=$page*10;
		}
$query = $wpdb->get_results("SELECT * FROM $ltm_trailer WHERE movie_status='1'  ORDER BY id DESC  Limit $page,10", ARRAY_A);
	$movie_list_id=1;
	?>
	<form action="<?php echo  $_SERVER['REQUEST_URI'] ;?>" method="post" enctype="multipart/form-data" id="add_form">
	<input type="submit"  name="moviessubmit" value="<?php echo $GLOBALS['language']['selectsadd']; ?>" class="alladd">
<br/><input id="alladd_checked" name="alladd_checked" type="checkbox" onclick="add_checked()" /> <?php echo $GLOBALS['language']['checkalladd']; ?>
&nbsp;&nbsp;
<input id="allnotadd_checked" name="allnotadd_checked" type="checkbox" onclick="notadd_checked()" /> <?php echo $GLOBALS['language']['checknotalladd']; ?>
	<table><tr><td><?php echo $GLOBALS['language']['page']; ?>:</td><td>
<?php for($p=$page_start;$p<$page_stop;$p++){ 
$s=$p+1;
echo '<span class="number"><a href="'.$_SERVER['REQUEST_URI'].'&page_number='.$p.'">'.$s.'</a></span>';
} 
?></td></tr></table>
	<?php
foreach($query as $row)
{
?>

<div class="trailer normal caption"><div class="poster"><img src="<?php echo str_replace("-xlarge","",$row['movie_poster']); ?>" alt="<?php echo $row['movie_name'];?>" border="0"></div>
<div class="description"><legend><span class="number"><?php echo $movie_list_id; ?></span><?php echo $row['movie_name'];?></legend><p><?php echo $row['movie_description'];?><br/>İmdb:<?php echo $row['movie_imdb'];?><br/><?php echo $GLOBALS['language']['producer'];?>:<?php echo $row['movie_producer'];?><br/><?php echo $GLOBALS['language']['cast']; ?>:<?php echo $row['movie_cast'];?></p><h3><input type="checkbox" id="addcheckbox" name="movie_add_list[]" value="<?php echo $row['id'];?>"><?php echo $GLOBALS['language']['addmovietrailer']; ?>&nbsp;<input type="checkbox" id="notaddcheckbox" name="movie_not_add_list[]" value="<?php echo $row['id'];?>"> <?php echo $GLOBALS['language']['notaddmovietrailer']; ?></p><p><?php if($row['movie_status']==4){?> <?php echo $GLOBALS['language']['problem']; ?><?php } ?>&nbsp;</h3></div></div>
<?php
$movie_list_id++;
}
?>
</from>

<?php
die();
return true;

}
function LMT_notadd(){
  	global $wpdb;
$ltm_options = $wpdb->prefix . 'ltm_options';
$user_options = $wpdb->get_results("SELECT * FROM {$ltm_options} WHERE id = 1", ARRAY_A);
$language_req='language_'.$user_options[0]['language'].'.php';
require_once($language_req);
	?>
<div class="listtitle"><?php echo $GLOBALS['language']['notaddmovietrailerlist']; ?></div>
<?php
$ltm_trailer = $wpdb->prefix . 'ltm_trailer';
		$ltm_trailer_sql= "SELECT movie_id FROM ".$ltm_trailer." WHERE movie_status='2'";
	$wpdb->get_results($ltm_trailer_sql);
	$pageCount = $wpdb->num_rows;
	$pageCount=ceil($pageCount/10);
		$page=@$_GET['page_number'];
		$page_start=($page/2)-5;
		if($page_start<0){
		$page_start=0;
		if($pageCount<10){
		$page_stop=$pageCount;
		}else{
			$page_stop=10;
		}
		}else{
		$page_stop=$page_start+10;
		if($pageCount<$page_stop){
		$page_stop=$pageCount;
		}else{
		}
		}
		if(empty($page)){
		$page=0;
		}else{
		$page=$page*10;
		}
$query = $wpdb->get_results("SELECT * FROM $ltm_trailer WHERE movie_status='2'  Limit 10", ARRAY_A);
	$movie_list_id=1;
	?>
	<form action="<?php echo  $_SERVER['REQUEST_URI'] ;?>" method="post" enctype="multipart/form-data" id="add_form">
	<input type="submit"  name="moviessubmit" value="<?php echo $GLOBALS['language']['selectsadd']; ?>" class="alladd">
<br/><input id="alladd_checked" name="alladd_checked" type="checkbox" onclick="add_checked()" /> <?php echo $GLOBALS['language']['checkalladd']; ?>
&nbsp;&nbsp;
<input id="allnotadd_checked" name="allnotadd_checked" type="checkbox" onclick="notadd_checked()" /> <?php echo $GLOBALS['language']['checknotalladd']; ?>
<table><tr><td><?php echo $GLOBALS['language']['page']; ?>:</td><td>
<?php for($p=$page_start;$p<$page_stop;$p++){ 
$s=$p+1;
echo '<span class="number"><a href="'.$_SERVER['REQUEST_URI'].'&page_number='.$p.'">'.$s.'</a></span>';
} 
?></td></tr></table>
	<?php
foreach($query as $row)
{
?>
<div class="trailer normal caption"><div class="poster"><img src="<?php echo str_replace("-xlarge","",$row['movie_poster']); ?>" alt="<?php echo $row['movie_name'];?>" border="0"></div>
<div class="description"><legend><span class="number"><?php echo $movie_list_id; ?></span><?php echo $row['movie_name'];?></legend><p><?php echo $row['movie_description'];?><br/>İmdb:<?php echo $row['movie_imdb'];?><br/><?php echo $GLOBALS['language']['producer'];?>:<?php echo $row['movie_producer'];?><br/><?php echo $GLOBALS['language']['cast']; ?>:<?php echo $row['movie_cast'];?></p><h3><input type="checkbox" id="addcheckbox" name="movie_add_list[]" value="<?php echo $row['id'];?>"><?php echo $GLOBALS['language']['addmovietrailer']; ?>&nbsp;<input type="checkbox" id="notaddcheckbox" name="movie_not_add_list[]" value="<?php echo $row['id'];?>"> <?php echo $GLOBALS['language']['notaddmovietrailer']; ?></p><p><?php if($row['movie_status']==4){?> <?php echo $GLOBALS['language']['problem']; ?><?php } ?>&nbsp;</h3></div></div>

<?php
$movie_list_id++;
}
?>
</from>
<?php
die();
return true;
}
?>