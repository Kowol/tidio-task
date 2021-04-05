<?php

declare(strict_types=1);

namespace App\Command;

use Company\Model\Employee;
use Company\Model\Employment;
use Company\Model\Money;
use Company\Repository\DepartmentRepositoryInterface;
use Company\Repository\EmployeeRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class EmployeeCommand extends Command
{
    protected static $defaultName = 'company:employee:create';

    public function __construct(
        private EmployeeRepositoryInterface $employeeRepository,
        private DepartmentRepositoryInterface $departmentRepository,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $firstNameQuestion = new Question('Please provide first name: ');
        $firstNameQuestion->setValidator(fn ($answer) => is_string($answer) ? $answer : throw new \RuntimeException('Invalid name'));
        $lastNameQuestion = new Question('Please provide last name: ');
        $lastNameQuestion->setValidator(fn ($answer) => is_string($answer) ? $answer : throw new \RuntimeException('Invalid name'));
        $departmentByName = [];
        $departments = $this->departmentRepository->findAll();
        foreach ($departments as $department) {
            $departmentByName[$department->getName()] = $department;
        }

        $departmentQuestion = new ChoiceQuestion(
            'Please select department',
            array_keys($departmentByName)
        );

        $salaryAmountQuestion = new Question('Please provide salary: ');
        $salaryAmountQuestion->setValidator(fn ($answer) => is_numeric($answer) ? $answer : throw new \RuntimeException('Invalid amount'));
        $salaryAmountQuestion->setNormalizer(fn ($answer) => (int) $answer);

        $firstName = $helper->ask($input, $output, $firstNameQuestion);
        $lastName = $helper->ask($input, $output, $lastNameQuestion);
        $departmentName = $helper->ask($input, $output, $departmentQuestion);
        $department = $departmentByName[$departmentName];
        $salaryAmount = $helper->ask($input, $output, $salaryAmountQuestion);

        $employee = new Employee($firstName, $lastName);
        $employment = new Employment($employee, $department, new Money($salaryAmount), new \DateTime());
        $employee->setEmployment($employment);

        $this->employeeRepository->add($employee);
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
