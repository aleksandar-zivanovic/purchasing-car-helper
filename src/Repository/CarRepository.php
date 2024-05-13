<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Car>
 *
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function allCarsWithAllDetails(string $order = 'DESC', bool $communication = false): array
    {
        $qb =  $this->createQueryBuilder('c')
            ->select('c', 'e', 's', 'cm')
            ->leftJoin('c.engine', 'e')
            ->leftJoin('c.seller', 's')
            ->leftjoin('c.communication', 'cm')
            ->addOrderBy('c.id', $order);

            if($communication) $qb->andWhere('cm.id IS NOT NULL');

        return $qb->getQuery()->getResult();
    }

    public function singleCarWithAllDetails($id): array
    {
        return $this->createQueryBuilder('c')
            ->select('c', 'e', 's', 'cm')
            ->leftJoin('c.engine', 'e')
            ->leftJoin('c.seller', 's')
            ->leftjoin('c.communication', 'cm')
            ->where('c.id = ' . $id)
            ->orderBy('cm.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function allCarsByModel(
        string $brand, 
        string $model, 
        int $engine, 
        string $fuel, 
        string $order
    ): array
    {
        $fuel = $fuel == 'D' ? 'Diesel' : 'Petrol';

        return $this->createQueryBuilder('c')
                    ->select('c', 'e', 's', 'cm')
                    ->leftJoin('c.engine', 'e')
                    ->leftJoin('c.seller', 's')
                    ->leftJoin('c.communication', 'cm')
                    ->where('c.brand =  :brand')
                    ->andWhere('c.model =  :model')
                    ->andWhere('e.engineDisplacement =  :engine')
                    ->andWhere('e.fuelType =  :fuel')
                    ->setParameters(new ArrayCollection([
                        new Parameter(':brand', $brand), 
                        new Parameter(':model', $model), 
                        new Parameter(':engine', $engine), 
                        new Parameter(':fuel', $fuel)
                    ]))
                    ->addOrderBy('c.id', $order)
                    ->getQuery()
                    ->getResult();
    }

    //    /**
    //     * @return Car[] Returns an array of Car objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Car
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
