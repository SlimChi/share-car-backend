<?php

namespace App\Repository;

use App\Entity\Com;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Com>
 *
 * @method Com|null find($id, $lockMode = null, $lockVersion = null)
 * @method Com|null findOneBy(array $criteria, array $orderBy = null)
 * @method Com[]    findAll()
 * @method Com[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Com::class);
    }

//    /**
//     * @return Com[] Returns an array of Com objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Com
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
