<?php
/**
 * Created by PhpStorm.
 * User: florian
 * Date: 11/07/18
 * Time: 15:27
 */

namespace AppBundle\Controller;


use AppBundle\Entity\City;
use AppBundle\Entity\Promo;
use AppBundle\Entity\Student;
use GuzzleHttp\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DataController
 * @package AppBundle\Controller
 */
class DataController extends Controller
{
    /**
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @Route("/data", name="data")
     */
    public function createCityAndPromoAction()
    {
        $crews = $this->getApiCrewData();

        $em = $this->getDoctrine()->getManager();

        $gender = ['Man' => Student::MALE, 'Woman' => Student::FEMALE];


        $cohortsFromDB = $em->getRepository(Promo::class)->findAll();
        $cohorts = [];
        /**
         * @var $cohortFromDB Promo
         */
        foreach ($cohortsFromDB as &$cohortFromDB) {
            $cohorts[$cohortFromDB->getName()] = $cohortFromDB;
        }

        $students = [];
        $studentsFromDB = $em->getRepository(Student::class)->findAll();
        /**
         * @var $studentFromDB Student
         */
        foreach ($studentsFromDB as &$studentFromDB) {
            $students[$studentFromDB->getEmail()] = $studentFromDB;
        }


        $cities = [];
        $citiesFromDB = $em->getRepository(City::class)->findAll();
        /**
         * @var $citiyFromDB City
         */
        foreach ($citiesFromDB as &$cityFromDB) {
            $cities[$cityFromDB->getName()] = $cityFromDB;
        }

        foreach ($crews as $crew) {
            if (new \DateTime('2018-02-20') < new \DateTime($crew->start_date) && $crew->start_date != null) {

                if (key_exists($crew->name, $cohorts)) {
                    $promo = $cohorts[$crew->name];
                } else {
                    $promo = new Promo();
                }

                if ($promo->getName() != $crew->name) {
                    $promo->setName($crew->name);
                }

                if ($promo->getStart() != ($crewStartDate = new \DateTime($crew->start_date))) {
                    $promo->setStart($crewStartDate);
                }

                if ($promo->getEnd() != ($crewEndDate = new \DateTime($crew->end_date))) {
                    $promo->setEnd($crewEndDate);
                }

                if ($promo->getTrainer() != ($crewEndDate = new \DateTime($crew->end_date))) {
                    if (count($crew->trainers) > 0) {
                        $promo->setTrainer($crew->trainers[0]->fullname);
                    }
                }

                if (isset($crew->course)) {
                    if ($promo->getLangage() != ($crew->course->name)) {
                        $promo->setLangage($crew->course->name);
                    }
                }

                if (isset($crew->location->city)) {

                    if (!key_exists($crew->location->city, $cities)) {
                        $city = new City();
                        $city->setName($crew->location->city);
                        $em->persist($city);
                    } else {
                        $promo->setCity($cities[$crew->location->city]);
                    }
                }

                $em->persist($promo);
                $em->flush();

                $studentsFromAPI = $this->getApiStudentData($crew->id)->students;

                foreach ($studentsFromAPI as $studentFromAPI) {

                    if (!key_exists($studentFromAPI->email, $students)) {
                        $student = new Student();
                    } else {
                        $student = $students[$studentFromAPI->email];
                    }

                    if ($studentFromAPI->lastname != $student->getName()) {
                        $student->setName($studentFromAPI->lastname);
                    }

                    if ($studentFromAPI->firstname != $student->getFirstname()) {
                        $student->setFirstname($studentFromAPI->firstname);
                    }

                    if ($studentFromAPI->email != $student->getEmail()) {
                        $student->setEmail($studentFromAPI->email);
                    }

                    if ($studentFromAPI->gender != $student->getGender()) {
                        if ($studentFromAPI->gender != null) {
                            $student->setGender($studentFromAPI->gender);
                        } elseif ($student->getGender() != Student::GENDER_UNDEFINED) {
                            $student->setGender(Student::GENDER_UNDEFINED);
                        }
                    }

                    if ($promo->getName() != $student->getPromo()->getName()) {
                        if ($student->getPromo()->getId() < $promo->getId()) {
                            $student->setPromo($promo);
                        }
                    }


                    if (!empty($studentFromAPI->birthdate)) {
                        if (($birthDate = new \DateTime($studentFromAPI->birthdate)) != $student->getDateOfBirth()) {
                            $student->setDateOfBirth($birthDate);
                        }
                    }

                    $em->persist($student);

                }
                $em->flush();
            }

        }
        $this->addFlash('notice', 'Update BDD ok');
        return $this->render('base.html.twig', []);
        return $this->redirectToRoute('homepage');
    }

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private
    function getApiCrewData()
    {
        $client = new Client();
        $res = $client->request('GET', 'https://odyssey.wildcodeschool.com/api/v2/crews', [
            'headers' => [
                'Authorization' => $this->getParameter('key_api_odyssey'),
            ],
        ]);
        $crews = json_decode($res->getBody()->getContents());

        return $crews;
    }

    /**
     * @param $idCrew
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private
    function getApiStudentData($idCrew)
    {
        $client = new Client();
        $res = $client->request('GET', 'https://odyssey.wildcodeschool.com/api/v2/crews/' . $idCrew, [
            'headers' => [
                'Authorization' => $this->getParameter('key_api_odyssey'),
            ],
        ]);
        $crews = json_decode($res->getBody()->getContents());
        return $crews;
    }
}