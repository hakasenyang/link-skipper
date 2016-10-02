<?php
	/**
	 * Redirect Bypass Function PHP
	 * any.gs and sh.st source by https://github.com/starbuck93/handy-link-skipper/blob/master/index.php
	 * adf.ly source by http://skizzerz.net/scripts/adfly.php
	 * Other source by Hakase ( contact@hakase.kr / https://hakase.kr/ )
	 */
	class Redirect {
		private $httph = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36';
		public function splits($data, $first, $end, $num = 1)
		{
			$temp = explode($first, $data);
			$temp = explode($end, $temp[$num]);
			$temp = $temp[0];
			return $temp;
		}
		public function WEBParsing($url, $cookie = NULL, $postparam = NULL, $otherheader = NULL)
		{
			$uri = parse_url($url);
			$paramType = strtoupper($paramType);
			if (!$uri['port']) $uri['port'] = 80;
			if (!$uri['path']) $uri['path'] = "/";
			$ch = curl_init();
			$opts = array(CURLOPT_RETURNTRANSFER => true,
				CURLOPT_URL => $url,
				CURLOPT_TIMEOUT => 10,
				CURLOPT_CONNECTTIMEOUT => 5,
				CURLOPT_SSL_VERIFYPEER => FALSE,
				CURLOPT_HEADER => 1,
			);
			curl_setopt_array($ch, $opts);
			if ($uri['host'] != 'sh.st') curl_setopt($ch, CURLOPT_USERAGENT, $this->httph);
			if ($otherheader) curl_setopt($ch, CURLOPT_HTTPHEADER, $otherheader);
			if ($cookie) curl_setopt($ch, CURLOPT_COOKIE, $cookie);
			if ($postparam)
			{
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postparam);
			}

			$data = curl_exec($ch);
			curl_close($ch);
			return ($data) ? $data : false;
		}
		/**
		 * [decode_adfly adf.ly decode URL]
		 * @param  [string] $ysmm [adf.ly ysmm data]
		 * @return [string]       [Original URL]
		 * Source by http://skizzerz.net/scripts/adfly.php
		 */
		public function decode_adfly( $ysmm )
		{
			$left = '';
			$right = '';
			for ( $i = 0; $i < strlen( $ysmm ); $i++ )
			{
				if ( $i % 2 == 0 )
				{
					$left .= $ysmm[$i];
				}
				else
				{
					$right = $ysmm[$i] . $right;
				}
			}
			return substr( base64_decode( $left . $right ), 2 );
		}
	}
