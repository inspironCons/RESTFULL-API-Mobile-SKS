<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Telegram {
    
    public function send($chat_id,$message){
        // initialise variables here
        $token = '690050890:AAHts5NmFxnU9fOrk3HxySaB-D5YN7wN0RE';
        $id = $chat_id;
        // path to the picture, 
        $text = $message;

        $data = array(
            'chat_id' =>$id,
            'text' =>$text
        );

        $request_url = 'https://api.telegram.org/bot'.$token.'/sendMessage?'.http_build_query($data);

        //  open connection
        $ch = curl_init();
        //  set the url
        curl_setopt($ch, CURLOPT_URL, $request_url);
        //  To display result of curl
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //  execute post
        $result = curl_exec($ch);
        curl_close($ch);

        // return json_decode($result, true);
        return $request_url;
    }

}