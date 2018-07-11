<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Student;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    /**
     * @Route("/search", name="search")
     */
    public function searchAction(Request $request)
    {
        dump($request);
        if ($request->isMethod('GET')) {
            $search = $request->query->get('name');
            $students = $this->getDoctrine()->getRepository(Student::class)->searchStudent($search);

        }

        return $this->render('search/search.html.twig', [
                'students' => $students ?? [],
            ]
        );

    }

}
