<?php

/***
 *       _____          _        _             _____                        _      
 *      / ____|        | |      | |           |  __ \                      (_)     
 *     | |     ___   __| | ___  | |__  _   _  | |  | | ___  ___  ___  _ __  ___  __
 *     | |    / _ \ / _` |/ _ \ | '_ \| | | | | |  | |/ _ \/ _ \/ _ \| '_ \| \ \/ /
 *     | |___| (_) | (_| |  __/ | |_) | |_| | | |__| |  __/  __/ (_) | | | | |>  < 
 *      \_____\___/ \__,_|\___| |_.__/ \__, | |_____/ \___|\___|\___/|_| |_|_/_/\_\
 *                                      __/ |                                      
 *                                     |___/                                       
 */

require 'panel/pex.config.php';
require 'panel/pex.class.php';
$pex = new PEX($pexconf);
$streamstatus = $pex->get_stream_data() or die($pex->error());

$maintenance = false;
$botToken = "";
$website = "https://api.telegram.org/bot" . $botToken;

$update = json_decode(file_get_contents("php://input"), true);
$chatID = $update['message']['chat']['id'];
$cmd = $update['message']['text'];
$botInfo = getMe();
if(!$botInfo['ok']) {return;}
$botName = $botInfo['result']['username'];

if($chatID !== null){
	if($maintenance !== true) {
		if($cmd{0} == "/") {
			switch($cmd) {
				case "/start":
					sendMessage($chatID, "Mit */help* erhältst du eine Kommando-Übersicht!");
					break;
				case "/help":
					sendMessage($chatID, "*/info*" . PHP_EOL . "*/onair*" . PHP_EOL . "*/zuhorer*" . PHP_EOL . "*/rekordhalter*" . PHP_EOL . "*/rekord*");
					break;
				case "/info":
					sendMessage($chatID, "OnAir: *" . $streamstatus['onair_name'] . "*" . PHP_EOL . "Zuhörer: *" . $streamstatus['listeners'] . "*" . PHP_EOL . "Rekordhalter: *" . $streamstatus['peak_name'] . "*" . PHP_EOL . "Zuhörer Rekord: *" . $streamstatus['peak_count'] . "*");
					break;
				case "/onair":
					sendMessage($chatID, "*" . $streamstatus['onair_name'] . "* sendet grade!");
					break;
				case "/zuhorer":
					sendMessage($chatID, "Es hören momentan *" . $streamstatus['listeners'] . "* Leute zu!");
					break;
				case "/rekordhalter":
					sendMessage($chatID, "*" . $streamstatus['peak_name'] . "* ist der momentane Rekordhalter!");
					break;
				case "/rekord":
					sendMessage($chatID, "*" . $streamstatus['peak_count'] . "* ist der momentane Zuhörer Rekord!");
					break;
				default:
					sendMessage($chatID, "Kommando nicht gefunden!");
					break;
			}
		}
	} else {
		sendMessage($chatID, "Unser Telegram Bot befindet sich momentan in *Wartungsarbeiten!*" . PHP_EOL . "Versuche es später nochmal! ;)");
	}
}

/***
 *        _  _           _____ _____ 
 *      _| || |_   /\   |  __ \_   _|
 *     |_  __  _| /  \  | |__) || |  
 *      _| || |_ / /\ \ |  ___/ | |  
 *     |_  __  _/ ____ \| |    _| |_ 
 *       |_||_|/_/    \_\_|   |_____|
 *                                   
 *                                   
 */

function getMe() {
	$url = $GLOBALS['website'] . "/getMe";
	return json_decode(file_get_contents($url), true);
}

function sendMessage($chatId, $message) {
	$url = $GLOBALS['website'] . "/sendMessage?chat_id=" . $chatId  . "&parse_mode=markdown" . "&text=" . urlencode($message);
	file_get_contents($url);
}
 
?>

