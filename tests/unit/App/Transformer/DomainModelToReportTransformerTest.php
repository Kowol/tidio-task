<?php

declare(strict_types=1);

namespace Tests\Unit\App\Transformer;

use App\Transformer\DomainModelToReportTransformer;
use Company\Model\Money;
use Company\Provider\SalarySupplement\SupplementProvider;
use PHPUnit\Framework\TestCase;
use Tests\_support\Mother\DepartmentMother;
use Tests\_support\Mother\EmploymentMother;

class DomainModelToReportTransformerTest extends TestCase
{
    public function testItTransformsModelProperly(): void
    {
        $supplementProvider = $this->createMock(SupplementProvider::class);

        $transformer = new DomainModelToReportTransformer($supplementProvider);

        $department = DepartmentMother::basicPercentageSupplement(10);
        $employment = EmploymentMother::withSalaryAndDepartment($salaryBaseInCents = 10000, $department);
        $employee = $employment->getEmployee();

        $supplementProvider->method('provide')->willReturn($supplementAmount = new Money(1000));
        $totalSalary = new Money($salaryBaseInCents + $supplementAmount->getAmountInCents());

        $report = $transformer->transform($employment, new \DateTime());

        self::assertEquals($employee->getFirstName(), $report->firstName);
        self::assertEquals($employee->getLastName(), $report->lastName);
        self::assertEquals($department->getName(), $report->departmentName);
        self::assertEquals($employment->getSalary()->getAmount(), $report->salaryBase);
        self::assertEquals($supplementAmount->getAmount(), $report->salarySupplementAmount);
        self::assertEquals('percentage', $report->salarySupplementType);
        self::assertEquals($totalSalary->getAmount(), $report->salaryTotal);
    }
}
