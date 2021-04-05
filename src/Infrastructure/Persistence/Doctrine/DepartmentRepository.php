<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Doctrine;

use Company\Model\Department;
use Company\Repository\DepartmentRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DepartmentRepository extends ServiceEntityRepository implements DepartmentRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Department::class);
    }

    /**
     * @return Department[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }

    public function add(Department $department): void
    {
        $this->getEntityManager()->persist($department);
    }
}
