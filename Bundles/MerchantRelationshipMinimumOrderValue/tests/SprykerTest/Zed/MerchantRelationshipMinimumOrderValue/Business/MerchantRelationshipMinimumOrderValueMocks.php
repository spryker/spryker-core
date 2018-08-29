<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationshipMinimumOrderValue\Business;

use Codeception\TestCase\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipMinimumOrderValueBusinessFactory;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipMinimumOrderValueFacade;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\MerchantRelationshipMinimumOrderValueConfig;

abstract class MerchantRelationshipMinimumOrderValueMocks extends Test
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $config;

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MerchantRelationshipMinimumOrderValue\MerchantRelationshipMinimumOrderValueConfig
     */
    protected function createMerchantRelationshipMinimumOrderValueConfigMock(): MockObject
    {
        return $this->getMockBuilder(MerchantRelationshipMinimumOrderValueConfig::class)
            ->getMock();
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|null $config
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipMinimumOrderValueBusinessFactory
     */
    protected function createMerchantRelationshipMinimumOrderValueBusinessFactoryMock(?MockObject $config = null): MockObject
    {
        $mockObject = $this->getMockBuilder(MerchantRelationshipMinimumOrderValueBusinessFactory::class)
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipMinimumOrderValueFacadeInterface
     */
    protected function createMerchantRelationshipMinimumOrderValueFacadeMock(?MockObject $factory = null): MockObject
    {
        $mockObject = $this->getMockBuilder(MerchantRelationshipMinimumOrderValueFacade::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();

        if ($factory !== null) {
            $mockObject->setFactory($factory);
        }

        return $mockObject;
    }
}
