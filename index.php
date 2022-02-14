<?php 
$token = '5157086336:AAGTbyTWlsvjqYuY1cTKkYAhzGEq11EQsIk'; 
$website = 'https://api.telegram.org/bot'.$token; 
 
$input = file_get_contents('php://input'); 
$update = json_decode($input, TRUE); 
 
$chatId = $update['message']['chat']['id']; 
$message = $update['message']['text']; 
 
switch($message) { 
    case '/start': 
        $response = 'Iniciando...'; 
        sendMessage($chatId, $response); 
        break; 

    case '/juegos': 
        obtener_juegos($chatId); 
        break; 

    case '/boton':
        $btn = new InlineKeyboardButton([
            'text' =--> 'Web Site',
            'url' => 'http://lostov.net16.net'
            ]);
            sendMessage($chatId, $btn)
        break;
    default: 
        $response = 'Aprende los comandos, no hay easter-egg'; 
        sendMessage($chatId, $response); 
        break; 
} 
 
function sendMessage($chatId, $response) { 
    $url = $GLOBALS['website'].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response); 
    file_get_contents($url); 
} 
 
function obtener_juegos($chatId){   
  
    $context = stream_context_create(array('http' =>  array('header' => 'Accept: application/xml'))); 
    $url = "https://www.metacritic.com/rss/games/pc"; 
  
    $xmlstring = file_get_contents($url, false, $context); 
  
    $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA); 
    $json = json_encode($xml); 
    $array = json_decode($json, TRUE); 
  
    for ($i=0; $i < 20; $i++) {  
        $titulos ="\n\n".$array['channel']['item'][$i]['title'].$array['channel']['item'][$i]['link']; 
        sendMessage($chatId, $titulos);
    } 
} 
 
 
?>