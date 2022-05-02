<?php

namespace App\Repository;

use App\Entity\CrawlerStatJob;
use App\Entity\Link;
use App\Entity\LinkCrawlerTracking;
use App\Http\HttpClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LinkCrawlerTracking>
 *
 * @method LinkCrawlerTracking|null find($id, $lockMode = null, $lockVersion = null)
 * @method LinkCrawlerTracking|null findOneBy(array $criteria, array $orderBy = null)
 * @method LinkCrawlerTracking[]    findAll()
 * @method LinkCrawlerTracking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkCrawlerTrackingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LinkCrawlerTracking::class);
    }

    public function add(LinkCrawlerTracking $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(LinkCrawlerTracking $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return LinkCrawlerTracking[] Returns an array of LinkCrawlerTracking objects
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
    public function findOneBySomeField($value): ?LinkCrawlerTracking
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param Link $linkEntity
     * @param CrawlerStatJob $crawlerStatJob
     * @return LinkCrawlerTracking
     */
    public function start(Link $linkEntity, CrawlerStatJob $crawlerStatJob): LinkCrawlerTracking
    {
        $date = \DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
        $time = \DateTime::createFromFormat('H:i:s', date('H:i:s'));

        $tracking = new LinkCrawlerTracking();

        $tracking
            ->setCrawlerJobStat($crawlerStatJob)
            ->setLink($linkEntity)
            ->setIsStarted(true)
            ->setCompleteStatus(0)
            ->setStartDate($date)
            ->setStartTime($time)
            ;

        $this->_em->persist($tracking);

        $this->_em->flush();

        return $tracking;
    }

    public function complete(LinkCrawlerTracking $tracking, \App\Http\PageStat $stat)
    {
        $date = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

        $info = $stat->getInfo();

        $tracking
            ->setCompleteStatus(HttpClient::HTTP_REQUEST_SUCCESSFUL)
            ->setHttpStatus($info->getHttpCode())
            ->setPageLoadTime($info->getTotalTime())
            ->setImageCount(count($stat->getImages()))
            ->setExternalLinksCount(count($stat->getExternalLinks()))
            ->setInternalLinksCount(count($stat->getInternalLinks()))
            ->setUpdatedAt($date)
        ;

        $this->_em->persist($tracking);

        $this->_em->flush();
    }

    public function fail(LinkCrawlerTracking $tracking, \Exception|\App\Http\HttpException $e)
    {
        $date = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

        $tracking
            ->setCompleteStatus(HttpClient::HTTP_REQUEST_FAILED)
            ->setHttpStatus($e->getCode())
            ->setMessage($e->getMessage())
            ->setUpdatedAt($date)
        ;

        $this->_em->persist($tracking);

        $this->_em->flush();
    }
}
