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
        $nickname = 'NowyGracz' . rand(0, 999);


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
        $this->assertTrue($response->hasHeader('Location'));
        $finishedData = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('nickname', $finishedData);

    }

}
