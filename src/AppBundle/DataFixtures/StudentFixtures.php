<?php
/**
 * Created by PhpStorm.
 * User: florian
 * Date: 29/06/18
 * Time: 00:46
 */

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Promo;
use AppBundle\Entity\Student;
use AppBundle\Form\StudentType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class StudentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $genders = [Student::MALE, Student::FEMALE];
        $promoReferences = range(1, 25);

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 300; $i++){
            $student = new Student();
            $student->setName($faker->name);
            $student->setFirstname($faker->firstName);
            $student->setGender($genders[array_rand($genders)]);
            $student->setDateOfBirth($faker->dateTime);
            $student->setValidateActivityOne($faker->boolean);
            $student->setValidateActivityTwo($faker->boolean);
            $student->setValidateEvalSuppOne($faker->boolean);
            $student->setValidateEvalSuppTwo($faker->boolean);
            $student->setCommActivityOne($faker->sentence(20, true));
            $student->setCommActivityTwo($faker->sentence(20, true));
            $student->setCommEvalSuppOne($faker->sentence(20, true));
            $student->setCommEvalSuppTwo($faker->sentence(20, true));
            $student->setObservationStudent($faker->sentence(20, true));

            $student->setPromo($this->getReference('promo_' . $promoReferences[array_rand($promoReferences)]));

            $manager->persist($student);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            PromoFixtures::class
        );
    }
}