<?php



error_reporting(0);

require 'usparser.php';

header('Content-type: text/json');

$data = usParser::GetConfig('https://red.uboost.one/view/e4ddeac60ea001c63fb7fa9ce27a77c0/50953');
if (isset($data)) {
	echo(json_encode(empty($data->media->playlist) ? $data->media->film : $data->media));
}


