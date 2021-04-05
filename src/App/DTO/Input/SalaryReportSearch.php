<?php

declare(strict_types=1);

namespace App\DTO\Input;

class SalaryReportSearch
{
    public ?string $departmentName = null;

    public ?string $firstName = null;

    public ?string $lastName = null;

    public ?SalaryReportOrder $order = null;
}
