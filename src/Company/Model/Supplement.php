<?php

declare(strict_types=1);

namespace Company\Model;

use Company\Enum\DepartmentSalarySupplementType;
use Company\Exception\InvalidSalarySupplementAmountException;
use Company\Exception\InvalidSalarySupplementTypeException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Supplement
{
    /**
     * @ORM\Column(type="smallint")
     */
    private int $type;

    /**
     * @ORM\Embedded(class="Money")
     */
    private ?Money $fixedValue;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $percents;

    private function __construct(int $type, Money $fixedValue = null, int $percents = null)
    {
        if (! in_array(
            $type,
            [DepartmentSalarySupplementType::PERCENTAGE, DepartmentSalarySupplementType::FIXED],
            true)
        ) {
            throw new InvalidSalarySupplementTypeException('Incorrect salary supplement type provided');
        }

        $this->type = $type;
        $this->fixedValue = $fixedValue;
        $this->percents = $percents;
    }

    public static function fixed(Money $fixedValue): self
    {
        if ($fixedValue->getAmountInCents() < 0) {
            throw new InvalidSalarySupplementAmountException('Invalid salary supplement amount provided');
        }

        return new self(DepartmentSalarySupplementType::FIXED, fixedValue: $fixedValue);
    }

    public static function percentage(int $percents): self
    {
        if ($percents < 0 || $percents > 100) {
            throw new InvalidSalarySupplementAmountException('Invalid salary supplement percents provided');
        }

        return new self(DepartmentSalarySupplementType::PERCENTAGE, percents: $percents);
    }

    public function getFixedValue(): ?Money
    {
        return $this->fixedValue;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getPercents(): ?int
    {
        return $this->percents;
    }
}
