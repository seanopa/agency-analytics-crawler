<?php

namespace App\Repository;

use App\Entity\Host;
use App\Entity\Link;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Link>
 *
 * @method Link|null find($id, $lockMode = null, $lockVersion = null)
 * @method Link|null findOneBy(array $criteria, array $orderBy = null)
 * @method Link[]    findAll()
 * @method Link[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Link::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Link $entity, bool $flush = true): void
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
    public function remove(Link $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Link[] Returns an array of Link objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Link
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function isProcessing(string $urlLink)
    {
    }

    public function start(string $url)
    {
        $date = \DateTime::createFromFormat( 'Y-m-d H:i:s', date('Y-m-d H:i:s'));

        $Link = new Link();
        $Link
            ->setUrl($url)
            ->setCreatedAt($date)
            ;

        $this->_em->persist($Link);

        $this->_em->flush();

        return $Link;
    }


    public function create(Host $host, string $url): Link
    {
        $date = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

        $link = new Link();
        $link
            ->setHost($host)
            ->setUrl($url)
            ->setCreatedAt($date)
            ->setUpdatedAt($date)
            ;

        $this->_em->persist($link);

        $this->_em->flush();

        return $link;
    }

    public function updateTitle(Link $link, $title): Link
    {
        $date = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

        $link
            ->setTitle($title)
            ->setUpdatedAt($date)
        ;

        $this->_em->persist($link);

        $this->_em->flush();

        return $link;
    }
}
