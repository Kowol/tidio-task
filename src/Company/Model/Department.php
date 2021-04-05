<?php

declare(strict_types=1);

namespace Company\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

/**
 * @ORM\Entity()
 */
class Department
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     */
    private UuidV4 $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\Embedded(class="Supplement")
     */
    private Supplement $salarySupplement;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private \DateTimeInterface $createdOn;

    public function __construct(string $name, Supplement $supplement)
    {
        $this->id = new UuidV4();
        $this->name = $name;
        $this->salarySupplement = $supplement;
        $this->createdOn = new \DateTime();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSalarySupplement(): Supplement
    {
        return $this->salarySupplement;
    }
}
