<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MinimumOrderValue\Business;

use Codeception\TestCase\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValueBusinessFactory;
use Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValueFacade;

abstract class MinimumOrderValueMocks extends Test
{
    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValueBusinessFactory
     */
    protected function createMinimumOrderValueBusinessFactoryMock(): MockObject
    {
        $mockObject = $this->getMockBuilder(MinimumOrderValueBusinessFactory::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();

        return $mockObject;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|null $factory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValueFacadeInterface
     */
    protected function createMinimumOrderValueFacadeMock(?MockObject $factory = null): MockObject
    {
        $mockObject = $this->getMockBuilder(MinimumOrderValueFacade::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();

        if ($factory !== null) {
            $mockObject->setFactory($factory);
        }

        return $mockObject;
    }
}
