<?php

namespace App\Repository;

use App\Entity\Stream;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Stream>
 */
class StreamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stream::class);
    }

    public function findUsersWithAtLeastTwoStreamsIn2024()
    {
        $dateStart = new \DateTime('2024-01-01');
        $dateEnd = new \DateTime('2024-12-31');
        $dateEnd->setTime(23, 59, 59);

        return $this->createQueryBuilder('s')
            ->select('u.pseudo, COUNT(s.id) as stream_count')
            ->join('s.user', 'u')
            ->andWhere('s.startDate BETWEEN :dateStart AND :dateEnd')
            ->setParameter('dateStart', $dateStart)
            ->setParameter('dateEnd', $dateEnd)
            ->groupBy('u.pseudo')
            ->having('COUNT(s.id) >= 2')
            ->orderBy('stream_count', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Stream[] Returns an array of Stream objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Stream
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    //    public function findUsersWithAtLeastTwoStreamsIn2024()
    //    {
    //        $dateStart = new \DateTime('2024-01-01');
    //        $dateEnd = new \DateTime('2024-12-31');
    //        $dateEnd->setTime(23, 59, 59);
    //
    //        return $this->createQueryBuilder('s')
    //            ->select('u.pseudo, COUNT(s.id) as stream_count')
    //            ->join('s.user', 'u')
    //            ->andWhere('s.startDate BETWEEN :dateStart AND :dateEnd')
    //            ->setParameter('dateStart', $dateStart)
    //            ->setParameter('dateEnd', $dateEnd)
    //            ->groupBy('u.pseudo')
    //            ->having('COUNT(s.id) >= 2')
    //            ->orderBy('stream_count', 'DESC')
    //            ->getQuery()
    //            ->getResult();
    //    }


    public function findStreamsForTomorrow()
    {
        $dateStart = new \DateTime('tomorrow');
        $dateStart->setTime(0, 0, 0);
        $dateEnd = clone $dateStart;
        $dateEnd->setTime(23, 59, 59);

        return $this->createQueryBuilder('s')
            ->select('s, u.pseudo, j.name')
            ->join('s.user', 'u')
            ->join('s.jeu', 'j')
            ->andWhere('s.startDate BETWEEN :dateStart AND :dateEnd')
            ->setParameter('dateStart', $dateStart)
            ->setParameter('dateEnd', $dateEnd)
            ->orderBy('s.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
