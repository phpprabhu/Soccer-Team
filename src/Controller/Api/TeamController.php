<?php

namespace App\Controller\Api;

use App\Entity\Team;
use App\Entity\Player;
use App\Service\TeamService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use FOS\RestBundle\Controller\Annotations\QueryParam;


/**
 * @Route("/api",name="api_")
 */
class TeamController extends AbstractFOSRestController
{
    /**
     * @var TeamService
     */
    private $teamService;

    /**
     * TeamController constructor.
     * @param TeamService $teamService
     */
    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    /**
     * Get list of Teams
     *
     * @Rest\Get("/v1/teams")
     * @SWG\Response(
     *     response=200,
     *     description="Get all teams",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Team::class))
     *     )
     * )
     * @SWG\Tag(name="Team")
     */
    public function getAllTeams(): Response
    {
        $teams = $this->teamService->getAllTeams();
        return $this->handleView($this->view($teams));
    }

    /**
     * Get Team detail
     *
     * @Rest\Get("/v1/team/{id}")
     * @SWG\Response(
     *     response=200,
     *     description="Get team detail",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Items(ref=@Model(type=Team::class))
     *     )
     * ),
     * @SWG\Response(response=404, description="Team not found"),
     * @SWG\Tag(name="Team")
     */
    public function getTeam(Request $request): Response
    {
        $teamId = $request->get('id');
        $team = $this->teamService->getTeamDetail($teamId);
        return $this->handleView($this->view($team));
    }

    /**
     * Get Team players
     *
     * @Rest\Get("/v1/team/{id}/players")
     * @QueryParam(name="offset", requirements="\d+", default="0", description="Offset")
     * @QueryParam(name="limit", requirements="\d+", default="5", description="Limit")
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="Team Id",
     *     required=true,
     *     type="integer"
     * ),
     * @SWG\Response(
     *     response=200,
     *     description="Get team detail",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Player::class))
     *     )
     * )
     * @SWG\Tag(name="Team")
     */
    public function getTeamPlayers(Request $request): Response
    {
        $teamId = $request->get('id');
        $offset = $request->get('offset', TeamService::DEFAULT_OFFSET);
        $limit = $request->get('limit', TeamService::DEFAULT_LIMIT);

        //Resetting to zero values if negative
        if ($limit < 0) {
            $limit = 0;
        }

        //Resetting to zero values if negative
        if ($offset < 0) {
            $offset = 0;
        }

        $players = $this->teamService->getTeamPlayers($teamId, $offset, $limit);
        return $this->handleView($this->view($players));
    }

    /**
     * Creates Team resource
     * @Rest\Post("/v1/team/add")
     * @param Request $request
     * @return View
     *
     *
     * @SWG\Post(path="/api/v1/team/add",
     *   tags={"Team"},
     *   summary="Add Team",
     *   description="API to add new Team",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Team Name & Its Logo URI",
     *     required=true,
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="name", type="string"),
     *         @SWG\Property(property="logo", type="string"),
     *     )
     * ),
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *         @SWG\Schema(type="string"),
     *     ),
     *     @SWG\Response(response=401, description="Unauthorized"),
     *   security={
     *         {
     *             "Bearer": {"write:teams"}
     *         }
     *     },
     * )
     */
    public function addTeam(Request $request): View
    {
        $data = $request->request->all();
        $team = $this->teamService->saveTeam($data);
        return View::create("Team (" . $team->getId() . ") added successfully", Response::HTTP_CREATED);
    }

    /**
     * Update Team resource
     * @Rest\Put("/v1/team/{id}/update")
     * @param Request $request
     * @return View
     *
     *
     * @SWG\Put(path="/api/v1/team/{id}/update",
     *   tags={"Team"},
     *   summary="Update Team",
     *   description="API to update Team",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="Team id",
     *     required=true,
     *     type="integer"
     * ),
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Team id, Team Name & Its Logo URI",
     *     required=true,
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="id", type="integer"),
     *         @SWG\Property(property="name", type="string"),
     *         @SWG\Property(property="logo", type="string"),
     *     )
     * ),
     *     @SWG\Response(
     *         response=204,
     *         description="Team updated successfully",
     *         @SWG\Schema(type="string"),
     *     ),
     *     @SWG\Response(response=404, description="Team not found"),
     *     @SWG\Response(response=401, description="Unauthorized"),
     *   security={
     *         {
     *             "Bearer": {"write:teams"}
     *         }
     *     },
     * )
     */
    public function updateTeam(Request $request): View
    {
        $teamId = $request->get('id');
        $data = $request->request->all();

        $team = $this->teamService->saveTeam($data, $teamId);
        return View::create("Team (" . $team->getId() . ") updated successfully", Response::HTTP_NO_CONTENT);
    }

    /**
     * Delete Team resource
     * @Rest\Delete("/v1/team/{id}/delete")
     * @param Request $request
     * @return View
     *
     *
     * @SWG\Delete(path="/api/v1/team/{id}/delete",
     *   tags={"Team"},
     *   summary="Delete Team",
     *   description="API to delete Team",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="Team id",
     *     required=true,
     *     type="integer"
     * ),
     *     @SWG\Response(
     *         response=202,
     *         description="Success",
     *         @SWG\Schema(type="string"),
     *     ),
     *     @SWG\Response(response=404, description="Team not found"),
     *     @SWG\Response(response=401, description="Unauthorized"),
     *   security={
     *         {
     *             "Bearer": {"write:teams"}
     *         }
     *     },
     * )
     */
    public function deleteTeam(Request $request): View
    {
        $teamId = $request->get('id');

        $this->teamService->deleteTeam($teamId);
        return View::create("Team (" . $teamId . ") deleted successfully", Response::HTTP_ACCEPTED);
    }
}
