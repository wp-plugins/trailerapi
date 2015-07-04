<?php

function image_save($resim,$filename){

	$upload_dir = wp_upload_dir();
	if( wp_mkdir_p( $upload_dir['path'] ) ) {
		$file = $upload_dir['path'] . '/' . basename($filename);
	} else {
		$file = $upload_dir['basedir'] . '/' .basename($filename);
	}

	$fopen  = fopen($file,'wb');
	$ch = curl_init();         
	curl_setopt($ch, CURLOPT_URL, $resim);
	curl_setopt($ch, CURLOPT_HEADER,0);
	curl_setopt($ch, CURLOPT_FILE, $fopen);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/600.5.17 (KHTML, like Gecko) Version/8.0.5 Safari/600.5.17');
	curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');
	curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,25);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	$image_data = curl_exec($ch);
	curl_close($ch);
	file_put_contents( $file, $image_data );
	

	return $file;	
		
}


function dateto($zaman,$suan,$ayrinti=2){
			$tarihler=array(365*24*60*60	=> $GLOBALS['language']['year'],
						30*24*60*60		=> $GLOBALS['language']['month'],
						7*24*60*60		=> $GLOBALS['language']['week'],
						24*60*60		=> $GLOBALS['language']['day'],
						60*60			=> $GLOBALS['language']['hour'],
						60				=> $GLOBALS['language']['minute'],
						1				=> $GLOBALS['language']['second']);
		if($suan>$zaman){ 
			$gecen=$suan-$zaman;
			if($gecen<5){$cikti='5 Saniyeden daha az sure once.';}
		}else{
			$gecen=$zaman-$suan;
			if($gecen<5){ $cikti='5 Saniyeden daha az sure gecmis.';}
		}
		$cikti=array();
		$cikis=0;
		if($gecen>5){
			foreach($tarihler as $sayi=>$kelime){
			if($cikis>=$ayrinti || ($cikis<0 && $sayi<60)) break;
			// ara sureyi bulalim
			$arasure=floor($gecen/$sayi);
			if($arasure>0){
				$cikti[]=$arasure.' '.$kelime;
				$gecen-=$arasure*$sayi;
				$cikis++;
			}else if($cikis>0) $cikis++;
			}
			$cikti=implode(' ',$cikti).'';

		}
		
			
		return $cikti;
	
	}
	function genre_translate( $string, $lang ) {
	if ( $lang == "tr" ) {
		$string = str_replace( array(
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
		), array(
			"Aksiyon",
			"Macera",
			"Animasyon",
			"Biyografi",
			"Komedi",
			"Suç",
			"Belgesel",
			"Dram",
			"Aile",
			"Fantastik",
			"Kara Film",
			"Tarihi",
			"Korku",
			"Müzik",
			"Müzik",
			"Gizem",
			"Romantik",
			"Bilim Kurgu",
			"Spor",
			"Gerilim",
			"Savaş"
		), $string );
	}
	if ( $lang == "es" ) {
		$string = str_replace( array(
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
		), array(
			"Acción",
			"Aventura",
			"Animación",
			"Biografía",
			"Comedia",
			"Crimen",
			"Documental",
			"Drama",
			"Familia",
			"Fantasía",
			"Cinenegro",
			"Historia",
			"Horror",
			"Música",
			"musical",
			"Misterio",
			"Pareja",
			"CienciaFicción",
			"Sport",
			"Thriller",
			"War"
		), $string );
	}
	if ( $lang == "fr" ) {
		$string = str_replace( array(
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
		), array(
			"Action",
			"Aventure",
			"Animation",
			"Biographie",
			"Comédie",
			"Crime",
			"Documentaire",
			"Drama",
			"Famille",
			"Fantasy",
			"Film-Noir",
			"Histoire",
			"Horreur",
			"Musique",
			"Musical",
			"Mystère",
			"Romance",
			"Sci-Fi",
			"Sport",
			"Thriller",
			"War"
		), $string );
	}
	if ( $lang == "pr" ) {
		$string = str_replace( array(
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
		), array(
			"Action",
			"Aventura",
			"Animação",
			"Biografia",
			"Comedy",
			"Crime",
			"Documentary",
			"Drama",
			"Família",
			"Fantasia",
			"Filmenegro",
			"História",
			"Horror",
			"Music",
			"musical",
			"Mistério",
			"romance",
			"Sci-Fi",
			"Sport",
			"Thriller",
			"War"
		), $string );
	}
	if ( $lang == "de" ) {
		$string = str_replace( array(
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
		), array(
			"Action",
			"Adventure",
			"Animation",
			"Biography",
			"Comedy",
			"Crime",
			"Dokumentarfilm",
			"Drama",
			"Familie",
			"Fantasy",
			"Film Noir",
			"History",
			"Horror",
			"Music",
			"Musik",
			"Mystery",
			"Romance",
			"Sci-Fi",
			"Sport",
			"Thriller",
			"War"
		), $string );
	}

	return $string;
}
	function LTM_update() {
	global $wpdb;
    $ltm_options = $wpdb->prefix.'ltm_options';
	$charset_collate = $wpdb->get_charset_collate();
	$ltm_version=get_option('LTM_version');
	if($ltm_version=="1.0"){
	$ltm_options = $wpdb->prefix.'ltm_options';
	$update_sql="ALTER TABLE $ltm_options  ADD post_description TEXT NOT NULL AFTER title";

	$wpdb->query($update_sql);
			$wpdb->update( 
	$ltm_options, 
	array( 
		'post_description' => '%%title%% Movie : <br> Cast : %%cast%% <br> Release Year : %%year%%',
	), 
	array( 'id' => 1 ), 
	array( 
		'%s'
	), 
	array( '%d' ) 
);
	update_option('LTM_version','1.1');
	}
		if($ltm_version=="1.1"){
	$update_sql="ALTER TABLE $ltm_options ADD  embed_taxonomy VARCHAR(255) NOT NULL AFTER embed_code;";
	$wpdb->query($update_sql);
	$update_sql="ALTER TABLE $ltm_options ADD current_year INT NOT NULL DEFAULT '0' AFTER year_title;";
	$wpdb->query($update_sql);
	$update_sql="ALTER TABLE $ltm_options ADD imdb_title VARCHAR(255) NOT NULL AFTER title, ADD imdb VARCHAR(255) NOT NULL AFTER imdb_title;";
	$wpdb->query($update_sql);
	$update_sql="ALTER TABLE $ltm_options ADD specific_title VARCHAR(255) NOT NULL AFTER gendeauto, ADD specific_taxonomy VARCHAR(255) NOT NULL AFTER specific_title;";
	$wpdb->query($update_sql);
				$wpdb->update( 
	$ltm_options, 
	array( 
		'imdb_title' => '%%imdbpoint%% Point',
	), 
	array( 'id' => 1 ), 
	array( 
		'%s'
	), 
	array( '%d' ) 
);
$wpdb->update( 
	$ltm_options, 
	array( 
		'specific_title' => 'Trailer',
	), 
	array( 'id' => 1 ), 
	array( 
		'%s'
	), 
	array( '%d' ) 
);
	update_option('LTM_version','1.2');
	}
	}
?>