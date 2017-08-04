<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedNavigation\Business\Model\Cache;

use Codeception\Test\Unit;
use Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheBuilder;
use Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheInterface;
use Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ZedNavigation
 * @group Business
 * @group Model
 * @group Cache
 * @group ZedNavigationCacheBuilderTest
 * Add your own group annotations below this line
 */
class ZedNavigationCacheBuilderTest extends Unit
{

    /**
     * @return void
     */
    public function testWriteNavigationCacheMustReadNavigationFromCollectorAndPassItToTheCache()
    {
        $navigationCacheMock = $this->getMockBuilder(ZedNavigationCacheInterface::class)->setMethods(['isEnabled', 'setNavigation', 'getNavigation'])->getMock();
        $navigationCacheMock->expects($this->once())
            ->method('setNavigation');

        $navigationCollectorMock = $this->getMockBuilder(ZedNavigationCollectorInterface::class)->setMethods(['getNavigation'])->getMock();
        $navigationCollectorMock->expects($this->once())
            ->method('getNavigation')
            ->will($this->returnValue([]));

        $navigationCacheBuilder = new ZedNavigationCacheBuilder($navigationCollectorMock, $navigationCacheMock);
        $navigationCacheBuilder->writeNavigationCache();
    }

}
