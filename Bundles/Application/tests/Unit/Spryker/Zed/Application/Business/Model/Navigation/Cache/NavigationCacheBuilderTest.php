<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Application\Business\Model\Navigation\Cache;

use Spryker\Zed\Application\Business\Model\Navigation\Cache\NavigationCacheBuilder;

/**
 * @group Spryker
 * @group Zed
 * @group Application
 * @group Business
 * @group NavigationCacheBuilder
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
