<?php

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\TestCase\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitBusinessFactory;
use Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacade;
use Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig;

abstract class ProductPackagingUnitMocks extends Test
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $config;

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig
     */
    protected function createProductPackagingUnitConfigMock(): MockObject
    {
        return $this->getMockBuilder(ProductPackagingUnitConfig::class)
            ->getMock();
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|null $config
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitBusinessFactory
     */
    protected function createProductPackagingUnitBusinessFactoryMock(?MockObject $config = null): MockObject
    {
        $mockObject = $this->getMockBuilder(ProductPackagingUnitBusinessFactory::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();

        if ($config !== null) {
            $mockObject->setConfig($config);
        }

        return $mockObject;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|null $factory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacadeInterface
     */
    protected function createProductPackagingUnitFacadeMock(?MockObject $factory = null): MockObject
    {
        $mockObject = $this->getMockBuilder(ProductPackagingUnitFacade::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();

        if ($factory !== null) {
            $mockObject->setFactory($factory);
        }

        return $mockObject;
    }
}
