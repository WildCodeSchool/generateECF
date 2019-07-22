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
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getApiCrewData(){
        $client = new Client();
        $res = $client->request('GET', 'https://odyssey.wildcodeschool.com/api/v2/crews', [
            'headers' => [
                'Authorization' => $this->getParameter('key_api_odyssey')
            ]
        ]);
        $crews = json_decode($res->getBody()->getContents());

        return $crews;
    }

    /**
     * @param $idCrew
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getApiStudentData($idCrew){
        $client = new Client();
        $res = $client->request('GET', 'https://odyssey.wildcodeschool.com/api/v2/crews/' . $idCrew, [
            'headers' => [
                'Authorization' => $this->getParameter('key_api_odyssey')
            ]
        ]);
        $crews = json_decode($res->getBody()->getContents());
        return $crews;
    }
    /**
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @Route("/data", name="data")
     */
    public function createCityAndPromoAction(){
        $crews = $this->getApiCrewData();
        $em = $this->getDoctrine()->getManager();

        $gender = ['Man' => Student::MALE, 'Woman' => Student::FEMALE];

        foreach ($crews as $crew){
            if (new \DateTime('2018-02-20') < new \DateTime($crew->start_date) && $crew->start_date != null){
                $promo = $em->getRepository(Promo::class)->findOneBy(array('name' => $crew->name));

                if ($promo == null) {
                    $promo = new Promo();
                }
                $promo->setName($crew->name);

                $promo->setStart(new \DateTime($crew->start_date));
                $promo->setEnd(new \DateTime($crew->end_date));

                if (count($crew->trainers) > 0){
                    $promo->setTrainer($crew->trainers[0]->fullname);
                }

                if (isset($crew->course)){
                    $promo->setLangage($crew->course->name);
                } else {
                    $promo->setLangage('undifined');
                }

                if (isset($crew->location->city)){
                    $city = $em->getRepository(City::class)->findOneBy(array('name' => $crew->location->city));
                    if ($city == null){
                        $city = new City();
                        $city->setName($crew->location->city);
                        $em->persist($city);
                    }
                    $promo->setCity($city);
                }

                $em->persist($promo);

                $students = $this->getApiStudentData($crew->id)->students;
                foreach ($students as $student){
                    $studentExist = $em->getRepository(Student::class)->findOneBy(array(
                        'firstname' => $student->firstname,
                        'name' => $student->lastname,
                        'promo' => $student->promo
                    ));
                    if ($studentExist == null) {
                        $studentExist = new Student();
                    }
                    $studentExist->setName($student->lastname);
                    $studentExist->setFirstname($student->firstname);

                    if ($student->gender != null) {
                        $studentExist->setGender($gender[$student->gender]);
                    } else {
                        $studentExist->setGender(Student::GENDER_UNDEFINED);
                    }
                    $studentExist->setPromo($promo);
                    $studentExist->setDateOfBirth(new \DateTime($student->birthdate));
                    $em->persist($studentExist);
                }
                $em->flush();
            }

        }
        $this->addFlash('notice', 'Update BDD ok');
        return $this->redirectToRoute('homepage');
    }
}