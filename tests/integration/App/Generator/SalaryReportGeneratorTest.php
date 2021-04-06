<?php

declare(strict_types=1);

namespace Tests\Integration\App\Generator;

use App\DTO\Input\SalaryReportOrder;
use App\DTO\Input\SalaryReportSearch;
use App\Generator\SalaryReportGenerator;
use Codeception\Test\Unit;
use Company\Model\Department;
use Company\Model\Employment;
use Company\Model\Money;
use Tests\_support\Mother\DepartmentMother;
use Tests\_support\Mother\EmploymentMother;
use Tests\IntegrationTester;

class SalaryReportGeneratorTest extends Unit
{
    protected IntegrationTester $tester;

    public function testItGeneratesReport(): void
    {
        $departmentA = DepartmentMother::basicPercentageSupplement($percentSupplementA = 10);
        $departmentB = DepartmentMother::basicPercentageSupplement($percentSupplementB = 20);

        $employmentA = $this->createEmployment($salaryBaseA = 500, $departmentA);
        $employmentB = $this->createEmployment($salaryBaseB = 10000, $departmentB);
        $employmentC = $this->createEmployment($salaryBaseB = 12000, $departmentB);

        $this->tester->haveInRepository($departmentA);
        $this->tester->haveInRepository($departmentB);
        $this->tester->haveInRepository($employmentA->getEmployee());
        $this->tester->haveInRepository($employmentB->getEmployee());
        $this->tester->haveInRepository($employmentC->getEmployee());

        $search = new SalaryReportSearch();
        $search->order = new SalaryReportOrder('salaryTotal', 'DESC');

        /** @var SalaryReportGenerator $generator */
        $generator = $this->tester->grabService(SalaryReportGenerator::class);

        $report = $generator->generate(new \DateTime(), $search);

        self::assertCount(3, $report);

        self::assertEquals(
            (new Money(14400))->getAmount(),
            $report[0]->salaryTotal
        );
        self::assertEquals(
            (new Money(12000))->getAmount(),
            $report[1]->salaryTotal
        );
        self::assertEquals(
            (new Money(550))->getAmount(),
            $report[2]->salaryTotal
        );
    }

    private function createEmployment(int $salaryBase, Department $department): Employment
    {
        return EmploymentMother::withSalaryAndDepartment($salaryBase, $department);
    }
}
