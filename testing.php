<?php


require __DIR__ . '/vendor/autoload.php';

$client = new\GuzzleHttp\Client(array(
   'base_url' => 'http://localhost:8000',
   'defaults' => array(
       'exceptions' => false
   )
));


$nickname = 'NowyGracz' . rand(0, 999);
$data = array(
    'nickname' => $nickname,
    'position' => rand(1, 5),
    'tagLine' => 'a test dev!'
);

$response = $client->post('/player', array(
    'body' => json_encode($data)
));

echo $response;

echo "\n\n";