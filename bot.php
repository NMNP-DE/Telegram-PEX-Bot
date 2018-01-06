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
					sendMessage($chatID, "You can get with */help* a list of all Commands!");
					break;
				case "/help":
					sendMessage($chatID, "*/info*" . PHP_EOL . "*/onair*" . PHP_EOL . "*/listeners*" . PHP_EOL . "*/recordholder*" . PHP_EOL . "*/record*");
					break;
				case "/info":
					sendMessage($chatID, "OnAir: *" . $streamstatus['onair_name'] . "*" . PHP_EOL . "Zuhörer: *" . $streamstatus['listeners'] . "*" . PHP_EOL "Song: *" . $streamstatus['song'] . "*" . PHP_EOL . "Rekordhalter: *" . $streamstatus['peak_name'] . "*" . PHP_EOL . "Zuhörer Rekord: *" . $streamstatus['peak_count'] . "*");
					break;
				case "/onair":
					sendMessage($chatID, "*" . $streamstatus['onair_name'] . "* is currently OnAir!");
					break;
				case "/song":
					sendMessage($chatID, "The current Song is: " . PHP_EOL . "*" . $streamstatus['song'] . "*");
					break;
				case "/listeners":
					sendMessage($chatID, "Currently *" . $streamstatus['listeners'] . "* listeners!");
					break;
				case "/recordholder":
					sendMessage($chatID, "*" . $streamstatus['peak_name'] . "* is the current record holder!");
					break;
				case "/record":
					sendMessage($chatID, "*" . $streamstatus['peak_count'] . "* is the current listener record!");
					break;
				default:
					sendMessage($chatID, "Command not found!");
					break;
			}
		}
	} else {
		sendMessage($chatID, "Our Telegram Bot is currently in Maintenance!*" . PHP_EOL . "Try again later! ;)");
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

