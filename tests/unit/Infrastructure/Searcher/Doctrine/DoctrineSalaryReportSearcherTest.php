<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Searcher\Doctrine;

use App\DTO\Input\SalaryReportOrder;
use App\DTO\Input\SalaryReportSearch;
use App\DTO\Output\EmploymentSalaryReport;
use Infrastructure\Searcher\Doctrine\DoctrineSalaryReportSearcher;
use PHPUnit\Framework\TestCase;

class DoctrineSalaryReportSearcherTest extends TestCase
{
    /**
     * @param EmploymentSalaryReport[] $reports
     * @param SalaryReportSearch $search
     * @param EmploymentSalaryReport[] $expectedReports
     *
     * @dataProvider itGeneratesBasicReportDataProvider
     */
    public function testItGeneratesBasicReport(array $reports, SalaryReportSearch $search, array $expectedReports): void
    {
        $searcher = new DoctrineSalaryReportSearcher();
        $matching = $searcher->matching($reports, $search);

        self::assertSame($expectedReports, $matching);
    }

    public function itGeneratesBasicReportDataProvider(): array
    {
        $reports = [
            $reportA = $this->createSingleReport('Joe', 'Doe', 'Department name', 100, 10),
            $reportB = $this->createSingleReport('Foo', 'Bar', 'Department name', 50, 40),
            $reportC = $this->createSingleReport('John', 'Alfreda', 'Another', 120, 5),
        ];

        return [
            'no filter, no order' => [
                $reports,
                $search = $this->createSearch(),
                $expectedReports = [$reportA, $reportB, $reportC]
            ],
            'filter by first name, no order' => [
                $reports,
                $search = $this->createSearch(firstName: 'Jo'),
                $expectedReports = [$reportA, $reportC]
            ],
            'filter by last name, no order' => [
                $reports,
                $search = $this->createSearch(lastName: 'Alf'),
                $expectedReports = [$reportC]
            ],
            'filter by department name, no order' => [
                $reports,
                $search = $this->createSearch(departmentName: 'Department'),
                $expectedReports = [$reportA, $reportB]
            ],
            'no filter, order by salary total DESC' => [
                $reports,
                $search = $this->createSearch(orderByField: 'salaryTotal', orderByDirection: 'DESC'),
                $expectedReports = [$reportC, $reportA, $reportB]
            ],
            'no filter, order by department name ASC' => [
                $reports,
                $search = $this->createSearch(orderByField: 'departmentName', orderByDirection: 'ASC'),
                $expectedReports = [$reportC, $reportA, $reportB]
            ],
            'no filter, order by salary supplement amount name DESC' => [
                $reports,
                $search = $this->createSearch(orderByField: 'salarySupplementAmount', orderByDirection: 'DESC'),
                $expectedReports = [$reportB, $reportA, $reportC]
            ],
            'filter by department name, order by salary base ASC' => [
                $reports,
                $search = $this->createSearch(departmentName: 'Department', orderByField: 'salaryBase', orderByDirection: 'ASC'),
                $expectedReports = [$reportB, $reportA]
            ],
        ];
    }

    private function createSingleReport(
        string $firstName,
        string $lastName,
        string $departmentName,
        float $salaryBase,
        float $supplementAmount
    ): EmploymentSalaryReport {
        return new EmploymentSalaryReport(
            $firstName,
            $lastName,
            $departmentName,
            $salaryBase,
            $supplementAmount,
            'fixed',
            $salaryBase + $supplementAmount
        );
    }

    private function createSearch(
        string $firstName = null,
        string $lastName = null,
        string $departmentName = null,
        string $orderByField = null,
        string $orderByDirection = null
    ): SalaryReportSearch {
        $search = new SalaryReportSearch();
        $search->firstName = $firstName;
        $search->lastName = $lastName;
        $search->departmentName = $departmentName;

        if ($orderByField && $orderByDirection) {
            $search->order = new SalaryReportOrder($orderByField, $orderByDirection);
        }

        return $search;
    }
}
