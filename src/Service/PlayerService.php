<?php

namespace App\Service;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PlayerService
{
    /**
     * @var PlayerRepository
     */
    private $playerRepository;

    /**
     * @var TeamRepository
     */
    private $teamRepository;

    /**
     * @var ValidatorService
     */
    private $validatorService;

    /**
     * TeamService constructor.
     * @param PlayerRepository $playerRepository
     * @param TeamRepository $teamRepository
     * @param ValidatorService $validatorService
     */
    public function __construct(PlayerRepository $playerRepository, TeamRepository $teamRepository, ValidatorService $validatorService)
    {
        $this->playerRepository = $playerRepository;
        $this->teamRepository = $teamRepository;
        $this->validatorService = $validatorService;
    }

    /**
     * Get player detail
     *
     * @param int $playerId
     * @return Player|null
     */
    public function getPlayerDetail(int $playerId): ?array
    {
        $player = $this->playerRepository->getPlayerDetail($playerId);
        if(empty($player)){
            throw new HttpException(Response::HTTP_NOT_FOUND, "Player (".$playerId.") not found");
        }
        return $player;
    }

    /**
     * Delete a player
     *
     * @param int $playerId
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deletePlayer(int $playerId): void
    {
        $player = $this->playerRepository->getPlayer($playerId);
        $this->playerRepository->delete($player);
    }

    /**
     * Save New or update Player
     *
     * @param array $data
     * @param null $playerId
     * @return Player
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function savePlayer(array $data = array(), $playerId = null): Player
    {
        $player = $this->playerRepository->getPlayer($playerId, true);
        $player->setAttributes($data);

        if (isset($data['teamId'])) {
            $teamId = $data['teamId'];
            $team = $this->teamRepository->getTeam($teamId);
            $player->setTeam($team);
        }

        if ($this->validatorService->Validator($player)) {
            $player = $this->playerRepository->save($player);
        }
        return $player;
    }

}