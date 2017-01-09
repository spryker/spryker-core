<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Application\Business\Model\Navigation\Cache;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Application\Business\Model\Navigation\Cache\NavigationCacheBuilder;
use Spryker\Zed\Application\Business\Model\Navigation\Cache\NavigationCacheInterface;
use Spryker\Zed\Application\Business\Model\Navigation\Collector\NavigationCollectorInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Application
 * @group Business
 * @group Model
 * @group Navigation
 * @group Cache
 * @group NavigationCacheBuilderTest
 */
class NavigationCacheBuilderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testWriteNavigationCacheMustReadNavigationFromCollectorAndPassItToTheCache()
    {
        $navigationCacheMock = $this->getMockBuilder(NavigationCacheInterface::class)->setMethods(['isEnabled', 'setNavigation', 'getNavigation'])->getMock();
        $navigationCacheMock->expects($this->once())
            ->method('setNavigation');

        $navigationCollectorMock = $this->getMockBuilder(NavigationCollectorInterface::class)->setMethods(['getNavigation'])->getMock();
        $navigationCollectorMock->expects($this->once())
            ->method('getNavigation')
            ->will($this->returnValue([]));

        $navigationCacheBuilder = new NavigationCacheBuilder($navigationCollectorMock, $navigationCacheMock);
        $navigationCacheBuilder->writeNavigationCache();
    }

}
