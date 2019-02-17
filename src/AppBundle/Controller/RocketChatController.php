<?php
/**
 * Created by PhpStorm.
 * User: florian
 * Date: 15/02/19
 * Time: 22:29
 */

namespace AppBundle\Controller;


use GuzzleHttp\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RocketChatController extends Controller
{
    /**
     * @Route("/rocket/channels", name="rocket")
     */
    public function getChannels(){

        $client = new Client([
            'base_uri' => 'https://chat.wildcodeschool.fr'
        ]);


        $res = $client->request('GET', '/api/v1/channels.list', [
            'headers' => [
                'X-Auth-Token' => 'tYzlbUiAGiY-2cTlPcancQngv6InaJENuE_-MAbuqlh',
                'X-User-Id'  => 'gbs3QBT57sQvrfGvf',
            ]
        ]);

        $rooms = $client->request('GET', '/api/v1/groups.listAll', [
            'headers' => [
                'X-Auth-Token' => 'tYzlbUiAGiY-2cTlPcancQngv6InaJENuE_-MAbuqlh',
                'X-User-Id'  => 'gbs3QBT57sQvrfGvf',
            ]
        ]);

        $crews = json_decode($res->getBody()->getContents());
        $rooms = json_decode($rooms->getBody()->getContents());

        dump($rooms); die();

        return $this->render("rocketchat/listAllChannel.html.twig", array(
            "crews" => $crews->channels
        ));
    }

    /**
     * @Route("/rocket/users", name="rocket")
     */
    public function getUsers(){

        $client = new Client([
            'base_uri' => 'https://chat.wildcodeschool.fr'
        ]);


        $users = $client->request('GET', '/api/v1/users.list?count=0', [
            'headers' => [
                'X-Auth-Token' => 'tYzlbUiAGiY-2cTlPcancQngv6InaJENuE_-MAbuqlh',
                'X-User-Id'  => 'gbs3QBT57sQvrfGvf',
            ]
        ]);

        $users = json_decode($users->getBody()->getContents());

        return $this->render("rocketchat/listAllUsers.html.twig", array(
            "users" => $users->users
        ));
    }
}