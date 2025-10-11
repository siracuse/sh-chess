<?php

namespace App\Repository;

use App\Entity\Puzzle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Puzzle>
 */
class PuzzleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Puzzle::class);
    }

    //    /**
    //     * @return Puzzle[] Returns an array of Puzzle objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

       public function findOneByThemeId($themeId): ?Puzzle
       {
           return $this->createQueryBuilder('p')
               ->andWhere('p.theme = :val')
               ->setParameter('val', $themeId)
               ->setMaxResults(1)
               ->getQuery()
               ->getOneOrNullResult()
           ;
       }

       public function findAllByThemeId($themeId)
       {
            return $this->createQueryBuilder('p')
               ->andWhere('p.theme = :val')
               ->setParameter('val', $themeId)
               ->getQuery()
               ->getResult()
           ;
       }
}
