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
use AppBundle\Form\UpdatePlayerType;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
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

        $player = new Player();
        $form = $this->createForm(new PlayerType(), $player);
        $this->processForm($request, $form);

        if(!$form->isValid()) {

//            ustawiam header, ktory umozliwi odczytanie dump'a w terminalu
//            header('Content-Type: cli');
//            dump((string)$form->getErrors(true, false)); die;

            $erors = $this->getErrorsFromForm($form);

            $data = [
                'type' => 'validation_error',
                'title' => 'There was a validation error',
                'errors' => $erors
            ];

            return new JsonResponse($data, 400);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($player);
        $em->flush();

        $location = $this->generateUrl('players_show', array(
            'nickname' => $player->getNickname()
        ));

        $response = $this->createApiResponse($player, 201);
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

        $response = $this->createApiResponse($player);

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

        $json = array('players' => array());

        $json = $this->serialize(array('players' => $players));

        $response =  new Response($json);

        return $response;
    }

    /**
     * @Route("/players/{nickname}", name="players_put")
     * @Method({"PUT", "PATCH"})
     */
    public function updateAction(Player $player, Request $request)
    {
        if (!$player) {
            throw $this->createNotFoundException('No player with that nickname!');
        }

        $form = $this->createForm(new UpdatePlayerType(), $player);
        $this->processForm($request, $form);

        $em = $this->getDoctrine()->getManager();
        $em->persist($player);
        $em->flush();

        $response = $this->createApiResponse($player);

        return $response;

    }

    /**
     * @Route("/players/{nickname}")
     * @Method("DELETE")
     */
    public function deleteAction(Player $player)
    {
        if ($player) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($player);
            $em->flush();
        }

        return new Response(null, 204);
    }

    private function processForm(Request $request, FormInterface $form)
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($data, $clearMissing);
    }

    private function serialize($data)
    {
        $contex = new SerializationContext();
        $contex->setSerializeNull(true);

        return $this->container->get('jms_serializer')
            ->serialize($data, 'json', $contex);
    }
    
    protected function createApiResponse($data, $statusCode = 200)
    {
        $json = $this->serialize($data);
        
        return new Response($json, $statusCode, [
            'Content-Type' => 'application/json'
        ]);
    }

    private function debug($element)
    {
        echo '<pre>';
        print_r($element);
        echo '</pre>';
    }

    private function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }
}