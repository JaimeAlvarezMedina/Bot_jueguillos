<?php 
$token = '5157086336:AAGTbyTWlsvjqYuY1cTKkYAhzGEq11EQsIk'; 
$website = 'https://api.telegram.org/bot'.$token; 
 
$input = file_get_contents('php://input'); 
$update = json_decode($input, TRUE); 
 
$chatId = $update['message']['chat']['id']; 
$message = $update['message']['text']; 
$reply=$update['message']['reply_to_message']['text']; 

 
switch($message) { 
    case '/start': 
        $response = 'Iniciando...'; 
        sendMessage($chatId, $response,True); 
        break; 

    case '/juegos': 
        obtener_juegos($chatId); 
        break; 

    default: 
        $response = 'Aprende los comandos, no hay easter-egg'; 
        sendMessage($chatId, $response); 
        break; 
} 
 
function sendMessage($chatId, $response, $repl) { 
    if($repl==TRUE){ 
        $reply_mark=array('force_reply'=>True); 
        $url = $GLOBALS['website'].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&reply_markup='.json_encode($reply_mark).'&text='.urlencode($response); 
    } 
    else{
        $url = $GLOBALS['website'].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response); 
        file_get_contents($url); 
    }
        
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