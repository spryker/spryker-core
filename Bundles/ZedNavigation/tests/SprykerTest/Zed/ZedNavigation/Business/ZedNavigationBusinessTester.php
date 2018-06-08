<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedNavigation\Business;

use Codeception\Test\Unit;
use Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheInterface;
use Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface;
use Spryker\Zed\ZedNavigation\Business\ZedNavigationBusinessFactory;
use Spryker\Zed\ZedNavigation\Business\ZedNavigationFacade;
use Spryker\Zed\ZedNavigation\Business\ZedNavigationFacadeInterface;
use Spryker\Zed\ZedNavigation\ZedNavigationConfig;

class ZedNavigationBusinessTester extends Unit
{
    /**
     * @return \Spryker\Zed\ZedNavigation\Business\ZedNavigationFacadeInterface
     */
    protected function getFacade(): ZedNavigationFacadeInterface
    {
        return new ZedNavigationFacade();
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\Business\ZedNavigationBusinessFactory
     */
    protected function getFactory(): ZedNavigationBusinessFactory
    {
        return new ZedNavigationBusinessFactory();
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getZedNavigationCollectorMock(): ZedNavigationCollectorInterface
    {
        return $this
            ->getMockBuilder(ZedNavigationCollectorInterface::class)
            ->setMethods(['getNavigation'])
            ->getMock();
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getZedNavigationCacheMock(): ZedNavigationCacheInterface
    {
        $cacheMock = $this
            ->getMockBuilder(ZedNavigationCacheInterface::class)
            ->setMethods(['setNavigation', 'getNavigation', 'hasContent', 'isEnabled'])
            ->getMock();

        $cacheMock->expects($this->never())
            ->method('isEnabled');
        $cacheMock->expects($this->never())
            ->method('setNavigation');
        $cacheMock->expects($this->never())
            ->method('hasContent');

        return $cacheMock;
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\ZedNavigationConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getZedNavigationConfigMock(): ZedNavigationConfig
    {
        return $this
            ->getMockBuilder(ZedNavigationConfig::class)
            ->setMethods(['isNavigationCacheEnabled'])
            ->getMock();
    }
}
