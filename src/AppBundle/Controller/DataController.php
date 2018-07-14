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
        $res = $client->request('GET', 'https://ppody.innoveduc.fr/api/v2/crews', [
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
        $res = $client->request('GET', 'https://ppody.innoveduc.fr/api/v2/crews/' . $idCrew, [
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

//        TODO: Update gender and language
        $language = ['PHP', 'JS', 'JAVA'];
        $gender = [Student::MALE, Student::FEMALE];

        foreach ($crews as $crew){
            $promo = $em->getRepository(Promo::class)->findOneBy(array('name' => $crew->name));
            if ($promo == null){
                $promo = new Promo();
                $promo->setName($crew->name);
                if (count($crew->trainers) > 0){
                    $promo->setTrainer($crew->trainers[0]->fullname);
                }

//        TODO: Update language
                $promo->setLangage($language[array_rand($language)]);

                $city = $em->getRepository(City::class)->findOneBy(array('name' => $crew->location->city));
                if ($city == null){
                    $city = new City();
                    $city->setName($crew->location->city);
                    $em->persist($city);
                    $em->flush();
                }
                $promo->setCity($city);
                $em->persist($promo);

                $students = $this->getApiStudentData($crew->id)->students;
                foreach ($students as $student){
                    $studentExist = $em->getRepository(Student::class)->findOneBy(array(
                        'firstname' => $student->firstname,
                        'name' => $student->lastname,
                        'dateOfBirth' => new \DateTime($student->birthdate)
                    ));
                    dump($studentExist);
                    if ($studentExist == null){
                        $newStudent = new Student();
                        $newStudent->setName($student->lastname);
                        $newStudent->setFirstname($student->firstname);

//        TODO: Update gender
                        $newStudent->setGender($gender[array_rand($gender)]);
                        $newStudent->setPromo($promo);
                        $newStudent->setDateOfBirth(new \DateTime($student->birthdate));
                        $em->persist($newStudent);
                        $em->flush();
                    }

                }
                $em->flush();
            }
        }
        $this->addFlash('notice', 'Update BDD ok');

        return $this->redirectToRoute('homepage');
    }
}