<?php 
$token = '5157086336:AAGTbyTWlsvjqYuY1cTKkYAhzGEq11EQsIk'; 
$website = 'https://api.telegram.org/bot'.$token; 
 
$input = file_get_contents('php://input'); 
$update = json_decode($input, TRUE); 
 
$chatId = $update['message']['chat']['id']; 
$message = $update['message']['text']; 
$reply=$update['message']['reply_to_message']['text']; 
$replay=explode(" ",$reply);
$keyboard = array('keyboard' =>
            array(array(
                array('text'=>'/juegos','callback_data'=>"1"),
            ),
                array(
                    array('text'=>'/easter egg','callback_data'=>"4")
                )), 'one_time_keyboard' => true, 'resize_keyboard' => true
        );
        file_get_contents('https://api.telegram.org/bot5157086336:AAGTbyTWlsvjqYuY1cTKkYAhzGEq11EQsIk/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&reply_markup='.json_encode($keyboard).'&text=Elija que desea hacer');
         
if(empty($reply)){
    switch($message) { 
    case '/start': 
        
        break; 

    case '/juegos': 
        $response='¿De que plataforma quieres las criticas?';
        sendMessage($chatId, $response,TRUE); 
        break;
    case '/easter egg':
        $response='Que no hay nada curiosona';
        sendMessage($chatId, $response,false); 
        break;
    default: 
        $response = 'Aprende los comandos, no hay easter-egg'; 
        sendMessage($chatId, $response, FALSE); 
        break; 
    } 
} 
else{
    $message=strtolower($message);
        switch($message){
            case "pc":
                obtener_juegos($chatId,1);
                break;
            case "ps3":
                obtener_juegos($chatId,2);
                break;
            case "wii u":
                obtener_juegos($chatId,3);
                break;
            case "xbox 360":
                obtener_juegos($chatId,4);
                break;
            case "3ds":
                obtener_juegos($chatId,5);
                break; 
            case "psp":
                obtener_juegos($chatId,6);
                break; 
            case "ds":
                obtener_juegos($chatId,7);
                break;   
            case "ios":
                obtener_juegos($chatId,8);
                break;   
            case "wii":
                obtener_juegos($chatId,9);
                break; 
            case "ps4":
                obtener_juegos($chatId,10);
                break; 
            case "xbox one":
                obtener_juegos($chatId,11);
                break; 
            case "ps vita":
                obtener_juegos($chatId,12);
                break; 
                
            default:
                $response = 'Esa plataforma no esta disponible, para acceder a las que estan disponibles, escibe /help'; 
                sendMessage($chatId, $response, TRUE); 
                break;
        }
    
    
}

 
function sendMessage($chatId, $response, $repl) { 
    if($repl==TRUE){ 
        $reply_mark=array('force_reply'=>True); 
        $url = $GLOBALS['website'].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&reply_markup='.json_encode($reply_mark).'&text='.urlencode($response); 
    } 
    else $url = $GLOBALS['website'].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response); 
    file_get_contents($url); 
} 
 
function obtener_juegos($chatId, $plataforma){   
  
    $context = stream_context_create(array('http' =>  array('header' => 'Accept: application/xml'))); 
    switch($plataforma){
        case 1:
            $url = "https://www.metacritic.com/rss/games/pc"; 
            break;
        case 2:
            $url = "https://www.metacritic.com/rss/games/ps3";
            break;
        case 3:
            $url = "https://www.metacritic.com/rss/games/wii-u";
            break;
        case 4:
            $url = "https://www.metacritic.com/rss/games/xbox360";
            break;
        case 5:
            $url = "https://www.metacritic.com/rss/games/3ds";
            break;
        case 6:
            $url = "https://www.metacritic.com/rss/games/psp";
            break;
        case 7:
            $url = "https://www.metacritic.com/rss/games/ds";
            break;
        case 8:
            $url = "https://www.metacritic.com/rss/games/ios";
            break;
        case 9:
            $url = "https://www.metacritic.com/rss/games/wii";
            break;
        case 10:
            $url = "https://www.metacritic.com/rss/games/ps4";
            break;
        case 11:
            $url = "https://www.metacritic.com/rss/games/xboxone";
            break;
        case 12:
            $url = "https://www.metacritic.com/rss/games/vita";
            break;
    }
    
  
    $xmlstring = file_get_contents($url, false, $context); 
  
    $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA); 
    $json = json_encode($xml); 
    $array = json_decode($json, TRUE); 
  
    for ($i=0; $i < 10; $i++) {  
        $titulos ="\n\n".$array['channel']['item'][$i]['title'].$array['channel']['item'][$i]['link']; 
        sendMessage($chatId, $titulos,false);
    } 
} 
 
 
?>