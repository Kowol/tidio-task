<?php

declare(strict_types=1);

namespace App\DTO\Input;

use App\DTO\Output\EmploymentSalaryReport;
use Webmozart\Assert\Assert;

class SalaryReportOrder
{
    public function __construct(
        public string $field,
        public string $direction
    ) {
        Assert::inArray($this->field, array_keys(get_class_vars(EmploymentSalaryReport::class)));
        Assert::inArray($this->direction, ['ASC', 'DESC']);
    }
}
