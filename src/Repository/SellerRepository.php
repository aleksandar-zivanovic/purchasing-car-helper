<?php

namespace App\Repository;

use App\Entity\Car;
use App\Entity\Seller;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Seller>
 *
 * @method Seller|null find($id, $lockMode = null, $lockVersion = null)
 * @method Seller|null findOneBy(array $criteria, array $orderBy = null)
 * @method Seller[]    findAll()
 * @method Seller[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SellerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Seller::class);
    }

    public function findSellerIdsWithoutCars()
    {
        /**
         * SELECT id FROM `seller` WHERE id NOT IN (SELECT seller_id FROM `car`);
         */
            $subQuery = $this->getEntityManager()->createQueryBuilder()
                ->select('IDENTITY(c.seller)')
                ->from('App\Entity\Car', 'c')
                ->getDQL();
    
            $qb = $this->createQueryBuilder('s');
    
            $qb->select('s.id')
                ->where(
                    $qb->expr()->notIn('s.id', $subQuery)
                );
    
            // Vraćamo niz ID-jeva kao običan niz integera
            return array_map('intval', array_column($qb->getQuery()->getScalarResult(), 'id'));
        }

        public function findAllSellersWithCarIds()
        {
            return $this->createQueryBuilder('s')
                ->select('s', 'c')
                ->leftJoin('s.cars', 'c')
                ->orderBy('s.id','DESC')
                ->getQuery()
                ->getResult();
        }


    //    /**
    //     * @return Seller[] Returns an array of Seller objects
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

    //    public function findOneBySomeField($value): ?Seller
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
