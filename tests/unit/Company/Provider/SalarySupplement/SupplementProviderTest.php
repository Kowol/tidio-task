<?php

declare(strict_types=1);

namespace Tests\Unit\Company\Provider\SalarySupplement;

use Company\Model\Employment;
use Company\Provider\SalarySupplement\Strategy\FixedProvider;
use Company\Provider\SalarySupplement\Strategy\PercentageProvider;
use Company\Provider\SalarySupplement\SupplementProvider;
use PHPUnit\Framework\TestCase;
use Tests\_support\Mother\DepartmentMother;

class SupplementProviderTest extends TestCase
{
    public function testItUsesPercentageProviderIfSupplementTypeIsPercentage(): void
    {
        $department = DepartmentMother::basicPercentageSupplement(100);
        $employment = $this->createMock(Employment::class);
        $employment->method('getDepartment')->willReturn($department);
        $onDate = new \DateTime();

        $percentageProviderMock = $this->createMock(PercentageProvider::class);
        $fixedProviderMock = $this->createMock(FixedProvider::class);

        $percentageProviderMock->expects($this->once())->method('provide');

        $provider = new SupplementProvider($percentageProviderMock, $fixedProviderMock);
        $provider->provide($employment, $onDate);
    }

    public function testItUsesFixedProviderIfSupplementTypeIsFixed(): void
    {
        $department = DepartmentMother::basicFixedSupplement(100);
        $employment = $this->createMock(Employment::class);
        $employment->method('getDepartment')->willReturn($department);
        $onDate = new \DateTime();

        $percentageProviderMock = $this->createMock(PercentageProvider::class);
        $fixedProviderMock = $this->createMock(FixedProvider::class);

        $fixedProviderMock->expects($this->once())->method('provide');

        $provider = new SupplementProvider($percentageProviderMock, $fixedProviderMock);
        $provider->provide($employment, $onDate);
    }
}
