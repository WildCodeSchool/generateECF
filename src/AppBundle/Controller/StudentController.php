<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Promo;
use AppBundle\Entity\Student;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Student controller.
 *
 * @Route("/student")
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
     * @Route("/{id}/edit", name="student_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Student $student)
    {
        $editForm = $this->createForm('AppBundle\Form\StudentType', $student);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('student_index', array('promo' => $promo->getId(),'id' => $student->getPromo()->getId()));
        }

        return $this->render('student/edit.html.twig', array(
            'student' => $student,
            'edit_form' => $editForm->createView(),
        ));
    }
}
