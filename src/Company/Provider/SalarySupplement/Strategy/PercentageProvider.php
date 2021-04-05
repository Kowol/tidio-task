<?php

declare(strict_types=1);

namespace Company\Provider\SalarySupplement\Strategy;

use Company\Model\Employment;
use Company\Provider\SalarySupplement\Exception\InvalidSupplementValueException;

class PercentageProvider
{
    public function provide(Employment $employment): int
    {
        $salary = $employment->getSalary();
        $department = $employment->getDepartment();
        $salarySupplement = $department->getSalarySupplement();
        $supplementPercents = $salarySupplement->getPercents();

        if (null === $supplementPercents) {
            throw new InvalidSupplementValueException('Supplement value should not be null');
        }

        return (int) ($salary->getAmountInCents() * ($supplementPercents / 100));
    }
}
