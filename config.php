<?php

	
	## Hataları Görüntüleyelim
	error_reporting(1);
	
	// Session Tutalım
	@session_start();
	@ob_start();
	
	
	## PDO Mysql Bağlantısı (mysql footer'da kapanacak ## $db = null;)
	try{
		$db = new PDO("mysql:host=localhost;dbname=testdb;charset=utf8","root","");
	} catch (PDOException $e) {
		print $e->getMessage();
	}
	
	
	// Şifreleme
	function sifrele( $obj ) {
		return base64_encode(gzcompress(serialize($obj)));
	}
	// Şifrelemeyi Çöz
	function sifrecoz($txt) {
		return unserialize(gzuncompress(base64_decode($txt)));
	}
	
	// Zaman Fonksiyonları
	$ontime = time();
	$ondatetime = date('Y-m-d H:i:s', $ontime);
	
	// Türkçe ay ve günler
	function dateTR($format, $datetime){
		$z = date($format, $datetime);
		$gun_dizi = array(
			'Monday'    => 'Pazartesi',
			'Tuesday'   => 'Salı',
			'Wednesday' => 'Çarşamba',
			'Thursday'  => 'Perşembe',
			'Friday'    => 'Cuma',
			'Saturday'  => 'Cumartesi',
			'Sunday'    => 'Pazar',
			'January'   => 'Ocak',
			'February'  => 'Şubat',
			'March'     => 'Mart',
			'April'     => 'Nisan',
			'May'       => 'Mayıs',
			'June'      => 'Haziran',
			'July'      => 'Temmuz',
			'August'    => 'Ağustos',
			'September' => 'Eylül',
			'October'   => 'Ekim',
			'November'  => 'Kasım',
			'December'  => 'Aralık',
			'Mon'       => 'Pts',
			'Tue'       => 'Sal',
			'Wed'       => 'Çar',
			'Thu'       => 'Per',
			'Fri'       => 'Cum',
			'Sat'       => 'Cts',
			'Sun'       => 'Paz',
			'Jan'       => 'Oca',
			'Feb'       => 'Şub',
			'Mar'       => 'Mar',
			'Apr'       => 'Nis',
			'Jun'       => 'Haz',
			'Jul'       => 'Tem',
			'Aug'       => 'Ağu',
			'Sep'       => 'Eyl',
			'Oct'       => 'Eki',
			'Nov'       => 'Kas',
			'Dec'       => 'Ara',
		);
		foreach($gun_dizi as $en => $tr){
			$z = str_replace($en, $tr, $z);
		}
		if(strpos($z, 'Mayıs') !== false && strpos($format, 'F') === false) $z = str_replace('Mayıs', 'May', $z);
		return $z;
	}
	
	
?>