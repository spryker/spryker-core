<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ZedNavigation\Business\Model\Collector\Decorator;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheInterface;
use Spryker\Zed\ZedNavigation\Business\Model\Collector\Decorator\ZedNavigationCollectorCacheDecorator;
use Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group ZedNavigation
 * @group Business
 * @group Model
 * @group Collector
 * @group Decorator
 * @group ZedNavigationCollectorCacheDecoratorTest
 */
class ZedNavigationCollectorCacheDecoratorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testIfCacheIsNotEnabledGetNavigationMustReturnNavigationFromCollector()
    {
        $navigationCacheMock = $this->getMockBuilder(ZedNavigationCacheInterface::class)->setMethods(['isEnabled', 'setNavigation', 'getNavigation'])->getMock();
        $navigationCacheMock->expects($this->once())
            ->method('isEnabled')
            ->will($this->returnValue(false));
        $navigationCacheMock->expects($this->never())
            ->method('getNavigation');

        $navigationCollectorMock = $this->getMockBuilder(ZedNavigationCollectorInterface::class)->setMethods(['getNavigation'])->getMock();
        $navigationCollectorMock->expects($this->once())
            ->method('getNavigation')
            ->will($this->returnValue([]));

        $navigationCollectorCacheDecorator = new ZedNavigationCollectorCacheDecorator($navigationCollectorMock, $navigationCacheMock);

        $this->assertInternalType(
            'array',
            $navigationCollectorCacheDecorator->getNavigation()
        );
    }

    /**
     * @return void
     */
    public function testIfCacheIsEnabledGetNavigationMustReturnNavigationFromCache()
    {
        $navigationCacheMock = $this->getMockBuilder(ZedNavigationCacheInterface::class)->setMethods(['isEnabled', 'setNavigation', 'getNavigation'])->getMock();
        $navigationCacheMock->expects($this->once())
            ->method('isEnabled')
            ->will($this->returnValue(true));
        $navigationCacheMock->expects($this->once())
            ->method('getNavigation')
            ->will($this->returnValue([]));

        $navigationCollectorMock = $this->getMockBuilder(ZedNavigationCollectorInterface::class)->setMethods(['getNavigation'])->getMock();
        $navigationCollectorMock->expects($this->never())
            ->method('getNavigation');

        $navigationCollectorCacheDecorator = new ZedNavigationCollectorCacheDecorator($navigationCollectorMock, $navigationCacheMock);

        $this->assertInternalType(
            'array',
            $navigationCollectorCacheDecorator->getNavigation()
        );
    }

}
