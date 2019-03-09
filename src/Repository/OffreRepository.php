<?php

namespace App\Repository;

use App\Entity\Offre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\Tag;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Expr\Join;
use App\Entity\Aire;
use Doctrine\ORM\AbstractQuery;

/**
 * @method Offre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offre[]    findAll()
 * @method Offre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Offre::class);
    }

    /**
     * Persists Offre object into database
     * @param Offre $offre
     */
    public function save(Offre $offre):Offre{
        $em = $this->getEntityManager();
        if (empty($offre->getId()) && !empty($offre->getUuid())) {
            $outOffre = $this->findOneBy(["uuid"=>$offre->getUuid()]);
        } else {
            $outOffre = $em->merge($offre);
        }
        if($outOffre == null){
            $outOffre = $em->merge($offre);
        }
        $em->persist($outOffre);
        $em->flush($outOffre);
        return $outOffre;
    }
    /**
     * 
     * @param Tag $tag
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    public function findWithTag(Tag $tag){
        return $this->createQueryBuilder('o')
        ->select('o')
        ->join(Aire::class, 'a',Join::WITH,'o.aire = a.id')
        ->join(Tag::class, 't',Join::WITH,'t.aire = a.id')
        ->andWhere('t = :tag')
        ->setParameter('tag', $tag)
        ->getQuery()
        ->getResult(AbstractQuery::HYDRATE_SIMPLEOBJECT);
        ;
    }
    
    // /**
    //  * @return Offre[] Returns an array of Offre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Offre
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
