<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderThreshold\Business;

use Codeception\TestCase\Test;
use Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdBusinessFactory;
use Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdFacade;
use Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdFacadeInterface;

abstract class SalesOrderThresholdMocks extends Test
{
    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdBusinessFactory
     */
    protected function createSalesOrderThresholdBusinessFactoryMock(): SalesOrderThresholdBusinessFactory
    {
        $mockObject = $this->getMockBuilder(SalesOrderThresholdBusinessFactory::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();

        return $mockObject;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdBusinessFactory|null $factory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdFacadeInterface
     */
    protected function createSalesOrderThresholdFacadeMock(?SalesOrderThresholdBusinessFactory $factory = null): SalesOrderThresholdFacadeInterface
    {
        /** @var \Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdFacade|\PHPUnit\Framework\MockObject\MockObject $mockObject */
        $mockObject = $this->getMockBuilder(SalesOrderThresholdFacade::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();

        if ($factory !== null) {
            $mockObject->setFactory($factory);
        }

        return $mockObject;
    }
}
