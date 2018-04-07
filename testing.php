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

//1. POST to create Player
$response = $client->post('/player', array(
    'body' => json_encode($data)
));

//echo $response;
//
//echo "\n\n";
//
//die;

//$nicknameToGet = 'NowyGracz99';

$playerUrl = $response->getHeader('Location');

//2. GET to fetch the player
$response = $client->get($playerUrl);


//3.GET a collection
$response = $client->get('/players');


echo $response;

echo "\n\n";