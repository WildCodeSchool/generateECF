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
        $res = $client->request('GET', 'https://odyssey.wildcodeschool.fr/api/v2/crews', [
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
        $res = $client->request('GET', 'https://odyssey.wildcodeschool.fr/api/v2/crews/' . $idCrew, [
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
            if (new \DateTime('2018-02-20') < new \DateTime($crew->start_date) && new \DateTime($crew->end_date) < new \DateTime('2018-08-01') && $crew->start_date != null){
                $promo = $em->getRepository(Promo::class)->findOneBy(array('name' => $crew->name));
                if ($promo == null){
                    $promo = new Promo();
                    $promo->setName($crew->name);
                    if (count($crew->trainers) > 0){
                        $promo->setTrainer($crew->trainers[0]->fullname);
                    }

                    if (isset($crew->program_type)){
                        $promo->setLangage($crew->program_type->name);
                    } else {
                        $promo->setLangage('undifined');
                    }

                    if (isset($crew->location->city)){
                        $city = $em->getRepository(City::class)->findOneBy(array('name' => $crew->location->city));
                        if ($city == null){
                            $city = new City();
                            $city->setName($crew->location->city);
                            $em->persist($city);
                            $em->flush();
                        }
                        $promo->setCity($city);
                    }

                    $em->persist($promo);

                    $students = $this->getApiStudentData($crew->id)->students;
                    foreach ($students as $student){
                        $studentExist = $em->getRepository(Student::class)->findOneBy(array(
                            'firstname' => $student->firstname,
                            'name' => $student->lastname,
                            'dateOfBirth' => new \DateTime($student->birthdate)
                        ));
                        if ($studentExist == null){
                            $newStudent = new Student();
                            $newStudent->setName($student->lastname);
                            $newStudent->setFirstname($student->firstname);

                            if ($student->gender != null) {
                                $newStudent->setGender($gender[$student->gender]);
                            } else {
                                $newStudent->setGender(Student::GENDER_UNDEFINED);
                            }
                            $newStudent->setPromo($promo);
                            $newStudent->setDateOfBirth(new \DateTime($student->birthdate));
                            $em->persist($newStudent);
                            $em->flush();
                        }

                    }
                    $em->flush();
                }
            }

        }
        $this->addFlash('notice', 'Update BDD ok');
//        return new Response('ok');
        return $this->redirectToRoute('homepage');
    }
}