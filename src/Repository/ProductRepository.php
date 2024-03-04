<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findProductsGreaterThanPrice($price, $drivingSchool)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.productPrice > :val')
            ->andWhere('p.drivingSchool = :drivingSchool')
            ->setParameter('val', $price)
            ->setParameter('drivingSchool', $drivingSchool)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findProductsLessThanPrice($price, $drivingSchool)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.productPrice < :val')
            ->andWhere('p.drivingSchool = :drivingSchool')
            ->setParameter('val', $price)
            ->setParameter('drivingSchool', $drivingSchool)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findProductByNameAndDescription(string $search, $drivingSchool): array
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.productName LIKE :productName OR q.productDescription LIKE :productDescription')
            ->andWhere('q.drivingSchool = :drivingSchool')
            ->setParameter('productName', '%' . $search . '%')
            ->setParameter('productDescription', '%' . $search . '%')
            ->setParameter('drivingSchool', $drivingSchool)
            ->getQuery()
            ->getResult();
    }

    public function findByDrivingSchoolId($drivingSchoolId): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.drivingSchool = :drivingSchoolId')
            ->setParameter('drivingSchoolId', $drivingSchoolId)
            ->getQuery()
            ->getResult();
    }

    public function queryFindByDrivingSchool($drivingSchool): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.drivingSchool = :drivingSchool')
            ->setParameter('drivingSchool', $drivingSchool);
    }
}
