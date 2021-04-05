<?php

declare(strict_types=1);

namespace Company\Provider\SalarySupplement;

use Company\Enum\DepartmentSalarySupplementType;
use Company\Model\Employment;
use Company\Model\Money;
use Company\Provider\SalarySupplement\Strategy\FixedProvider;
use Company\Provider\SalarySupplement\Strategy\PercentageProvider;

class SupplementProvider
{
    public function __construct(
        private PercentageProvider $percentageProvider,
        private FixedProvider $fixedProvider
    ) {
    }

    public function provide(Employment $employment, \DateTimeInterface $forDate): Money
    {
        $department = $employment->getDepartment();
        $salarySupplement = $department->getSalarySupplement();

        $amount = match ($salarySupplement->getType()) {
            DepartmentSalarySupplementType::PERCENTAGE => $this->percentageProvider->provide($employment),
            DepartmentSalarySupplementType::FIXED => $this->fixedProvider->provide($employment, $forDate),
        };

        return new Money($amount);
    }
}
