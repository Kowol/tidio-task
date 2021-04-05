<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Doctrine;

use Company\Model\Employee;
use Company\Repository\EmployeeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EmployeeRepository extends ServiceEntityRepository implements EmployeeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function add(Employee $employee): void
    {
        $this->getEntityManager()->persist($employee);
    }

    /**
     * @return Employee[]
     */
    public function findAllForSalaryReport(): array
    {
        $qb = $this->createQueryBuilder('employee')
            ->addSelect(['employment', 'department'])
            ->join('employee.employment', 'employment')
            ->join('employment.department', 'department');

        return $qb->getQuery()->getResult();
    }
}
