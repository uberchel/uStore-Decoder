<?php

class usParser {
	
	private static function arrHash($depth = 1) {

		$arr = [];
		$depth += 2;
		for ($i = 0x30; $i < 0x7C; $i++) {
			if (preg_match('/^([a-z0-9])/iS', chr($i), $chr)) {
				array_push($arr, $chr[1]);
			}
		}

		$count = count($arr);
		while (($depth--) >= 0) {
			for($i = 0; $i < $count; $i++) {
				for($j = 0; $j < $count -1; $j++) {
					if ($arr[$i] > $arr[$j + 1]) {
						$temp = $arr[$j];
						$arr[$j] = $arr[$j + 1];
						$arr[$j + 1] = $temp;
					}
				}
			}
		}

		return 	[
			array_splice($arr, 0, $count / 2),
			$arr
		];
	}
	
	public static function Decode($str = '', $isLink = false) {

		if (isset($str)) {
			$chr = self::arrHash(intval(substr($str, -1)));
			$str = str_replace('=', '', substr($str, $isLink ? 0 : 44, -1));

			for ($i = 0, $len = count($chr[0]); $i < $len; $i++) {
					$str = str_replace($chr[0][$i], '__', $str);
					$str = str_replace($chr[1][$i], $chr[0][$i], $str);
					$str = str_replace('__', $chr[1][$i], $str);
			}

			$str = base64_decode(strrev($str));
			return rawurldecode($str);
		}

	}

    public static function GetConfig($url = '') {

		if (empty($url)) return;
		$data = usParser::http($url, [
				"Content-Type: text/html",
				"Connection: close",
				"Referer: {$url}"
			]
		);

		if (empty($data)) {
			return;
		}

		if (($result = preg_match("#data:\s?'([a-z0-9-\+\/]+)'#mui", $data, $Arr) ? $Arr[1] : null) !== null) {
			$result =  json_decode(usParser::Decode($result));
		}

		return $result;
    }
	
	public static function http($url, $header = []) {
	    $ch =  curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);   
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
	    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	    curl_setopt($ch, CURLOPT_REFERER, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_VERBOSE, true);
	    
	    if (!empty($header)) {
	    	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	    }
	    
	    $result =  curl_exec($ch);
	    curl_close($ch);
		
	    if ($result){
	        return $result;
	    } else {
	        return '';
	    }
	}
}
