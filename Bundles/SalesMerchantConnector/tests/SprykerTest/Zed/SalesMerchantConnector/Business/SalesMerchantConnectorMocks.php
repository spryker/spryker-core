<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesMerchantConnector\Business;

use Codeception\TestCase\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\SalesMerchantConnector\Business\SalesMerchantConnectorBusinessFactory;
use Spryker\Zed\SalesMerchantConnector\Business\SalesMerchantConnectorFacade;
use Spryker\Zed\SalesMerchantConnector\SalesMerchantConnectorConfig;

abstract class SalesMerchantConnectorMocks extends Test
{
    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesMerchantConnector\SalesMerchantConnectorConfig
     */
    protected function createSalesMerchantConnectorConfigMock(): MockObject
    {
        $mockObject = $this->getMockBuilder(SalesMerchantConnectorConfig::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();

        return $mockObject;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|null $config
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createSalesMerchantConnectorBusinessFactoryMock(?MockObject $config = null): MockObject
    {
        $mockObject = $this->getMockBuilder(SalesMerchantConnectorBusinessFactory::class)
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesMerchantConnector\Business\SalesMerchantConnectorFacadeInterface
     */
    protected function createSalesMerchantConnectorFacadeMock(?MockObject $factory = null): MockObject
    {
        $mockObject = $this->getMockBuilder(SalesMerchantConnectorFacade::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();

        if ($factory !== null) {
            $mockObject->setFactory($factory);
        }

        return $mockObject;
    }
}
