<?php

namespace App\Tests\Unit\Service;

use App\Service\TeamService;
use App\Tests\Unit\Service\TestCase;
use Doctrine\Common\Collections\ArrayCollection;


class TeamServiceTest extends TestCase
{

    public $teams;

    public $mockedTeamService;

    public $mockedTeamEntity;

    /**
     * Sets up the fixture.
     *
     * @throws \RuntimeException If the class under test name is wrong.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->mockedTeamService = $this->getInstanceOfClassUnderTest();

        $this->mockedTeamEntity = $this->getMockBuilder('App\Entity\Team')
            ->disableOriginalConstructor()
            ->getMock();

        $this->teams = array(
            0 =>
                array(
                    'id' => 1,
                    'name' => 'AFC AJAX',
                    'logo' => 'https://logo.clearbit.com/ajax.nl',
                    'players' => array(
                        0 =>
                            array(
                                'id' => 1,
                                'firstName' => 'Hakim',
                                'lastName' => 'Ziyech',
                                'imageURI' => 'https://www.vtbl.nl/sites/default/files/styles/liggend/public/content/images/2019/06/16/ANP-58099208.jpg?itok=0YmHF_rX',
                            ),
                        1 =>
                            array(
                                'id' => 2,
                                'firstName' => 'David',
                                'lastName' => 'Neres',
                                'imageURI' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/06/David_Neres_2017.jpg/250px-David_Neres_2017.jpg',
                            ),
                    )
                ),
            1 =>
                array(
                    'id' => 2,
                    'name' => 'Go Ahead Eagles',
                    'logo' => 'https://upload.wikimedia.org/wikipedia/en/3/3c/Go_Ahead_Eagles.png',
                ),
        );
    }

    /**
     * The class under test name.
     *
     * @var string
     */
    protected $classUnderTestName = 'App\Service\TeamService';

    /**
     * Test Get All Teams for success case
     */
    public function testGetAllTeamsWithSuccess()
    {
        $this->classUnderTestConstructorArgs['teamRepository']->shouldReceive('getTeams')->andReturn($this->teams);

        $actual = $this->mockedTeamService->getAllTeams();
        $expected = $this->teams;
        $this->assertEquals($actual, $expected);
    }

    /**
     * Test Get team detail for success case
     */
    public function testGetTeamDetailsWithSuccess()
    {
        $argument = $this->teams[0]['id'];
        $this->classUnderTestConstructorArgs['teamRepository']->shouldReceive('getTeamDetail')->with($argument)->andReturn($this->teams[0]);
        $actual = $this->mockedTeamService->getTeamDetail($this->teams[0]['id']);
        $expected = $this->teams[0];
        $this->assertEquals($actual, $expected);
    }

    /**
     * Test Get team detail for Exception case
     * @expectedException Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testGetTeamDetailsWithException()
    {
        $argument = $this->teams[0]['id'];
        $this->classUnderTestConstructorArgs['teamRepository']->shouldReceive('getTeamDetail')->with($argument)->andReturn(array());
        $this->mockedTeamService->getTeamDetail($this->teams[0]['id']);
    }

    /**
     * Test get team players for success case
     */
    public function testGetTeamPlayersWithSuccess()
    {
        $argument = $this->teams[0]['id'];
        $this->classUnderTestConstructorArgs['teamRepository']->shouldReceive('getTeamPlayers')->with($argument, TeamService::DEFAULT_OFFSET, TeamService::DEFAULT_LIMIT)->andReturn($this->teams[0]['players']);
        $actual = $this->mockedTeamService->getTeamPlayers($this->teams[0]['id']);
        $expected = $this->teams[0]['players'];
        $this->assertEquals($actual, $expected);
    }

    /**
     * Test delete team for success case
     */
    public function testDeleteTeamWithSuccess()
    {
        $argument = $this->teams[0]['id'];

        $this->classUnderTestConstructorArgs['teamRepository']->shouldReceive('getTeam')->with($argument)->andReturn($this->mockedTeamEntity);

        $this->mockedTeamEntity->method('getPlayers')->willReturn(new ArrayCollection());

        $this->classUnderTestConstructorArgs['teamRepository']->shouldReceive('delete')->with($this->mockedTeamEntity);

        $this->mockedTeamService->deleteTeam($this->teams[0]['id']);
    }

    /**
     * Test delete team for exception case
     * @expectedException Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testDeleteTeamWithException()
    {
        $argument = $this->teams[0]['id'];

        $this->classUnderTestConstructorArgs['teamRepository']->shouldReceive('getTeam')->with($argument)->andReturn($this->mockedTeamEntity);

        $this->mockedTeamEntity->method('getPlayers')->willReturn(new ArrayCollection($this->teams[0]['players']));

        $this->classUnderTestConstructorArgs['teamRepository']->shouldReceive('delete')->with($this->mockedTeamEntity);

        $this->mockedTeamService->deleteTeam($this->teams[0]['id']);
    }

    /**
     * Test save team for success case
     */
    public function testSaveTeamWithSuccess()
    {
        $argument = $this->teams[0]['id'];
        $data = $this->teams[0];

        $this->classUnderTestConstructorArgs['teamRepository']->shouldReceive('getTeam')->with($argument, true)->andReturn($this->mockedTeamEntity);

        $this->classUnderTestConstructorArgs['validatorService']->shouldReceive('Validator')->with($this->mockedTeamEntity)->andReturn(true);

        $this->classUnderTestConstructorArgs['teamRepository']->shouldReceive('save')->with($this->mockedTeamEntity)->andReturn($this->mockedTeamEntity);

        $this->mockedTeamService->saveTeam($data, $argument);
    }

    /**
     * Test save team for validation fail
     */
    public function testSaveTeamWithValidationFail()
    {
        $argument = $this->teams[0]['id'];
        $data = $this->teams[0];

        $this->classUnderTestConstructorArgs['teamRepository']->shouldReceive('getTeam')->with($argument, true)->andReturn($this->mockedTeamEntity);

        $this->mockedTeamEntity->method('setAttributes')->willReturn($data);

        $this->classUnderTestConstructorArgs['validatorService']->shouldReceive('Validator')->with($this->mockedTeamEntity)->andReturn(false);

        $this->classUnderTestConstructorArgs['teamRepository']->shouldNotHaveReceived('save');

        $this->mockedTeamService->saveTeam($data, $argument);
    }

}
