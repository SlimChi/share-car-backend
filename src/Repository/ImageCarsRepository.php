<?php

namespace App\Repository;

use App\Entity\ImageCars;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImageVoitures>
 *
 * @method ImageVoitures|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageVoitures|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageVoitures[]    findAll()
 * @method ImageVoitures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageCarsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageCars::class);
    }

//    /**
//     * @return ImageVoitures[] Returns an array of ImageVoitures objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ImageVoitures
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
