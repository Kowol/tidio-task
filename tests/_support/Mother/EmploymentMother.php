<?php

declare(strict_types=1);

namespace Tests\_support\Mother;

use Company\Model\Department;
use Company\Model\Employee;
use Company\Model\Employment;
use Company\Model\Money;

class EmploymentMother
{
    public static function withSalaryAndDepartment(
        int $salaryInCents,
        Department $department,
        \DateTimeInterface $hiredOn = null
    ): Employment {
        $employee = new Employee('Joe', 'Doe');
        $employment = new Employment(
            $employee,
            $department,
            new Money($salaryInCents),
            $hiredOn ?? new \DateTime()
        );

        $employee->setEmployment($employment);

        return $employment;
    }
}
