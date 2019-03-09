<?php

namespace App\Repository;

use App\Entity\Aire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Aire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Aire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Aire[]    findAll()
 * @method Aire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AireRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Aire::class);
    }

    /**
     * Persists Aire object into database
     * @param Aire $aire
     */
    public function save(Aire $aire):Aire{
        $em = $this->getEntityManager();
        if (empty($aire->getId()) && !empty($aire->getUuid())) {
            $outAire = $this->findOneBy(["uuid"=>$aire->getUuid()]);
        } else {
            $outAire = $em->merge($aire);
        }
        $em->persist($outAire);
        $em->flush($outAire);
        return $outAire;
    }
    
    
    // /**
    //  * @return Aire[] Returns an array of Aire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Aire
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
