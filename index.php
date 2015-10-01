<?php
require __DIR__ . '/source/Jacwright/RestServer/RestServer.php';
require 'TestController.php';
require 'restclient.php';

$server = new \Jacwright\RestServer\RestServer('debug');
$server->addClass('TestController');
$server->handle();


  function curPageURL() {
        $pageURL = 'http';
    
        $pageURL .= "://";
    
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }
        return $pageURL;
  }

 if(curPageURL() == "http://10.10.100.32/tugas2/client/meow.jpg"){
        $api = new RestClient(array(
            'base_url' => "https://10.10.100.32/", 
            'format' => "json",
        ));

        $result = $api->get("tugas2/server/meow.jpg", array('q' => "#php"));
            if($result->info->http_code == 200){
                  echo var_dump($result->decode_response());
            }
  }
?>
