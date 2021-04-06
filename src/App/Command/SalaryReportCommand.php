<?php

declare(strict_types=1);

namespace App\Command;

use App\DTO\Input\SalaryReportOrder;
use App\DTO\Input\SalaryReportSearch;
use App\DTO\Output\EmploymentSalaryReport;
use App\Generator\SalaryReportGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SalaryReportCommand extends Command
{
    protected static $defaultName = 'company:salary:report';

    public function __construct(
        private SalaryReportGenerator $salaryReportGenerator
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Generates salary report')
            ->setHelp(sprintf(
                <<<EOT
                Available fields for <info>orderByField</info> are <info>%s</info>:
                EOT,
                implode(', ', array_keys(get_class_vars(EmploymentSalaryReport::class)))
            ))
            ->addOption('departmentName', mode: InputOption::VALUE_OPTIONAL, description: 'Department name to search')
            ->addOption('firstName', mode: InputOption::VALUE_OPTIONAL, description: 'First name to search')
            ->addOption('lastName', mode: InputOption::VALUE_OPTIONAL, description: 'Department name to search')
            ->addOption('orderByField', mode: InputOption::VALUE_OPTIONAL, description: 'Order by field')
            ->addOption('orderByDirection', mode: InputOption::VALUE_OPTIONAL, description: 'Order by direction', default: 'DESC');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $searchInput = $this->createReportSearchInput($input);

        $reportDate = new \DateTime();

        $table = new Table($output);
        $table->setHeaders([
            'First name',
            'Last name',
            'Department name',
            'Salary base',
            'Supplement amount',
            'Supplement type',
            'Total salary',
        ]);
        $table->setHeaderTitle(sprintf('%s salary report', $reportDate->format('Y-m-d')));
        $table->setRows(array_map(
            fn (EmploymentSalaryReport $report) => $this->normalizeReport($report),
            $this->salaryReportGenerator->generate($reportDate, $searchInput)
        ));

        $table->render();

        return Command::SUCCESS;
    }

    private function createReportSearchInput(InputInterface $input): SalaryReportSearch
    {
        $departmentName = $input->getOption('departmentName');
        $firstName = $input->getOption('firstName');
        $lastName = $input->getOption('lastName');
        $orderByField = $input->getOption('orderByField');
        $orderByDirection = $input->getOption('orderByDirection');

        $search = new SalaryReportSearch();
        $search->firstName = $firstName;
        $search->lastName = $lastName;
        $search->departmentName = $departmentName;

        if ($orderByField && $orderByDirection) {
            $search->order = new SalaryReportOrder($orderByField, $orderByDirection);
        }

        return $search;
    }

    /**
     * @return mixed[]
     */
    private function normalizeReport(EmploymentSalaryReport $report): array
    {
        return [
            $report->firstName,
            $report->lastName,
            $report->departmentName,
            number_format($report->salaryBase, 2),
            number_format($report->salarySupplementAmount, 2),
            $report->salarySupplementType,
            number_format($report->salaryTotal, 2),
        ];
    }
}
