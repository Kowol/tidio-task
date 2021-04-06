<?php

declare(strict_types=1);

namespace Tests\Unit\Company\Provider\SalarySupplement\Strategy;

use Company\Provider\SalarySupplement\Strategy\FixedProvider;
use PHPUnit\Framework\TestCase;
use Tests\_support\Mother\DepartmentMother;
use Tests\_support\Mother\EmploymentMother;

class FixedProviderTest extends TestCase
{
    /**
     * @param int $baseSalaryInCents
     * @param int $fixedSupplementAmountInCents
     * @param int $maxYearsForSupplementCalculation
     * @param \DateTimeInterface $hiredOn
     * @param \DateTimeInterface $calculationDate
     * @param int $expectedSupplementInCents
     *
     * @dataProvider itCalculatesSalarySupplementDataProvider
     */
    public function testItCalculatesSalarySupplement(
        int $baseSalaryInCents,
        int $fixedSupplementAmountInCents,
        int $maxYearsForSupplementCalculation,
        \DateTimeInterface $hiredOn,
        \DateTimeInterface $calculationDate,
        int $expectedSupplementInCents
    ): void {
        $department = DepartmentMother::basicFixedSupplement($fixedSupplementAmountInCents);
        $employment = EmploymentMother::withSalaryAndDepartment($baseSalaryInCents, $department, $hiredOn);

        $provider = new FixedProvider($maxYearsForSupplementCalculation);

        self::assertEquals($expectedSupplementInCents, $provider->provide($employment, $calculationDate));
    }

    public function itCalculatesSalarySupplementDataProvider(): array
    {
        $baseSalaryInCents = 20000;
        $fixedSupplementAmountInCents = 10000;

        return [
            'max calculation years are 10, years worked 0' => [
                $baseSalaryInCents,
                $fixedSupplementAmountInCents,
                $maxYearsForSupplementCalculation = 10,
                $hiredOn = new \DateTime('2021-01-10'),
                $calculationDate = new \DateTime('2021-08-01'),
                $expectedSupplementInCents = 0,
            ],
            'max calculation years are 10, calculation date before hired' => [
                $baseSalaryInCents,
                $fixedSupplementAmountInCents,
                $maxYearsForSupplementCalculation = 10,
                $hiredOn = new \DateTime('2021-01-10'),
                $calculationDate = new \DateTime('2020-08-01'),
                $expectedSupplementInCents = 0,
            ],
            'max calculation years are 10, years worked 1' => [
                $baseSalaryInCents,
                $fixedSupplementAmountInCents,
                $maxYearsForSupplementCalculation = 10,
                $hiredOn = new \DateTime('2021-01-10'),
                $calculationDate = new \DateTime('2022-01-10'),
                $expectedSupplementInCents = $fixedSupplementAmountInCents,
            ],
            'max calculation years are 10, years worked 9' => [
                $baseSalaryInCents,
                $fixedSupplementAmountInCents,
                $maxYearsForSupplementCalculation = 10,
                $hiredOn = new \DateTime('2021-01-10'),
                $calculationDate = new \DateTime('2030-01-10'),
                $expectedSupplementInCents = $fixedSupplementAmountInCents * 9,
            ],
            'max calculation years are 10, years worked 15' => [
                $baseSalaryInCents,
                $fixedSupplementAmountInCents,
                $maxYearsForSupplementCalculation = 10,
                $hiredOn = new \DateTime('2021-01-10'),
                $calculationDate = new \DateTime('2036-01-10'),
                $expectedSupplementInCents = $fixedSupplementAmountInCents * 10,
            ],
        ];
    }
}
