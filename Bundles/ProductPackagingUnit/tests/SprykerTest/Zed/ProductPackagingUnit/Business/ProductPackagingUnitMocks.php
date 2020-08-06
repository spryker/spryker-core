<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\TestCase\Test;
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
    protected function createProductPackagingUnitConfigMock(): ProductPackagingUnitConfig
    {
        return $this->getMockBuilder(ProductPackagingUnitConfig::class)
            ->getMock();
    }

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig|\PHPUnit\Framework\MockObject\MockObject|null $config
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitBusinessFactory
     */
    protected function createProductPackagingUnitBusinessFactoryMock(?ProductPackagingUnitConfig $config = null): ProductPackagingUnitBusinessFactory
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitBusinessFactory $mockObject */
        $mockObject = $this->getMockBuilder(ProductPackagingUnitBusinessFactory::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();

        if ($config !== null) {
            $mockObject->setConfig($config);
        }

        return $mockObject;
    }

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitBusinessFactory|\PHPUnit\Framework\MockObject\MockObject|null $factory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacade
     */
    protected function createProductPackagingUnitFacadeMock(?ProductPackagingUnitBusinessFactory $factory = null): ProductPackagingUnitFacade
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacade $mockObject */
        $mockObject = $this->getMockBuilder(ProductPackagingUnitFacade::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();

        if ($factory !== null) {
            $mockObject->setFactory($factory);
        }

        return $mockObject;
    }
}
