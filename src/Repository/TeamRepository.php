<?php

namespace App\Repository;

use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    /**
     * TeamRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Team::class);
    }

    /**
     * @return array Team
     */
    public function getTeams()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select(array('t.id', 't.name', 't.logo'))
            ->from('App\Entity\Team', 't')
            ->getQuery()->getResult();
    }

    /**
     * Get team
     *
     * @param $teamId
     * @param bool $createNewIfNull
     * @return Team|null
     */
    public function getTeam($teamId = null, $createNewIfNull = false){
        if($createNewIfNull && !$teamId){
            return new Team();
        }

        $team = $this->find($teamId);
        if (!$team) {
            throw new HttpException(Response::HTTP_NOT_FOUND, "Team not found");
        }
        return $team;
    }

    /**
     * Get team detail with no.of players
     *
     * @param $teamId
     * @param bool $createNewIfNull
     * @return Team|null
     */
    public function getTeamDetail($teamId){
        $qb = $this->getEntityManager()->createQueryBuilder();
        return  $qb->select(array('t.id', 't.name', 't.logo', 'count(p.id) as noOfPlayers'))
            ->from('App\Entity\Team', 't')
            ->leftJoin('App\Entity\Player', 'p', Join::WITH, 't.id = p.team')
            ->where('t.id = :teamId')
            ->setParameter('teamId', $teamId)
            ->groupBy('t.id')
            ->getQuery()->getResult();
    }

    /**
     * Get all team players
     *
     * @param $teamId
     * @param $offset
     * @param $limit
     * @return mixed
     */
    public function getTeamPlayers($teamId, $offset, $limit)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select(array('p.id','p.firstName', 'p.lastName', 'p.imageURI'))
            ->from('App\Entity\Player', 'p')
            ->leftJoin('App\Entity\Team', 't', Join::WITH, 'p.team = t.id')
            ->where('p.team = :teamId')
            ->setParameter('teamId', $teamId)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()->getResult();
    }

    /**
     * Save Team
     *
     * @param Team $team
     * @return Team
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Team $team){
        $entityManager = $this->getEntityManager();
        $entityManager->persist($team);
        $entityManager->flush();
        return $team;
    }

    /**
     * Delete Team
     *
     * @param Team $team
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Team $team){
        $entityManager = $this->getEntityManager();
        $entityManager->remove($team);
        $entityManager->flush();
    }
}
