<?php

namespace App\Repository;

use App\Entity\CrawlerStatJob;
use App\Http\WebsiteStat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CrawlerStatJob>
 *
 * @method CrawlerStatJob|null find($id, $lockMode = null, $lockVersion = null)
 * @method CrawlerStatJob|null findOneBy(array $criteria, array $orderBy = null)
 * @method CrawlerStatJob[]    findAll()
 * @method CrawlerStatJob[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CrawlerStatJobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CrawlerStatJob::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(CrawlerStatJob $entity, bool $flush = true): void
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
    public function remove(CrawlerStatJob $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return CrawlerJobStat[] Returns an array of CrawlerJobStat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CrawlerJobStat
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function create(): CrawlerStatJob
    {
        $job = new CrawlerStatJob();

        $this->_em->persist($job);

        $this->_em->flush();

        return $job;
    }

    public function start(?CrawlerStatJob $job, ?\App\Entity\Link $initialLinkEntity)
    {
        $date = \DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
        $time = \DateTime::createFromFormat('H:i:s', date('H:i:s'));

        $job
            ->setInitialLink($initialLinkEntity)
            ->setStartDate($date)
            ->setStartTime($time)
            ;

        $this->_em->persist($job);

        $this->_em->flush();
    }

    public function update(CrawlerStatJob $job, WebsiteStat $stat)
    {
        $date = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

        $job
            ->setTotalPagesCrawled($stat->getNumPagesVisited())
            ->setImageTotal($stat->getImagesTotal())
            ->setInternalLinkTotal($stat->getInternalLInksTotal())
            ->setExternalLinkTotal($stat->getExternalLinksTotal())
            ->setAveragePageLoadTime($stat->getAveragePageLoadTime())
            ->setAverageWordCount($stat->getAverageWordCount())
            ->setAverageTitleLength($stat->getAveragePageTitleLength())
            ->setUpdatedAt($date)
            ;

        $this->_em->persist($job);

        $this->_em->flush();
    }
}
