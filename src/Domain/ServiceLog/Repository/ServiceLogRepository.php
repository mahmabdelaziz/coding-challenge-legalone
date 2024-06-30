<?php

namespace App\Domain\ServiceLog\Repository;

use App\Domain\ServiceLog\Entity\ServiceLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ServiceLog>
 */
class ServiceLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceLog::class);
    }

    public function insert(ServiceLog $serviceLog): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($serviceLog);
        $entityManager->flush();
    }

    public function getCountBy(?array $serviceNames, ?\DateTimeInterface $startDate, ?\DateTimeInterface $endDate, ?int $statusCode): int
    {
        $qb = $this->createQueryBuilder('l')
            ->select('count(l.id)'); // Select the count of entries

        if (!empty($serviceNames)) {
            $qb->andWhere('l.service IN (:services)')
                ->setParameter('services', $serviceNames);
        }

        if (!is_null($startDate) && !is_null($endDate)) {
            $qb->andWhere('l.requested_at BETWEEN :startDate AND :endDate')
                ->setParameter('startDate', $startDate)
                ->setParameter('endDate', $endDate);
        }

        if (!is_null($statusCode)) {
            $qb->andWhere('l.status_code = :statusCode')
                ->setParameter('statusCode', $statusCode);
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
