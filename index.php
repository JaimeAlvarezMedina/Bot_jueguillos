<?php
$token = '5296594085:AAHNqJohJUg2WAF_yCooXDx6P3TPOzD6ouE';
$website = 'https://api.telegram.org/bot'.$token;

$input = file_get_contents('php://input');
$update = json_decode($input, TRUE);

$chatId = $update['message']['chat']['id'];
$message = $update['message']['text'];

switch($message) {
    case '/start':
        $response ='iniciado';
        sendMessage($chatId, $response);
        break;
    case '/juegos':
        buscar_juegos($chatId);
        break;
    
    default:
        $response = 'Introduce uno de los comandos, no intentes explorar';
        sendMessage($chatId, $response);
        break;
}

function sendMessage($chatId, $response) {
    $url = $GLOBALS['website'].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response);
    file_get_contents($url);
}



?>