<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\EmployeeFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Employee>
 *
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function save(Employee $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Employee $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByForm(EmployeeFilter $filter)
    {
        $query = $this->createQueryBuilder('e');
        if ($filter->getEmployeeName()) {
            $query->where('e.name = :name')
                ->setParameter('name', $filter->getEmployeeName());
        }

        $query->setMaxResults($filter->getSize())
            ->setFirstResult(($filter->getPage() - 1) * $filter->getSize());

        return $query->getQuery()->getResult();
    }

    public function findByChiefId(EmployeeFilter $filter, int $chiefId)
    {
        $query = $this->createQueryBuilder('e');
        if ($filter->getEmployeeName()) {
            $query->AndWhere('e.name = :name')
                ->setParameter('name', $filter->getEmployeeName());
        }

        $query->andWhere('e.chief_id = :chiefId')
            ->setParameter('chiefId', $chiefId)
            ->setMaxResults($filter->getSize())
            ->setFirstResult(($filter->getPage() - 1) * $filter->getSize());

        return $query->getQuery()->getResult();
    }
}
