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
        $this->asserter()->assertResponsePropertiesExist($response, array(
            'nickname',
            'position',
            'tagLine'
        ));

        $this
            ->asserter()
            ->assertResponsePropertyEquals($response, 'nickname', 'tester');

    }

    public function testGETPlayersCollection()
    {
        $this->createPlayer(array(
            'nickname' => 'tester',
            'position' => 4,
            'tagLine' => 'testTagLine'
        ));

        $this->createPlayer(array(
            'nickname' => 'slawek',
            'position' => 2,
            'tagLine' => 'testTagLine'
        ));

        $response = $this->client->get('/players');
        $this->assertEquals(200, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyIsArray($response, 'players');
        $this->asserter()->assertResponsePropertyCount($response, 'players', 2);
        $this->asserter()->assertResponsePropertyEquals($response, 'players[1].nickname', 'slawek');
    }

    public function testPUTPlayer()
    {
        $this->createPlayer(array(
            'nickname' => 'slawek',
            'position' => 2,
            'tagLine' => 'foo'
        ));

        $data = array(
            'nickname' => 'kewals',
            'position' => 3,
            'tagLine' => 'foo'
        );

        $response = $this->client->put('/players/slawek', array(
            'body' => json_encode($data)
        ));

        $this->assertEquals(200, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyEquals($response, 'position', 3);
        $this->asserter()->assertResponsePropertyEquals($response, 'nickname', 'slawek');
    }
}
