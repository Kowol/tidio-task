<?php

declare(strict_types=1);

namespace Company\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Employment
{
    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Employee", inversedBy="employment")
     */
    private Employee $employee;

    /**
     * @ORM\ManyToOne(targetEntity="Department")
     */
    private Department $department;

    /**
     * @ORM\Embedded(class="Money")
     */
    private Money $salary;

    /**
     * @ORM\Column(type="date")
     */
    private \DateTimeInterface $employmentDate;

    public function __construct(
        Employee $employee,
        Department $department,
        Money $salary,
        \DateTimeInterface $employmentDate
    ) {
        $this->employee = $employee;
        $this->department = $department;
        $this->salary = $salary;
        $this->employmentDate = $employmentDate;
    }

    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    public function getDepartment(): Department
    {
        return $this->department;
    }

    public function getSalary(): Money
    {
        return $this->salary;
    }

    public function getEmploymentDate(): \DateTimeInterface
    {
        return $this->employmentDate;
    }
}
