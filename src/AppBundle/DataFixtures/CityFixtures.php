<?php
/**
 * Created by PhpStorm.
 * User: florian
 * Date: 28/06/18
 * Time: 23:33
 */

namespace AppBundle\DataFixtures;


use AppBundle\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CityFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $cities = array(
            'Bordeaux',
            'Paris',
            'Lille',
            'Strasbourg',
            'Orleans',
            'Biarritz',
            'Lyon',
            'La Loupe',
            'Toulouse',
            'Reims'
        );
        foreach ($cities as $city){
            $newCity = new City();
            $newCity->setName($city);
            $manager->persist($newCity);
            $this->addReference($city, $newCity);
        }

        $manager->flush();
    }
}