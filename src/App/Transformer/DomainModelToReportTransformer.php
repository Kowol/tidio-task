<?php

declare(strict_types=1);

namespace App\Transformer;

use App\DTO\Output\EmploymentSalaryReport;
use Company\Enum\DepartmentSalarySupplementType;
use Company\Model\Employment;
use Company\Model\Money;
use Company\Provider\SalarySupplement\SupplementProvider;

class DomainModelToReportTransformer
{
    private const SUPPLEMENT_TYPE_TO_TEXT = [
        DepartmentSalarySupplementType::PERCENTAGE => 'percentage',
        DepartmentSalarySupplementType::FIXED => 'fixed',
    ];

    public function __construct(
        private SupplementProvider $supplementProvider
    ) {
    }

    public function transform(Employment $employmentModel, \DateTimeInterface $forDate): EmploymentSalaryReport
    {
        $employee = $employmentModel->getEmployee();
        $department = $employmentModel->getDepartment();
        $salary = $employmentModel->getSalary();
        $supplement = $department->getSalarySupplement();

        $supplementValue = $this->supplementProvider->provide($employmentModel, $forDate);
        $totalSalary = new Money($salary->getAmountInCents() + $supplementValue->getAmountInCents());

        return new EmploymentSalaryReport(
            $employee->getFirstName(),
            $employee->getLastName(),
            $department->getName(),
            $salary->getAmount(),
            $supplementValue->getAmount(),
            self::SUPPLEMENT_TYPE_TO_TEXT[$supplement->getType()],
            $totalSalary->getAmount(),
        );
    }
}
