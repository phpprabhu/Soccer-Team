<?php

namespace App\DataFixtures;

use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TeamFixture extends Fixture implements OrderedFixtureInterface
{
    /**
     * load Team table with 2 teams
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $team = new Team();
        $team->setName('AFC AJAX');
        $team->setLogo('https://logo.clearbit.com/ajax.nl');
        $manager->persist($team);
        $manager->flush();

        // store reference to Ajax team for player relation
        $this->addReference('AJAX-TEAM', $team);

        $team = new Team();
        $team->setName('Go Ahead Eagles');
        $team->setLogo('https://upload.wikimedia.org/wikipedia/en/3/3c/Go_Ahead_Eagles.png');
        $manager->persist($team);
        $manager->flush();

        // store reference to Eagles team for player relation
        $this->addReference('EAGLES-TEAM', $team);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}
