<?php

namespace App\Repository;

use App\Entity\Queue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Stamp\TransportMessageIdStamp;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Queue>
 *
 * @method Queue|null find($id, $lockMode = null, $lockVersion = null)
 * @method Queue|null findOneBy(array $criteria, array $orderBy = null)
 * @method Queue[]    findAll()
 * @method Queue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QueueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Queue::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Queue $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Queue $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Queue[] Returns an array of Queue objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Queue
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @return ?Queue
     */
    public function get(): ?Queue
    {
        $result = $this->createQueryBuilder('q')
            ->andWhere('q.handled = 0')
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        return empty($result) ? null : $result[0];
    }

    public function ack(Queue $entity)
    {
        $entity->setHandled(true);

        $this->_em->persist($entity);
        $this->_em->flush();
    }

    public function reject(Queue $entity)
    {
        $this->remove($entity);
    }

    public function send(array $encodedMessage): Queue
    {
        $queueItem = new Queue();
        $queueItem
            ->setEnvelope($encodedMessage['body'])
            ->setHandled(false)
            ->setDeliveredAt( new \DateTimeImmutable())
            ;

        $this->_em->persist($queueItem);
        $this->_em->flush();

        return $queueItem;
    }
}
