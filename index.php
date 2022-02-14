<?php 
$token = '5296594085:AAHNqJohJUg2WAF_yCooXDx6P3TPOzD6ouE'; 
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
    case '/noticias': 
            getNoticias($chatId); 
        break; 
    case '/info': 
        $response = 'Hola! Soy @ruben'; 
        sendMessage($chatId, $response); 
        break; 
    default: 
        $response = 'No te he entendido'; 
        sendMessage($chatId, $response); 
        break; 
} 
 
function sendMessage($chatId, $response) { 
    $url = $GLOBALS['website'].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response); 
    file_get_contents($url); 
} 
 
function getNoticias($chatId){   
  
    $context = stream_context_create(array('http' =>  array('header' => 'Accept: application/xml'))); 
    $url = "https://www.metacritic.com/rss/games/pc.xml"; 
  
    $xmlstring = file_get_contents($url, false, $context); 
  
    $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA); 
    $json = json_encode($xml); 
    $array = json_decode($json, TRUE); 
  
    for ($i=0; $i < 9; $i++) {  
        $titulos ="\n\n".$array['channel']['item'][$i]['title'].$array['channel']['item'][$i]['link']; 
        sendMessage($chatId, $titulos);
    } 
  
     
 
} 
 
 
?>