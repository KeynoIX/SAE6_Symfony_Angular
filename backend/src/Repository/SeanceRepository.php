<?php

namespace App\Repository;

use App\Entity\Seance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Seance>
 */
class SeanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Seance::class);
    }

    public function findSeancesBySportifAndPeriod(string $sportifId, \DateTime $dateMin, \DateTime $dateMax)
    {
        return $this->createQueryBuilder('s')
            ->join('s.sportifs', 'sp')
            ->where('sp.id = :sportifId')
            ->andWhere('s.date_heure >= :dateMin')
            ->andWhere('s.date_heure <= :dateMax')
            ->andWhere('s.statut = :statut')
            ->setParameter('sportifId', $sportifId)
            ->setParameter('dateMin', $dateMin)
            ->setParameter('dateMax', $dateMax)
            ->setParameter('statut', 'validée')
            ->orderBy('s.date_heure', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getSeanceTypeDistribution(string $sportifId, \DateTime $dateMin, \DateTime $dateMax)
    {
        $results = $this->createQueryBuilder('s')
            ->select('s.type_seance, COUNT(s.id) as count')
            ->join('s.sportifs', 'sp')
            ->where('sp.id = :sportifId')
            ->andWhere('s.date_heure >= :dateMin')
            ->andWhere('s.date_heure <= :dateMax')
            ->andWhere('s.statut = :statut')
            ->setParameter('sportifId', $sportifId)
            ->setParameter('dateMin', $dateMin)
            ->setParameter('dateMax', $dateMax)
            ->setParameter('statut', 'validée')
            ->groupBy('s.type_seance')
            ->getQuery()
            ->getResult();
            
        $distribution = [];
        foreach ($results as $result) {
            $distribution[$result['type_seance']] = (int)$result['count'];
        }
        
        return $distribution;
    }

    //    /**
    //     * @return Seance[] Returns an array of Seance objects
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

    //    public function findOneBySomeField($value): ?Seance
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
