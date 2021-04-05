<?php

declare(strict_types=1);

namespace Company\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

/**
 * @ORM\Entity()
 */
class Employee
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     */
    private UuidV4 $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string")
     */
    private string $lastName;

    /**
     * @ORM\OneToOne(targetEntity="Employment", mappedBy="employee", cascade="all")
     */
    private Employment $employment;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private \DateTimeInterface $createdOn;

    public function __construct(string $firstName, string $lastName)
    {
        $this->id = new UuidV4();
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->createdOn = new \DateTime();
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmployment(): Employment
    {
        return $this->employment;
    }

    public function setEmployment(Employment $employment): void
    {
        $this->employment = $employment;
    }
}
