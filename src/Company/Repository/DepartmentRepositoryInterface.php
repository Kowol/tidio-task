<?php

declare(strict_types=1);

namespace Company\Repository;

use Company\Model\Department;

interface DepartmentRepositoryInterface
{
    /**
     * @return Department[]
     */
    public function findAll(): array;

    public function add(Department $department): void;
}
