<?php


namespace App\Repository;
use App\Entity\User;
use App\Entity\Chat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Chat>
 *
 * @method Chat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chat[]    findAll()
 * @method Chat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chat::class);
    }

    /**
     * Get messages between two users.
     *
     * @param int $recipientId
     * @param int $otherUserId
     * @return Chat[]
     */
    public function getMessagesByUsers($recipientId, $otherUserId)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('(m.sender = :recipientId AND m.recipient = :otherUserId) OR (m.sender = :otherUserId AND m.recipient = :recipientId)')
            ->setParameter('recipientId', $recipientId)
            ->setParameter('otherUserId', $otherUserId)
            ->getQuery()
            ->getResult();
    }

    public function deleteAllChatsForUser(User $user)
{
    $qb = $this->createQueryBuilder('c');
    $qb
        ->delete(Chat::class, 'c')
        ->where('c.sender = :user OR c.recipient = :user')
        ->setParameter('user', $user)
        ->getQuery()
        ->execute();
}

public function findChatByIdForUser($id, User $user)
{
    return $this->createQueryBuilder('c')
        ->where('c.id = :id AND (c.sender = :user OR c.recipient = :user)')
        ->setParameter('id', $id)
        ->setParameter('user', $user)
        ->getQuery()
        ->getOneOrNullResult();
}
}
