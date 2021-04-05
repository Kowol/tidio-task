<?php

declare(strict_types=1);

namespace Infrastructure\Searcher\Doctrine;

use App\DTO\Input\SalaryReportSearch;
use App\DTO\Output\EmploymentSalaryReport;
use App\Generator\SalaryReportSearcherInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

class DoctrineSalaryReportSearcher implements SalaryReportSearcherInterface
{
    /**
     * @param EmploymentSalaryReport[] $reports
     *
     * @return EmploymentSalaryReport[]
     */
    public function matching(array $reports, SalaryReportSearch $search): array
    {
        $collection = new ArrayCollection($reports);
        $criteria = Criteria::create();

        if ($search->departmentName) {
            $criteria->andWhere(Criteria::expr()->contains('departmentName', $search->departmentName));
        }

        if ($search->firstName) {
            $criteria->andWhere(Criteria::expr()->contains('firstName', $search->firstName));
        }

        if ($search->lastName) {
            $criteria->andWhere(Criteria::expr()->contains('lastName', $search->lastName));
        }

        if ($search->order) {
            $order = $search->order;
            $criteria->orderBy([
                $order->field => $order->direction,
            ]);
        }

        return array_values($collection->matching($criteria)->toArray());
    }
}
