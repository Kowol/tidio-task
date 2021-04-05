<?php

declare(strict_types=1);

namespace Company\Model;

use Company\Exception\InvalidMoneyAmountException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Money
{
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private int $amountInCents;

    public function __construct(int $amountInCents)
    {
        if ($amountInCents < 0) {
            throw new InvalidMoneyAmountException('Invalid money amount provided');
        }

        $this->amountInCents = $amountInCents;
    }

    public function getAmountInCents(): int
    {
        return $this->amountInCents;
    }

    public function getAmount(): float
    {
        return round($this->amountInCents / 100, 2);
    }
}
