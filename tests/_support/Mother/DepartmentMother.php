<?php

declare(strict_types=1);

namespace Tests\_support\Mother;

use Company\Model\Department;
use Company\Model\Money;
use Company\Model\Supplement;

class DepartmentMother
{
    public static function basicFixedSupplement(int $supplementAmountInCents): Department
    {
        return new Department(
            'Some name',
            Supplement::fixed(new Money($supplementAmountInCents))
        );
    }

    public static function basicPercentageSupplement(int $supplementValue): Department
    {
        return new Department(
            'Some name',
            Supplement::percentage($supplementValue)
        );
    }
}
