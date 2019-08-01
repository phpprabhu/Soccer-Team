<?php

namespace App\Controller\Api;

use App\Entity\Player;
use App\Service\PlayerService;
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

/**
 * @Route("/api",name="api_")
 */
class PlayerController extends AbstractFOSRestController
{

    /**
     * @var PlayerService
     */
    private $playerService;

    /**
     * PlayerController constructor.
     * @param PlayerService $playerService
     */
    public function __construct(PlayerService $playerService)
    {
        $this->playerService = $playerService;
    }

    /**
     * Get Player detail
     *
     * @Rest\Get("/v1/player/{id}")
     * @SWG\Response(
     *     response=200,
     *     description="Get player detail",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Items(ref=@Model(type=Player::class))
     *     )
     * ),
     * @SWG\Response(response=404, description="Player not found"),
     * @SWG\Tag(name="Player")
     */
    public function getPlayer(Request $request): Response
    {
        $playerId = $request->get('id');
        $player = $this->playerService->getPlayerDetail($playerId);
        return $this->handleView($this->view($player));
    }

    /**
     * Update Player resource
     * @Rest\Put("/v1/player/{playerId}/update")
     * @param Request $request
     * @return View
     *
     *
     * @SWG\Put(path="/api/v1/player/{playerId}/update",
     *   tags={"Player"},
     *   summary="Update Player",
     *   description="API to update Player",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="playerId",
     *     in="path",
     *     description="Player id",
     *     required=true,
     *     type="integer"
     * ),
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="First Name, Last Name, Image URI & Team Id",
     *     required=true,
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="firstName", type="string"),
     *         @SWG\Property(property="lastName", type="string"),
     *         @SWG\Property(property="imageURI", type="string"),
     *         @SWG\Property(property="teamId", type="integer"),
     *     )
     * ),
     *     @SWG\Response(
     *         response=204,
     *         description="Player updated successfully",
     *         @SWG\Schema(type="string"),
     *     ),
     *     @SWG\Response(response=404, description="Player not found"),
     *     @SWG\Response(response=401, description="Unauthorized"),
     *   security={
     *         {
     *             "Bearer": {"write:teams"}
     *         }
     *     },
     * )
     */
    public function updatePlayer(Request $request): View
    {
        $data = $request->request->all();
        $playerId = $request->get('playerId');

        $player = $this->playerService->savePlayer($data, $playerId);
        return View::create("Player (" . $player->getId() . ") updated successfully", Response::HTTP_NO_CONTENT);
    }

    /**
     * Creates Player resource
     * @Rest\Post("/v1/player/add")
     * @param Request $request
     * @return View
     *
     *
     * @SWG\Post(path="/api/v1/player/add",
     *   tags={"Player"},
     *   summary="Add Player",
     *   description="API to add new player in team",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="First Name, Last Name, Image URI & Team Id",
     *     required=true,
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="firstName", type="string"),
     *         @SWG\Property(property="lastName", type="string"),
     *         @SWG\Property(property="imageURI", type="string"),
     *         @SWG\Property(property="teamId", type="integer"),
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
    public function addPlayer(Request $request): View
    {
        $data = $request->request->all();

        $player = $this->playerService->savePlayer($data);
        return View::create("Player (" . $player->getId() . ") successfully added", Response::HTTP_CREATED);
    }

    /**
     * Delete Player resource
     * @Rest\Delete("/v1/player/{id}/delete")
     * @param Request $request
     * @return View
     *
     *
     * @SWG\Delete(path="/api/v1/player/{id}/delete",
     *   tags={"Player"},
     *   summary="Delete Player",
     *   description="API to delete Player",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="Player id",
     *     required=true,
     *     type="integer"
     * ),
     *     @SWG\Response(
     *         response=202,
     *         description="Accepted for deletion",
     *         @SWG\Schema(type="string"),
     *     ),
     *     @SWG\Response(response=404, description="Player not found"),
     *     @SWG\Response(response=401, description="Unauthorized"),
     *   security={
     *         {
     *             "Bearer": {"write:teams"}
     *         }
     *     },
     * )
     */
    public function deletePlayer(Request $request): View
    {
        $playerId = $request->get('id');

        $this->playerService->deletePlayer($playerId);
        return View::create("Player (" . $playerId . ") deleted succesfully", Response::HTTP_ACCEPTED);
    }
}
