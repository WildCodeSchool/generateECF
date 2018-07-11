<?php

namespace AppBundle\Controller;

use AppBundle\Entity\City;
use AppBundle\Entity\Promo;
use AppBundle\Entity\Student;
use AppBundle\Services\Zip;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Services\WritePdf;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class HomeController extends Controller
{
    /**
     * @return Response
     */
    public function listPromosAction(City $city = null)
    {
        $em = $this->getDoctrine()->getManager();

        $promos = $em->getRepository(Promo::class)->findBy(['city' => $city], ['langage' => 'ASC', 'name' => 'ASC']);
        return $this->render('default/includes/boxPromoResult.html.twig', [
            'promos' => $promos ?? [],
        ]);

    }

     /**
     * @return Response
     * @Route("/city/{city}", name="homepage")
     */
    public function indexAction(City $city = null)
    {
        $em = $this->getDoctrine()->getManager();
        $cities = $em->getRepository(City::class)->findBy([], ['name'=>'ASC']);

        return $this->render('default/index.html.twig', [
            'cities' => $cities,
            'city'   => $city,
        ]);

    }

    /**
     * @param WritePdf $writePdf
     * @param Zip $zip
     * @param Promo $promo
     * @return BinaryFileResponse
     * @throws \setasign\Fpdi\PdfReader\PdfReaderException
     *
     * @Route("/generate/{promo}", name="generate_ecf")
     */
    public function generatePdf(Promo $promo, WritePdf $writePdf, Zip $zip)
    {
        $em = $this->getDoctrine()->getManager();
        $students = $em->getRepository(Student::class)->findBy(array('promo' => $promo));

        foreach ($students as $student) {
            $writePdf->generatePdf($student);
        }

        $zipInfos = $zip->zipFolder($promo);

        $response = new BinaryFileResponse($zipInfos['path_to_zip']);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $zipInfos['filename']);

        return $response;
    }
}
