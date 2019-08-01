<?php

namespace App\DataFixtures;

use App\Entity\Player;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PlayerFixture extends Fixture implements OrderedFixtureInterface
{
    /**
     * Load Player table with dummy players
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $player = new Player();
        $player->setFirstName('Hakim');
        $player->setLastName('Ziyech');
        $player->setTeam($this->getReference('AJAX-TEAM')); // load the stored reference
        $player->setImageURI('https://www.vtbl.nl/sites/default/files/styles/liggend/public/content/images/2019/06/16/ANP-58099208.jpg?itok=0YmHF_rX');
        $manager->persist($player);
        $manager->flush();

        $player = new Player();
        $player->setFirstName('David');
        $player->setLastName('Neres');
        $player->setTeam($this->getReference('AJAX-TEAM')); // load the stored reference
        $player->setImageURI('https://upload.wikimedia.org/wikipedia/commons/thumb/0/06/David_Neres_2017.jpg/250px-David_Neres_2017.jpg');
        $manager->persist($player);
        $manager->flush();

        $player = new Player();
        $player->setFirstName('Thomas');
        $player->setLastName('Verheydt');
        $player->setTeam($this->getReference('EAGLES-TEAM')); // load the stored reference
        $player->setImageURI('https://pbs.twimg.com/profile_images/1038404156493635584/vd5M4ah5.jpg');
        $manager->persist($player);
        $manager->flush();

        $player = new Player();
        $player->setFirstName('Maarten');
        $player->setLastName('Pouwels');
        $player->setTeam($this->getReference('EAGLES-TEAM')); // load the stored reference
        $player->setImageURI('https://www.dalfsennet.nl/static/img/normal-b/2018/08/maarten2_1534538079.png');
        $manager->persist($player);
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}
