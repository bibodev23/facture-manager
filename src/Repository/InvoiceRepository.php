<?php

namespace App\Repository;

use App\Entity\Company;
use App\Entity\Customer;
use App\Entity\Invoice;
use App\Enum\InvoiceStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Invoice>
 */
class InvoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }
    public function findByCompanyWithRelations(Company $company, ?string $status = null): array
    {
        $qb = $this->createQueryBuilder('i')
            ->leftJoin('i.customer', 'c')->addSelect('c')     
            ->leftJoin('i.deliveries', 'd')->addSelect('d')  
            ->andWhere('i.company = :company')
            ->setParameter('company', $company)
            ->orderBy('i.date', 'DESC');

        if ($status) {
            $qb->andWhere('i.status = :status')
            ->setParameter('status', $status);
        }

        return $qb->getQuery()->getResult();
    }

    public function findByCustomer(Customer $customer): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.customer = :customer')
            ->setParameter('customer', $customer)
            ->orderBy('i.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // public function findByStatus(InvoiceStatus $status, Customer $customer): array
    // {
    //     return $this->createQueryBuilder('i')
    //         ->leftJoin('i.customer', 'c')->addSelect('c')
    //         ->andWhere('i.customer = :customer')
    //         ->andWhere('i.status = :status')
    //         ->setParameter('customer', $customer)
    //         ->setParameter('status', $status)
    //         ->orderBy('i.date', 'DESC')
    //         ->getQuery()
    //         ->getResult();
    // }

    //    /**
    //     * @return Invoice[] Returns an array of Invoice objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Invoice
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
