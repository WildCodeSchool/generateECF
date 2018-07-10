<?php
/**
 * Created by PhpStorm.
 * User: florian
 * Date: 28/06/18
 * Time: 23:39
 */

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Promo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PromoFixtures extends Fixture implements DependentFixtureInterface
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

        $langages = array(
            'PHP',
            'JS',
            'JAVA',
            'ANDROID'
        );

        for ($i = 1; $i <= 25; $i++){
            $promo = new Promo();
            $city = $cities[array_rand($cities)];

            $promo->setName(strtolower($city) . '_' . uniqid(3));
            $promo->setLangage($langages[array_rand($langages)]);
            $promo->setCity($this->getReference($city));

            $this->addReference('promo_' . $i, $promo);
            $manager->persist($promo);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CityFixtures::class
        );
    }
}