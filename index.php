<?php 
$token = '5234948616:AAEDodKPmmzuOlShdITBu8Wb0B8W9m-dL5M'; 
$website = 'https://api.telegram.org/bot'.$token; 
 
$input = file_get_contents('php://input'); 
$update = json_decode($input, TRUE); 
 
$chatId = $update['message']['chat']['id']; 
$message = $update['message']['text']; 
$repl=$update['message']['reply_to_message']['text']; 
 
switch($message) { 
    case '/start': 
        $response = 'Me has iniciado'; 
        sendMessage($chatId, $response,True); 
        break; 
    case '/video': 
        $response = 'https://vdmedia.elpais.com/elpaistop/multimedia/20222/13/20220213235721471_1644793109_video_1800.mp4'; 
        sendVideo($chatId, $response,True); 
        break; 
    case '/audio': 
        $response = 'https://www.youtube.com/watch?v=wlPl-rP6j2g'; 
        sendAudio($chatId, $response); 
        break;     
    case '/noticias': 
        getNoticias($chatId); 
        break; 
    case '/info': 
        $response = 'Hola! Soy @ruben'; 
        sendMessage($chatId, $response,True); 
        break; 
    default: 
        $response = 'No te he entendido'; 
        sendMessage($chatId, $response); 
        break; 
} 
 
function sendMessage($chatId, $response, $repl) { 
    if($repl==TRUE){ 
        $reply_mark=array('force_reply'=>True); 
        $url = $GLOBALS['website'].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&reply_markup='.json_encode($reply_mark).'&text='.urlencode($response); 
    } 
    else $url = $GLOBALS['website'].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response); 
    file_get_contents($url); 
} 
 
 
function sendVideo($chatId, $response) { 
    $url = $GLOBALS['website'].'/sendVideo?chat_id='.$chatId.'&parse_mode=HTML&video='.urlencode($response); 
    file_get_contents($url); 
} 
function sendAudio($chatId, $response) { 
    $url = $GLOBALS['website'].'/sendAudio?chat_id='.$chatId.'&parse_mode=HTML&audio='.urlencode($response); 
    file_get_contents($url); 
} 
function getNoticias($chatId){   
  
    $context = stream_context_create(array('http' =>  array('header' => 'Accept: application/xml'))); 
    $url = "https://feeds.elpais.com/mrss-s/pages/ep/site/elpais.com/portada"; 
  
    $xmlstring = file_get_contents($url, false, $context); 
  
    $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA); 
    $json = json_encode($xml); 
    $array = json_decode($json, TRUE); 
  
     
        $titulos = $titulos."\n\n".$array['channel']['item'][0]['title'].$array['channel']['item'][0]['media:url']; 
     
  
    sendVideo($chatId, $titulos); 
 
} 
 
 
?>