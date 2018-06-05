<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedNavigation\Business\Model\Collector\Decorator;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheInterface;
use Spryker\Zed\ZedNavigation\Business\Model\Collector\Decorator\ZedNavigationCollectorCacheDecorator;
use Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ZedNavigation
 * @group Business
 * @group Model
 * @group Collector
 * @group Decorator
 * @group ZedNavigationCollectorCacheDecoratorTest
 * Add your own group annotations below this line
 */
class ZedNavigationCollectorCacheDecoratorTest extends Unit
{
    /**
     * @return void
     */
    public function testIfCacheIsNotEnabledGetNavigationMustReturnNavigationFromCollector()
    {
        //prepare
        $expectedNavigation = ['key' => 'value'];

        $navigationCacheMock = $this->haveZedNavigationCache();
        $navigationCacheMock
            ->expects($this->never())
            ->method('getNavigation');

        $navigationCollectorMock = $this->haveZedNavigationCollector();
        $navigationCollectorMock
            ->expects($this->once())
            ->method('getNavigation')
            ->will($this->returnValue($expectedNavigation));

        $navigationCollectorCacheDecorator = new ZedNavigationCollectorCacheDecorator(
            $navigationCollectorMock,
            $navigationCacheMock,
            false
        );

        //act
        $navigation = $navigationCollectorCacheDecorator->getNavigation();

        //assert
        $this->assertSame(
            $expectedNavigation,
            $navigation
        );
    }

    /**
     * @return void
     */
    public function testIfCacheIsEnabledGetNavigationMustReturnNavigationFromCache()
    {
        //prepare
        $expectedNavigation = ['key' => 'value'];

        $navigationCacheMock = $this->haveZedNavigationCache();
        $navigationCacheMock
            ->expects($this->once())
            ->method('getNavigation')
            ->will($this->returnValue($expectedNavigation));

        $navigationCollectorMock = $this->haveZedNavigationCollector();
        $navigationCollectorMock
            ->expects($this->never())
            ->method('getNavigation');

        $navigationCollectorCacheDecorator = new ZedNavigationCollectorCacheDecorator(
            $navigationCollectorMock,
            $navigationCacheMock,
            true
        );

        //act
        $navigation = $navigationCollectorCacheDecorator->getNavigation();

        //assert
        $this->assertSame(
            $expectedNavigation,
            $navigation
        );
    }

    /**
     * @return ZedNavigationCollectorInterface|MockObject
     */
    protected function haveZedNavigationCollector(): ZedNavigationCollectorInterface
    {
        return $this
            ->getMockBuilder(ZedNavigationCollectorInterface::class)
            ->setMethods(['getNavigation'])
            ->getMock();
    }

    /**
     * @return ZedNavigationCacheInterface|MockObject
     */
    protected function haveZedNavigationCache(): ZedNavigationCacheInterface
    {
        return $this
            ->getMockBuilder(ZedNavigationCacheInterface::class)
            ->setMethods(['setNavigation', 'getNavigation'])
            ->getMock();
    }
}
