<?php

declare(strict_types=1);

namespace Tests\Unit\Company\Provider\SalarySupplement\Strategy;

use Company\Provider\SalarySupplement\Strategy\PercentageProvider;
use PHPUnit\Framework\TestCase;
use Tests\_support\Mother\DepartmentMother;
use Tests\_support\Mother\EmploymentMother;

class PercentageProviderTest extends TestCase
{
    /**
     * @param int $baseSalaryInCents
     * @param int $percentageSupplementValue
     * @param int $expectedSupplementInCents
     *
     * @dataProvider itCalculatesSalarySupplementDataProvider
     */
    public function testItCalculatesSalarySupplement(
        int $baseSalaryInCents,
        int $percentageSupplementValue,
        int $expectedSupplementInCents
    ): void {
        $department = DepartmentMother::basicPercentageSupplement($percentageSupplementValue);
        $employment = EmploymentMother::withSalaryAndDepartment($baseSalaryInCents, $department);

        $provider = new PercentageProvider();

        self::assertEquals($expectedSupplementInCents, $provider->provide($employment));
    }

    public function itCalculatesSalarySupplementDataProvider(): array
    {
        return [
            [
                $baseSalaryInCents = 10000,
                $percentageSupplementValue = 10,
                $expectedSupplementInCents = 1000,
            ],
            [
                $baseSalaryInCents = 10000,
                $percentageSupplementValue = 100,
                $expectedSupplementInCents = 10000,
            ],
        ];
    }
}
