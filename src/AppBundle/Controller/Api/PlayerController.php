<?php
/**
 * Created by PhpStorm.
 * User: slawek
 * Date: 07.04.18
 * Time: 21:47
 */

namespace AppBundle\Controller\Api;


use AppBundle\Entity\Player;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PlayerController extends Controller
{
    /**
     * @Route("/player", name="player")
     * @Method("POST")
     */
    public function playerAction(Request $request)
    {
        $body = $request->getContent();
        $data = json_decode($body, true); //tru zapewnia że dostaniemy tablice a nie obiekt

        $player = new Player($data['nickname'], $data['age']);
        $player->setTagLine($data['tagLine']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($player);
        $em->flush();

        return new Response('It worked!!!');
    }
}