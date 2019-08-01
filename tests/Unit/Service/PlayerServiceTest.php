<?php

namespace App\Tests\Unit\Service;

use App\Service\Playerervice;
use App\Tests\Unit\Service\TestCase;


class PlayerServiceTest extends TestCase
{

    public $players;

    public $mockedPlayerService;

    public $mockedPlayerEntity;

    /**
     * Sets up the fixture.
     *
     * @throws \RuntimeException If the class under test name is wrong.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->mockedPlayerService = $this->getInstanceOfClassUnderTest();

        $this->players = array(
            0 =>
                array(
                    'id' => 1,
                    'firstName' => 'Hakim',
                    'lastName' => 'Ziyech',
                    'imageURI' => 'https://www.vtbl.nl/sites/default/files/styles/liggend/public/content/images/2019/06/16/ANP-58099208.jpg?itok=0YmHF_rX',
                    'teamId' => 1
                ),
            1 =>
                array(
                    'id' => 2,
                    'firstName' => 'David',
                    'lastName' => 'Neres',
                    'imageURI' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/06/David_Neres_2017.jpg/250px-David_Neres_2017.jpg',
                    'teamId' => 2
                ),
        );

        $this->mockedPlayerEntity = $this->getMockBuilder('App\Entity\Player')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * The class under test name.
     *
     * @var string
     */
    protected $classUnderTestName = 'App\Service\PlayerService';

    /**
     * Test Get player for success case
     */
    public function testGetPlayerWithSuccess()
    {
        $argument = $this->players[0]['id'];

        $this->classUnderTestConstructorArgs['playerRepository']->shouldReceive('getPlayerDetail')->with($argument)->andReturn($this->players[0]);

        $actual = $this->mockedPlayerService->getPlayerDetail($this->players[0]['id']);
        $expected = $this->players[0];
        $this->assertEquals($actual, $expected);
    }

    /**
     * Test Get player detail for success case
     */
    public function testGetPlayerDetailsWithSuccess()
    {
        $argument = $this->players[0]['id'];
        $this->classUnderTestConstructorArgs['playerRepository']->shouldReceive('getPlayerDetail')->with($argument)->andReturn($this->players[0]);
        $actual = $this->mockedPlayerService->getPlayerDetail($this->players[0]['id']);
        $expected = $this->players[0];
        $this->assertEquals($actual, $expected);
    }


    /**
     * Test delete player for success case
     */
    public function testDeletePlayerWithSuccess(){
        $argument = $this->players[0]['id'];

        $this->classUnderTestConstructorArgs['playerRepository']->shouldReceive('getPlayer')->with($argument)->andReturn($this->mockedPlayerEntity);

        $this->classUnderTestConstructorArgs['playerRepository']->shouldReceive('delete')->with($this->mockedPlayerEntity);
    }

    /**
     * Test save player for success case
     */
    public function testSavePlayerWithSuccess(){
        $argument = $this->players[0]['id'];
        $data = $this->players[0];

        $teamEntity = $this->getMockBuilder('App\Entity\Team')
            ->disableOriginalConstructor()
            ->getMock();

        $this->classUnderTestConstructorArgs['playerRepository']->shouldReceive('getPlayer')->with($data, $argument)->andReturn($this->mockedPlayerEntity);
        $this->classUnderTestConstructorArgs['teamRepository']->shouldReceive('getTeam')->with($data['teamId'], $argument)->andReturn($teamEntity);

        $this->classUnderTestConstructorArgs['validatorService']->shouldReceive('Validator')->with($this->mockedPlayerEntity);

        $this->classUnderTestConstructorArgs['teamRepository']->shouldReceive('save')->with($this->mockedPlayerEntity);
    }

}
