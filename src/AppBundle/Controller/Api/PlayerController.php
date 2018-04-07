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
use Symfony\Component\HttpFoundation\JsonResponse;
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

        $location = $this->generateUrl('players_show', array(
            'nickname' => $player->getNickname()
        ));

        $data = $this->serializePlayer($player);

        $response = new JsonResponse($data, 201);
        $response->headers->set('Location', $location);  //na wypadek gdy potrzebny jest adres do nowego resources

        return $response;
    }

    /**
     * @Route("/players/{nickname}", name="players_show")
     * @Method("GET")
     */
    public function showAction(Player $player)
    {
        if (!$player) {
            throw $this->createNotFoundException('No player with that nickname!');
        }

        $data = $this->serializePlayer($player);

        $response =  new JsonResponse($data);

        return $response;
    }

    /**
     * @Route("/players")
     * @Method("GET")
     */
    public function listAction()
    {
        $players = $this->getDoctrine()
            ->getRepository('AppBundle:Player')
            ->findAll();

        $data = array('players' => array());

        foreach ($players as $player) {
            $data['players'][] = $this->serializePlayer($player);
        }

        $response =  new JsonResponse($data);

        return $response;
    }

    private function serializePlayer(Player $player)
    {
        return array(
            'nickname' => $player->getNickname(),
            'avatarNumber' => $player->getPosition(),
            'tagLine' => $player->getTagLine()
        );
    }
}