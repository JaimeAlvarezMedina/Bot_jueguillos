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

function buscar_juegos($chatId){
    $context = stream_context_create(array('http' =>  array('header' => 'Accept: application/xml')));
    $url="https://www.metacritic.com/rss/games/pc";

    $xmlstring = file_get_contents($url,false,$context);

    $xml= simplexml_load_string($xmlstring, "SimpleXMLElement",LIBXML_NOCDATA);
    $json=json_encode($xml);
    $array=json_decode($json,TRUE);
    
    for ($i=0; $i < 9; $i++) { 
        $link=$array['channel']['item'][$i]['link'];
        $response=$response."\n\n".$array['channel']['item'][$i]['title']."<a href='".$link."'> +info</a>";
    }

    sendMessage($chatId, $response);

}

?>