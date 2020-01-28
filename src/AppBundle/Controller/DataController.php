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

        foreach ($crews as $crew) {
            if (new \DateTime('2018-02-20') < new \DateTime($crew->start_date) && $crew->start_date != null) {

                if (key_exists($crew->name, $cohorts)) {
                    $promo = $cohorts[$crew->name];
                }else{
                    $promo = new Promo();
                }

                $promo->setName($crew->name);

                $promo->setStart(new \DateTime($crew->start_date));
                $promo->setEnd(new \DateTime($crew->end_date));

                if (count($crew->trainers) > 0) {
                    $promo->setTrainer($crew->trainers[0]->fullname);
                }

                if (isset($crew->course)) {
                    $promo->setLangage($crew->course->name);
                } else {
                    $promo->setLangage('undifined');
                }

                if (isset($crew->location->city)) {
                    $city = $em->getRepository(City::class)->findOneBy(array('name' => $crew->location->city));
                    if ($city == null) {
                        $city = new City();
                        $city->setName($crew->location->city);
                        $em->persist($city);
                    }
                    $promo->setCity($city);
                }

                $em->persist($promo);
                $em->flush();

                $studentsFromAPI = $this->getApiStudentData($crew->id)->students;

                foreach ($studentsFromAPI as $studentFromAPI) {

                    if (!key_exists($studentFromAPI->email, $students)) {
                        $student = new Student();
                    }else{
                        $student = $students[$studentFromAPI->email];
                    }

                    $student->setName($studentFromAPI->lastname);
                    $student->setFirstname($studentFromAPI->firstname);
                    $student->setEmail($studentFromAPI->email);

                    if ($studentFromAPI->gender != null) {
                        $student->setGender($gender[$studentFromAPI->gender]);
                    } else {
                        $student->setGender(Student::GENDER_UNDEFINED);
                    }
                    $student->setPromo($promo);
                    $student->setDateOfBirth(new \DateTime($studentFromAPI->birthdate));
                    $em->persist($student);
                }
                $em->flush();
            }

        }
        $this->addFlash('notice', 'Update BDD ok');

        return $this->redirectToRoute('homepage');
    }

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getApiCrewData()
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
    private function getApiStudentData($idCrew)
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