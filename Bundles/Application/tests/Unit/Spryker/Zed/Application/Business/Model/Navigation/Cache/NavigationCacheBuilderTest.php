<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Application\Business\Model\Navigation\Cache;

use Spryker\Zed\Application\Business\Model\Navigation\Cache\NavigationCacheBuilder;

/**
 * @group Spryker
 * @group Zed
 * @group Application
 * @group Business
 * @group NavigationCache
 */
class NavigationCacheBuilderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testWriteNavigationCacheMustReadNavigationFromCollectorAndPassItToTheCache()
    {
        $navigationCacheMock = $this->getMock(
            'Spryker\Zed\Application\Business\Model\Navigation\Cache\NavigationCacheInterface',
            ['isEnabled', 'setNavigation', 'getNavigation']
        );
        $navigationCacheMock->expects($this->once())
            ->method('setNavigation');

        $navigationCollectorMock = $this->getMock(
            'Spryker\Zed\Application\Business\Model\Navigation\Collector\NavigationCollectorInterface',
            ['getNavigation']
        );
        $navigationCollectorMock->expects($this->once())
            ->method('getNavigation')
            ->will($this->returnValue([]));

        $navigationCacheBuilder = new NavigationCacheBuilder($navigationCollectorMock, $navigationCacheMock);
        $navigationCacheBuilder->writeNavigationCache();
    }

}
