<?php

declare(strict_types=1);

namespace Company\Provider\SalarySupplement\Strategy;

use Company\Model\Employment;
use Company\Provider\SalarySupplement\Exception\InvalidSupplementValueException;

class FixedProvider
{
    public function __construct(
        private int $maxYearsForSupplement
    ) {
    }

    public function provide(Employment $employment, \DateTimeInterface $forDate): int
    {
        if ($forDate < $employment->getEmploymentDate()) {
            return 0;
        }

        $department = $employment->getDepartment();
        $salarySupplement = $department->getSalarySupplement();
        $salarySupplementValue = $salarySupplement->getFixedValue();

        if (null === $salarySupplementValue) {
            throw new InvalidSupplementValueException('Supplement value should not be null');
        }

        $intervalBetweenDates = $forDate->diff($employment->getEmploymentDate());
        $yearsInCompany = $intervalBetweenDates->y;
        $yearsToCalculateSalary = min($yearsInCompany, $this->maxYearsForSupplement);

        return $salarySupplementValue->getAmountInCents() * $yearsToCalculateSalary;
    }
}
