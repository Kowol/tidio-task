<?php

declare(strict_types=1);

namespace Tests\Integration\App\Generator;

use App\DTO\Input\SalaryReportOrder;
use App\DTO\Input\SalaryReportSearch;
use App\Generator\SalaryReportGenerator;
use Codeception\Test\Unit;
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
        $employmentA = $this->createEmployment($salaryBaseA = 500, $percentSupplementA = 10);
        $employmentB = $this->createEmployment($salaryBaseB = 10000, $percentSupplementB = 20);

        $this->tester->haveInRepository($employmentA->getDepartment());
        $this->tester->haveInRepository($employmentA->getEmployee());
        $this->tester->haveInRepository($employmentB->getDepartment());
        $this->tester->haveInRepository($employmentB->getEmployee());

        /** @var SalaryReportGenerator $generator */
        $generator = $this->tester->grabService(SalaryReportGenerator::class);

        $search = new SalaryReportSearch();
        $search->order = new SalaryReportOrder('salaryTotal', 'DESC');

        $report = $generator->generate(new \DateTime(), $search);

        self::assertEquals(
            (new Money(12000))->getAmount(),
            $report[1]->salaryTotal
        );
        self::assertEquals(
            (new Money(550))->getAmount(),
            $report[0]->salaryTotal
        );
    }

    private function createEmployment(int $salaryBase, int $salaryPercentSupplement): Employment
    {
        $department = DepartmentMother::basicPercentageSupplement($salaryPercentSupplement);
        return EmploymentMother::withSalaryAndDepartment($salaryBase, $department);
    }
}
