<?php
function curl($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/600.5.17 (KHTML, like Gecko) Version/8.0.5 Safari/600.5.17');
	curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');
	curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt');
	curl_setopt($ch, CURLOPT_REFERER, 'https://trailers.apple.com/');
	//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$str = curl_exec($ch);
	curl_close($ch);
	//echo $str;
	return $str;
}
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
function seoFile($string) {
	$aranacak = array("Ý"," ","ý","ç","ð","ö","þ","ü","'","%","’");
	$degisecek = array("i","-","i","c","g","o","s","u","","","");
	$string = str_replace($aranacak, $degisecek, strtolower($string));
	$string = strtolower($string);
	$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
	$string = preg_replace("/[\s-]+/", " ", $string);
	$string = preg_replace("/[\s_]/", "-", $string);
	if($string[0] == '-') $string[0] = "";
	if($string[strlen($string)-1] == '-') $string = substr_replace($string, "", -1);
	return $string;
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
?>