<?php
/**
 * Created by PhpStorm.
 * User: florian
 * Date: 17/07/18
 * Time: 21:56
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Promo;
use GuzzleHttp\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PromoController
 * @package AppBundle\Controller
 *
 * @Route("/promo")
 */
class PromoController extends Controller
{
    /**
     * @param Promo $promo
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @Route("/edit/{id}", name="promo_edit")
     * @Method({"GET", "POST"})
     */
    public function editPromo(Promo $promo, Request $request){

        $client = new Client();
        $res = $client->request('GET', 'https://odyssey.wildcodeschool.fr/api/v2/crews', [
            'headers' => [
                'Authorization' => $this->getParameter('key_api_odyssey')
            ]
        ]);
        $crews = json_decode($res->getBody()->getContents());

        $trainers = [];
        foreach ($crews as $crew){
            if ($crew->name == $promo->getName()){
                foreach ($crew->trainers as $t){
                    $trainers[trim(preg_replace('/[^A-Za-z0-9\-]/', ' ', $t->fullname))] = trim(preg_replace('/[^A-Za-z0-9\-]/', ' ', $t->fullname));
                }
            }
        }

        $editForm = $this->createForm('AppBundle\Form\PromoType', $promo, array(
            'trainers' => $trainers
        ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('notice', 'Promo mise a jour');

            return $this->redirectToRoute('student_index', array('promo' => $promo->getId()));
        }

        return $this->render('promo/edit.html.twig', array(
            'promo' => $promo,
            'edit_form' => $editForm->createView(),
        ));
    }
}