<?php
/**
 * Created by PhpStorm.
 * User: slawek
 * Date: 08.04.18
 * Time: 04:44
 */

namespace AppBundle\Test;

use GuzzleHttp\Client;

class ApiTestCase extends \PHPUnit_Framework_TestCase
{
    private static $staticClient;

    /**
     * @var $client
     */
    protected $client;

    //wywoływany raz przez testami - zapewnia że Client tworzony jest tylko raz
    public static function setUpBeforeClass()
    {
        self::$staticClient = new Client(array(
            'base_url' => 'http://localhost:8000',
            'defaults' => array(
                'exceptions' => false
            )
        ));
    }

    //ustawienie jako non-static property
    public function setUp()
    {
        $this->client = self::$staticClient;
    }

}