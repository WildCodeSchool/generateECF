<?php

namespace AppBundle\Controller;

use AppBundle\Entity\City;
use AppBundle\Entity\Promo;
use AppBundle\Entity\Student;
use AppBundle\Form\ChoosePromoType;
use AppBundle\Services\Zip;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use AppBundle\Services\WritePdf;
use Symfony\Component\HttpFoundation\Request;
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

        $promos = $em->getRepository(Promo::class)->getPromoByCityAndDate($city);

        return $this->render('default/includes/boxPromoResult.html.twig', [
            'promos' => $promos ?? [],
        ]);
    }



     /**
     * @return Response
     * @Route("/{city}", name="homepage")
     */
    public function indexAction(City $city = null)
    {
        $em = $this->getDoctrine()->getManager();
        $cities = $em->getRepository(City::class)->findBy([], ['name'=>'ASC']);

        return $this->render('default/index.html.twig', [
            'cities' => $cities,
            'city'   => $city
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
    public function generatePromoPdf(Promo $promo, WritePdf $writePdf, Zip $zip)
    {
        $em = $this->getDoctrine()->getManager();
        $students = $em->getRepository(Student::class)->findBy(array('promo' => $promo));

//        if ($promo->getEcfVersion() == Promo::OLD_ECF){
//            foreach ($students as $student) {
//                $writePdf->generatePdfOldVersion($student, $promo);
//            }
//        } else{
            foreach ($students as $student) {
                $writePdf->generatePdfNewVersion($student, $promo);
            }
//        }

        $zipInfos = $zip->zipFolder($promo);

        $response = new BinaryFileResponse($zipInfos['path_to_zip']);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $zipInfos['filename']);

        return $response;
    }

    /**
     * @param WritePdf $writePdf
     * @param Student $student
     * @return BinaryFileResponse
     * @throws \setasign\Fpdi\PdfReader\PdfReaderException
     *
     * @Route("/generate/promo/{promo}/student/{student}", name="generate_student_ecf")
     */
    public function generateStudentPdf(Promo $promo, Student $student, WritePdf $writePdf)
    {
//        if ($promo->getEcfVersion() == Promo::OLD_ECF){
//            return new BinaryFileResponse($writePdf->generatePdfOldVersion($student, $promo));
//
//        } else{
            return new BinaryFileResponse($writePdf->generatePdfNewVersion($student, $promo));
//        }
    }

    /**
     * @param Promo $promo
     * @return BinaryFileResponse
     *
     * @Route("/download/template/promo/{promo}", name="downloadTemplate")
     */
    public function downloadTemplateAction(Promo $promo){
        if ($promo->getEcfVersion() == Promo::ECF_PHP){
            return new BinaryFileResponse($this->getParameter('template_directory') . 'templateecf_php_fev2019.pdf');
        } elseif ($promo->getEcfVersion() == Promo::ECF_JS){
            return new BinaryFileResponse($this->getParameter('template_directory') . 'templateecf_js_fev2019.pdf');
        } elseif ($promo->getEcfVersion() == Promo::ECF_JAVA) {
            return new BinaryFileResponse($this->getParameter('template_directory') . 'templateecf_java_fev2019.pdf');
        } else{
            return new BinaryFileResponse($this->getParameter('template_directory') . 'templateecf_js_java_fev2019.pdf');
        }
    }
}
