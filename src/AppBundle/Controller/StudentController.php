<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Promo;
use AppBundle\Entity\Student;
use AppBundle\Services\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Student controller.
 *
 * @Route("/students")
 */
class StudentController extends Controller
{
    /**
     * Lists all student entities.
     *
     * @Route("/promo/{promo}", name="student_index")
     * @Method("GET")
     */
    public function indexAction(Promo $promo){

        $em = $this->getDoctrine()->getManager();

        $students = $em->getRepository(Student::class)->findBy(['promo' => $promo], ['firstname'=>'ASC', 'name'=>'ASC']);

        return $this->render('student/index.html.twig', array(
            'students' => $students,
            'promo' => $promo
        ));
    }

    /**
     * Displays a form to edit an existing student entity.
     *
     * @param Request $request
     * @param Promo $promo
     * @param Student $student
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/promo/{promo}/{student}/edit", name="student_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Promo $promo, Student $student, FileUploader $fileUploader)
    {
        $signT = $student->getSign();

        $editForm = $this->createForm('AppBundle\Form\StudentType', $student);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            if ($student->getSign() != null){
                $file = $student->getSign();
                $fileName = $fileUploader->upload($file);
                $student->setSign($fileName);

                if ($signT != null){
                    unlink($this->getParameter('sign_directory') . $signT);
                }
            } else {
                $student->setSign($signT);
            }

            $student->setValidateEvalSuppOne($student->getValidateActivityOne());
            $student->setValidateEvalSuppTwo($student->getValidateActivityTwo());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('student_index', array('promo' => $promo->getId()));
        }

        return $this->render('student/edit.html.twig', array(
            'student' => $student,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param Student $student
     * @return JsonResponse
     * @throws \Exception
     * @Route("/editBirth/{student}", name="student_edit_birth")
     */
    public function changeDateBirth(Request $request, Student $student){
        $em = $this->getDoctrine()->getManager();
        $date = $request->get('date');
        $dateTime = new \DateTime($date, new \DateTimeZone('Europe/Paris'));

        $student->setDateOfBirth($dateTime);
        $em->persist($student);
        $em->flush();

        $studentDateBirth = $student->getDateOfBirth()->format('d/m/Y');

        return new JsonResponse($studentDateBirth, 200);
    }
}
