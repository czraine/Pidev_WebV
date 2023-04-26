<?php

namespace App\Repository;

use App\Entity\Favplaces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FavplacesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Favplaces::class);
    }

    public function findFavoritePlace(int $userId, int $placeId): ?Favplaces
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.idUser = :userId')
            ->andWhere('f.idPlace = :placeId')
            ->setParameter('userId', $userId)
            ->setParameter('placeId', $placeId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
