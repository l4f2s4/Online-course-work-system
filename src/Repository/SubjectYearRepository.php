<?php

namespace App\Repository;

use App\Entity\SubjectYear;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubjectYear|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubjectYear|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubjectYear[]    findAll()
 * @method SubjectYear[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubjectYearRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubjectYear::class);
    }

    // /**
    //  * @return SubjectYear[] Returns an array of SubjectYear objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SubjectYear
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
