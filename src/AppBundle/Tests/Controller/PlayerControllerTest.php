<?php
/**
 * Created by PhpStorm.
 * User: slawek
 * Date: 07.04.18
 * Time: 23:48
 */

namespace AppBundle\Tests\Controller;

use AppBundle\Controller\Api\PlayerController;
use AppBundle\Test\ApiTestCase;

class PlayerControllerTest extends ApiTestCase
{
    public function testPOST()
    {
        $nickname = 'NowyGracz';

        $data = array(
            'nickname' => $nickname,
            'position' => rand(1, 5),
            'tagLine' => 'a test dev!'
        );

        //1. POST to create Player
        $response = $this->client->post('/player', array(
            'body' => json_encode($data)
        ));

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('/players/NowyGracz', $response->getHeader('Location'));
        $finishedData = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('nickname', $finishedData);
        $this->assertEquals('NowyGracz', $data['nickname']);

    }

    //clever default player
    public function testGETPlayer()
    {
        $this->createPlayer(array(
            'nickname' => 'tester',
            'position' => 4,
            'tagLine' => 'testTagLine'
        ));

        $response = $this->client->get('/players/tester');
        $this->assertEquals(200, $response->getStatusCode());
        $data = $response->json();
        $this->assertEquals(
            array(
                'nickname',
                'position',
                'tagLine'
            ),
            array_keys($data)
        );
    }

}
