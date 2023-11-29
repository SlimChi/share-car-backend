<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

        /**
     * Find all users except the specified user.
     *
     * @param int $userId The ID of the user to exclude.
     * @return User[]
     */
    public function findAllExceptCurrentUser(int $userId): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id != :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    public function getMessagesByUsers($recipientId, $otherUserId)
{
    return $this->createQueryBuilder('m')
        ->andWhere('(m.sender = :recipientId AND m.recipient = :otherUserId) OR (m.sender = :otherUserId AND m.recipient = :recipientId)')
        ->setParameter('recipientId', $recipientId)
        ->setParameter('otherUserId', $otherUserId)
        ->getQuery()
        ->getResult();
}
//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
