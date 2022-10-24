<?php

namespace App\Repository;

use App\Entity\EmployeeFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmployeeFile>
 *
 * @method EmployeeFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmployeeFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmployeeFile[]    findAll()
 * @method EmployeeFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmployeeFile::class);
    }

    public function save(EmployeeFile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EmployeeFile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array
     */
    public function findTop(): array
    {
        return $this->createQueryBuilder('w')
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
}
