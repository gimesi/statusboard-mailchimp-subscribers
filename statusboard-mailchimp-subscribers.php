<?php

/*
 * statusboard-mailchimp-subscribers - Show the number of subscribers of a Mailchimp Newsletter on your Panic Status Board.
 * https://github.com/gimesi/statusboard-mailchimp-subscribers
 * author: Thomas Gimesi < Twitter: @gimesi >
 *
 * NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.
 * 
 * Contains modified html/css based on Panic Status Board's DIY example,
 * as well as the 
 * "Mini MailChimp API v2 wrapper" by Drew McLellan
 * to be found at https://github.com/drewm/mailchimp-api
 *
 * For further info on Status Board see http://panic.com/statusboard
 */

class MailChimp
{
	private $api_key;
	private $api_endpoint = 'https://<dc>.api.mailchimp.com/2.0/';

	function __construct($api_key)
	{
		$this->api_key = $api_key;
		list(, $datacentre) = explode('-', $api_key);
		$this->api_endpoint = str_replace('<dc>', $datacentre, $this->api_endpoint);
	}

	public function call($method, $args=array())
	{
		return $this->_raw_request($method, $args);
	}

	private function _raw_request($method, $args=array())
	{      
		$args['apikey'] = $this->api_key;

		$url = $this->api_endpoint.'/'.$method.'.json';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($args));
		$result = curl_exec($ch);
		curl_close($ch);

		return $result ? json_decode($result, true) : false;
	}

}

$MailChimp = new MailChimp('YOURAPIKEY'); // enter your API-Key here
$member_count = $MailChimp->call('lists/list');
?>

<?php echo $member_count['data'][0]['stats']['member_count']; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
		<meta http-equiv="Cache-control" content="no-cache" />	
		<style type="text/css">
			@font-face { font-family: "Roadgeek2005SeriesD";
						 src: url("http://panic.com/fonts/Roadgeek 2005 Series D/Roadgeek 2005 Series D.otf"); }		
			body, * { }
			body, div, dl, dt, dd, ul, ol, li, h1, h2, h3, h4, h5, h6, pre, form, fieldset, input, textarea, p, blockquote, th, td { margin: 0;
			                                                                                                                         padding: 0; }
			fieldset, img { border: 0; }
			html, body, #main { overflow: hidden; }
			body { color: white;
			       font-family: 'Roadgeek2005SeriesC', sans-serif;
			       font-size: 24px;
			       line-height: 28px; }
			body, html, #main { background: transparent !important; }
			#container { width: 250px;
			             height: 250px;
			             text-align: center; }
			#container * { font-weight: normal; }
			h1 { font-size: 100px;
			     line-height: 120px;
			     margin-top: 15px;
			     margin-bottom: 28px;
			     color: white;
			     text-shadow: 0px -2px 0px black;
			     text-transform: uppercase; }
			h2 { width: 180px;
			     margin: 0px auto;
			     padding-top: 20px;
			     font-size: 20px;
			     line-height: 22px;
			     color: #7e7e7e;
			     text-transform: uppercase; }
			h2 span { font-size: 24px;
			          color: white; }
		</style>
	</head>
	<body onload="init()">
		<div id="main">
			<div id="container">
				<h2>Subscribers on<br /><span>Mailchimp</span></h2>
				<h1><?php echo $member_count['data'][0]['stats']['member_count']; ?></h1>
			</div>
		</div>
	</body>
</html>