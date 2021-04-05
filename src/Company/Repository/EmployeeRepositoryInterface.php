<?php

declare(strict_types=1);

namespace Company\Repository;

use Company\Model\Employee;

interface EmployeeRepositoryInterface
{
    /**
     * @return Employee[]
     */
    public function findAllForSalaryReport(): array;

    public function add(Employee $employee): void;
}
