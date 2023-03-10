<?php



error_reporting(0);

require 'usparser.php';

//header('Content-type: text/json');
$URL = 'https://red.uboost.one/view/e4ddeac60ea001c63fb7fa9ce27a77c0/50953';
$HASH = preg_match('#([a-f0-9]{32})#iS', $URL, $h) ? $h[1] : 'e4ddeac60ea001c63fb7fa9ce27a77c0';

$data = usParser::GetConfig($URL);
if (isset($data)) {
	$data = json_encode([
		'allow' => $_SERVER['HTTP_HOST'],
		'media' => $data->media
	]);
	$data = usParser::Encode($HASH) . usParser::Encode($data);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	
</head>
<body>
	<div id="player"></div>
	<script src="https://red.uboost.one/playerjs/initPlayer.js"></script>
	<script type="text/javascript">
			var zp = new zPlayer({
				id: 'player',
				autoSwitch: true,
				p2pEnabled: false,
				preload: false,
				data: '<?=$data?>'
			});
		</script>
</body>
</html>