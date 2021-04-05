<?php

declare(strict_types=1);

namespace App\DTO\Output;

class EmploymentSalaryReport
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $departmentName,
        public float $salaryBase,
        public float $salarySupplementAmount,
        public string $salarySupplementType,
        public float $salaryTotal,
    ) {
    }
}
