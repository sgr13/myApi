<?php


require __DIR__ . '/vendor/autoload.php';

$client = new\GuzzleHttp\Client(array(
   'base_url' => 'http://localhost:8000',
   'defaults' => array(
       'exceptions' => false
   )
));

$response = $client->post('/player');

echo $response;

echo "\n\n";