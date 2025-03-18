<?php

namespace App\Repository;

use App\Entity\Exercice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Exercice>
 */
class ExerciceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exercice::class);
    }

    public function findTopExercicesBySportifAndPeriod(string $sportifId, \DateTime $dateMin, \DateTime $dateMax, int $limit = 3)
    {
        return $this->createQueryBuilder('e')
            ->select('e.id, e.nom, COUNT(e.id) as frequence')
            ->join('e.seances', 's')
            ->join('s.sportifs', 'sp')
            ->where('sp.id = :sportifId')
            ->andWhere('s.date_heure >= :dateMin')
            ->andWhere('s.date_heure <= :dateMax')
            ->andWhere('s.statut = :statut')
            ->setParameter('sportifId', $sportifId)
            ->setParameter('dateMin', $dateMin)
            ->setParameter('dateMax', $dateMax)
            ->setParameter('statut', 'validée')
            ->groupBy('e.id')
            ->orderBy('frequence', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function calculateTotalDuration(string $sportifId, \DateTime $dateMin, \DateTime $dateMax)
    {
        $result = $this->createQueryBuilder('e')
            ->select('SUM(e.duree_estimee) as totalDuration')
            ->join('e.seances', 's')
            ->join('s.sportifs', 'sp')
            ->where('sp.id = :sportifId')
            ->andWhere('s.date_heure >= :dateMin')
            ->andWhere('s.date_heure <= :dateMax')
            ->andWhere('s.statut = :statut')
            ->setParameter('sportifId', $sportifId)
            ->setParameter('dateMin', $dateMin)
            ->setParameter('dateMax', $dateMax)
            ->setParameter('statut', 'validée')
            ->getQuery()
            ->getSingleScalarResult();
            
        return $result ? (int)$result : 0;
    }

    //    /**
    //     * @return Exercice[] Returns an array of Exercice objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Exercice
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
