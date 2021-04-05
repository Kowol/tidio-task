<?php

declare(strict_types=1);

namespace App\Generator;

use App\DTO\Input\SalaryReportSearch;
use App\DTO\Output\EmploymentSalaryReport;

interface SalaryReportSearcherInterface
{
    /**
     * @param EmploymentSalaryReport[] $reports
     *
     * @return EmploymentSalaryReport[]
     */
    public function matching(array $reports, SalaryReportSearch $search): array;
}
