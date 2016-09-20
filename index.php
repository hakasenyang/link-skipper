<?php
	/**
	 * Redirect Bypass PHP
	 * any.gs and sh.st source by https://github.com/starbuck93/handy-link-skipper/blob/master/index.php
	 * adf.ly source by http://skizzerz.net/scripts/adfly.php
	 * Other source by Hakase ( contact@hakase.kr / https://hakase.kr/ )
	 */
	include_once '_function.php';
	$aa = new Redirect();
	$url = $_GET['url'];
	if($url) {
		$urls = parse_url($url);
		switch($urls['host'])
		{
			case 'goo.gl':
			case 'me2.do':
			case 'tinyurl.com':
			case 'bit.ly':
				$data = $aa->splits($aa->WEBParsing($url), 'Location: ', PHP_EOL);
				break;
			case 'durl.kr':
				$data = $aa->splits($aa->WEBParsing($url), '<h2><a href="', '" id="titleArea"');
				break;
			//any.gs and sh.st source by https://github.com/starbuck93/handy-link-skipper/blob/master/index.php
			case 'www.any.gs':
			case 'any.gs':
				$data = substr($url, strpos($url,"url/")+4);
				break;
			case 'sh.st':
				$data = $aa->WEBParsing($url);
				$data = $aa->splits($data, '<title>Redirecting to ', '</title>');
				break;
			// adf.ly source by http://skizzerz.net/scripts/adfly.php
			case 'adf.ly':
				$data = $aa->WEBParsing($url);
				if ( preg_match( "#var ysmm = '([a-zA-Z0-9+/=]+)'#", $data, $matches ) )
					$data = $final = $url = $aa->decode_adfly( $matches[1] );
				break;
			case 'adfoc.us':
				$data = $aa->WEBParsing($url, NULL, NULL, array('Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8'));
				$data = $aa->splits($data, 'var click_url = "', '";');
				break;
			case 'bc.vc':
				$data = $aa->WEBParsing($url);
				$aid = $aa->splits($data, '{aid:', ',');
				$lid = $aa->splits($data, ',lid:', ',');
				if(!$aid || !$lid) { unset($data); break; }
				$data = $aa->WEBParsing('http://bc.vc/fly/ajax.fly.php', NULL, 'opt=check_log&args%5Baid%5D='.$aid.'&args%5Blid%5D='.$lid, array('Referer: http://bc.vc/', 'X-Requested-With: XMLHttpRequest'));
				$cookie1 = $aa->splits($data, 'Set-Cookie: SITE_view_', PHP_EOL, 1);
				$cookie2 = $aa->splits($data, 'Set-Cookie: SITE_view_', PHP_EOL, 2);
				if(!$cookie1 || !$cookie2) { unset($data); break; }
				$cookie = 'SITE_view_'.$cookie1.';SITE_view_'.$cookie2.';';
				$data = $aa->WEBParsing('http://bc.vc/fly/ajax.fly.php', $cookie, 'opt=checks_log&args%5Baid%5D='.$aid.'&args%5Blid%5D='.$lid, array('Referer: http://bc.vc/', 'X-Requested-With: XMLHttpRequest'));
				$cookie = $cookie . 'PHPSESSID=' . $aa->splits($data, 'Set-Cookie: PHPSESSID=', ';') . ';';
				$data = $aa->WEBParsing('http://bc.vc/fly/ajax.fly.php', $cookie, 'opt=check_log&args%5Baid%5D='.$aid.'&args%5Blid%5D='.$lid, array('Referer: http://bc.vc/', 'X-Requested-With: XMLHttpRequest'));
				sleep(5); // server check time
				$data = $aa->WEBParsing('http://bc.vc/fly/ajax.fly.php', $cookie, 'opt=make_log&args%5Baid%5D='.$aid.'&args%5Blid%5D='.$lid, array('Referer: http://bc.vc/', 'X-Requested-With: XMLHttpRequest'));
				$data = json_decode('{'.$aa->splits($data, '{"error":false,"message":{', '}}').'}',true)['url'];
				break;
			default:
				echo 'Not supported. (E-mail: contact@hakase.kr)';
				break;
		}
		if($data) echo $data; else echo 'Error';
		exit;
	}
?>
<p>For example: <br>
<a href='?url=<?php echo urlencode('http://me2.do/G4AB9cJL');?>'>http://me2.do/G4AB9cJL</a><br>
<a href='?url=<?php echo urlencode('https://goo.gl/AIScrC');?>'>https://goo.gl/AIScrC</a><br>
<a href='?url=<?php echo urlencode('http://bit.ly/29O1WJt');?>'>http://bit.ly/29O1WJt</a><br>
<a href='?url=<?php echo urlencode('http://durl.kr/cpubjn');?>'>http://durl.kr/cpubjn</a></p>
<p>How to use? : ?url=URL<br>
Supported URL : me2.do / goo.gl / tinyurl.com / me2.do / durl.kr / adf.ly / sh.st / adfoc.us / bc.vc (wait for 5 seconds)</p>
