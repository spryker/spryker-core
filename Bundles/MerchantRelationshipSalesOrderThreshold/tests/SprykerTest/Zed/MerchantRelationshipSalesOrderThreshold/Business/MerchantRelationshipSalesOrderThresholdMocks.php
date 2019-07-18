<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationshipSalesOrderThreshold\Business;

use Codeception\TestCase\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipSalesOrderThresholdBusinessFactory;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipSalesOrderThresholdFacade;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\MerchantRelationshipSalesOrderThresholdConfig;

abstract class MerchantRelationshipSalesOrderThresholdMocks extends Test
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $config;

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MerchantRelationshipSalesOrderThreshold\MerchantRelationshipSalesOrderThresholdConfig
     */
    protected function createMerchantRelationshipSalesOrderThresholdConfigMock(): MockObject
    {
        return $this->getMockBuilder(MerchantRelationshipSalesOrderThresholdConfig::class)
            ->getMock();
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|null $config
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipSalesOrderThresholdBusinessFactory
     */
    protected function createMerchantRelationshipSalesOrderThresholdBusinessFactoryMock(?MockObject $config = null): MockObject
    {
        $mockObject = $this->getMockBuilder(MerchantRelationshipSalesOrderThresholdBusinessFactory::class)
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipSalesOrderThresholdFacadeInterface
     */
    protected function createMerchantRelationshipSalesOrderThresholdFacadeMock(?MockObject $factory = null): MockObject
    {
        $mockObject = $this->getMockBuilder(MerchantRelationshipSalesOrderThresholdFacade::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();

        if ($factory !== null) {
            $mockObject->setFactory($factory);
        }

        return $mockObject;
    }
}
