<?php

declare(strict_types=1);

namespace App\Command;

use Company\Enum\DepartmentSalarySupplementType;
use Company\Model\Department;
use Company\Model\Money;
use Company\Model\Supplement;
use Company\Repository\DepartmentRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class DepartmentCommand extends Command
{
    private const ANSWER_TO_SUPPLEMENT_TYPE = [
        'percentage' => DepartmentSalarySupplementType::PERCENTAGE,
        'fixed' => DepartmentSalarySupplementType::FIXED,
    ];

    protected static $defaultName = 'company:department:create';

    public function __construct(
        private DepartmentRepositoryInterface $departmentRepository,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $departmentNameQuestion = new Question('Please provide department name: ');
        $departmentNameQuestion->setValidator(fn ($answer) => is_string($answer) ? $answer : throw new \RuntimeException('Invalid name'));
        $salarySupplementTypeQuestion = new ChoiceQuestion(
            'Please provide salary supplement type',
            array_keys(self::ANSWER_TO_SUPPLEMENT_TYPE)
        );

        $salarySupplementAmountQuestion = new Question('Please provide salary supplement amount (in cents or percents): ');
        $salarySupplementAmountQuestion->setValidator(fn ($answer) => is_numeric($answer) ? $answer : throw new \RuntimeException('Invalid amount'));
        $salarySupplementAmountQuestion->setNormalizer(fn ($answer) => (int) $answer);

        $departmentName = $helper->ask($input, $output, $departmentNameQuestion);
        $salarySupplementType = $helper->ask($input, $output, $salarySupplementTypeQuestion);
        $salarySupplementAmount = $helper->ask($input, $output, $salarySupplementAmountQuestion);

        $salarySupplement = match (self::ANSWER_TO_SUPPLEMENT_TYPE[$salarySupplementType]) {
            DepartmentSalarySupplementType::PERCENTAGE => Supplement::percentage($salarySupplementAmount),
            DepartmentSalarySupplementType::FIXED => Supplement::fixed(new Money($salarySupplementAmount)),
        };

        $department = new Department(
            $departmentName,
            $salarySupplement
        );

        $this->departmentRepository->add($department);
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
