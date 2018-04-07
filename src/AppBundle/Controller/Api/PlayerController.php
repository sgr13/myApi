<?php
/**
 * Created by PhpStorm.
 * User: slawek
 * Date: 07.04.18
 * Time: 21:47
 */

namespace AppBundle\Controller\Api;


use AppBundle\Entity\Player;
use AppBundle\Form\PlayerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PlayerController extends Controller
{
    /**
     * @Route("/player", name="player")
     *
     */
    public function playerAction(Request $request)
    {
        $body = $request->getContent();
        $data = json_decode($body, true); //true zapewnia że dostaniemy tablice a nie obiekt

//        $data = array(
//            'nickname' => 'slavko',
//            'position' => rand(1, 5),
//            'tagLine' => 'a test dev!'
//        );

        $player = new Player();
        $form = $this->createForm(new PlayerType(), $player);
        $form->submit($data);  //zamiast zapisu form->handleRequest

        $em = $this->getDoctrine()->getManager();
        $em->persist($player);
        $em->flush();

        $response = new Response('It worked!!!', 201);
        $response->headers->set('Location', '/some/player/url');

        return $response;
    }
}