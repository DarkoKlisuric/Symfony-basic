<?php

namespace App\Repository;

use App\Entity\MicroPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MicroPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method MicroPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method MicroPost[]    findAll()
 * @method MicroPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MicroPostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MicroPost::class);
    }

    public function findAllByUsers(Collection $users)
    {
        $qb = $this->createQueryBuilder('mp');
         return $qb->select('mp')
            ->where('mp.user in (:following)')
            ->setParameter('following' , $users)
            ->orderBy('mp.time' , 'DESC')
            ->getQuery()
            ->getResult();

    }

    // /**
    //  * @return MicroPost[] Returns an array of MicroPost objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MicroPost
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
