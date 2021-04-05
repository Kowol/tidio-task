<?php

declare(strict_types=1);

namespace App\Generator;

use App\DTO\Input\SalaryReportSearch;
use App\DTO\Output\EmploymentSalaryReport;
use App\Transformer\DomainModelToReportTransformer;
use Company\Model\Employee;
use Company\Repository\EmployeeRepositoryInterface;

class SalaryReportGenerator
{
    public function __construct(
        private EmployeeRepositoryInterface $employeeRepository,
        private DomainModelToReportTransformer $modelToOutputTransformer,
        private SalaryReportSearcherInterface $reportSearcher
    ) {
    }

    /**
     * @return EmploymentSalaryReport[]
     */
    public function generate(\DateTimeInterface $reportDate, SalaryReportSearch $input): array
    {
        $employees = $this->employeeRepository->findAllForSalaryReport();

        $models = array_map(
            fn (Employee $employee) => $this->modelToOutputTransformer->transform(
                $employee->getEmployment(),
                $reportDate
            ),
            $employees
        );

        return $this->reportSearcher->matching($models, $input);
    }
}
