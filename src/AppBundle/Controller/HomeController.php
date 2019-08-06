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
        $cities = $em->getRepository(City::class)->findBy([], ['name' => 'ASC']);

        return $this->render('default/index.html.twig', [
            'cities' => $cities,
            'city'   => $city,
        ]);

    }

    /**
     *
     * @Route("/send-mail/{promo}", name="send_mail_ecf")
     */
    public function sendEcfMailPromo(Promo $promo, WritePdf $writePdf, \Swift_Mailer $mailer)
    {
        $em = $this->getDoctrine()->getManager();
        $students = $em->getRepository(Student::class)->findBy(array('promo' => $promo));

        foreach ($students as $student) {
            $message = (new \Swift_Message('ECF - ' . $student->getFirstname() . ' ' . $student->getName()))
                ->setFrom($this->getParameter('mailer_user'))
                ->setTo($student->getEmail())
                ->setBody(
                    $this->renderView('email/sendEcf.html.twig', ['student' => $student]),
                    'text/html'
                )
                ->attach(\Swift_Attachment::fromPath($writePdf->generatePdfNewVersion($student, $promo)));

            $mailer->send($message);
            sleep(2);
        }


        $this->addFlash('success', 'Le message a bien été envoyé');

        return $this->redirectToRoute('student_index', ['promo' => $promo->getId()]);
    }

    /**
     *
     * @Route("/send-mail-student/{student}", name="send_mail_student_ecf")
     */
    public function sendEcfMailStudent(Student $student, WritePdf $writePdf, \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('ECF - ' . $student->getFirstname() . ' ' . $student->getName()))
            ->setFrom($this->getParameter('mailer_user'))
            ->setTo($student->getEmail())
            ->setBody(
                $this->renderView('email/sendEcf.html.twig', ['student' => $student]),
                'text/html'
            )
            ->attach(\Swift_Attachment::fromPath($writePdf->generatePdfNewVersion($student, $student->getPromo())));

        $mailer->send($message);

        $this->addFlash('success', 'Le message a bien été envoyé');

        return $this->redirectToRoute('student_index', ['promo' => $student->getPromo()->getId()]);
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

        foreach ($students as $student) {
            $writePdf->generatePdfNewVersion($student, $promo);
        }

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
    public function downloadTemplateAction(Promo $promo)
    {
        if ($promo->getEcfVersion() == Promo::ECF_PHP) {
            return new BinaryFileResponse($this->getParameter('template_directory') . 'templateecf_php_fev2019.pdf');
        } elseif ($promo->getEcfVersion() == Promo::ECF_JS) {
            return new BinaryFileResponse($this->getParameter('template_directory') . 'templateecf_js_fev2019.pdf');
        } elseif ($promo->getEcfVersion() == Promo::ECF_JAVA) {
            return new BinaryFileResponse($this->getParameter('template_directory') . 'templateecf_java_fev2019.pdf');
        } else {
            return new BinaryFileResponse($this->getParameter('template_directory') . 'templateecf_js_java_fev2019.pdf');
        }
    }
}
