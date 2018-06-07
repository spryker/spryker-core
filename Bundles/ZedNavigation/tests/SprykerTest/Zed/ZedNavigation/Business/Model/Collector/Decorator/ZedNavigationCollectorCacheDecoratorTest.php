<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedNavigation\Business\Model\Collector\Decorator;

use Codeception\Test\Unit;
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

        $navigationCacheMock = $this->getZedNavigationCacheMock();
        $navigationCacheMock
            ->expects($this->never())
            ->method('getNavigation');

        $navigationCollectorMock = $this->getZedNavigationCollectorMock();
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

        $navigationCacheMock = $this->getZedNavigationCacheMock();
        $navigationCacheMock
            ->expects($this->once())
            ->method('getNavigation')
            ->will($this->returnValue($expectedNavigation));

        $navigationCollectorMock = $this->getZedNavigationCollectorMock();
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
     * @return void
     */
    public function testReturnsCollectedNavigationWhenCacheIsEnabledButCacheDoesNotExists()
    {
        //prepare
        $expectedNavigation = ['key' => 'value'];

        $navigationCacheMock = $this->getZedNavigationCacheMock();
        $navigationCacheMock
            ->expects($this->once())
            ->method('getNavigation')
            ->will($this->returnValue($expectedNavigation));
        $navigationCacheMock
            ->expects($this->once())
            ->method('setNavigation')
            ->with($this->equalTo($expectedNavigation));

        $navigationCollectorMock = $this->getZedNavigationCollectorMock();
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
        return $this
            ->getMockBuilder(ZedNavigationCacheInterface::class)
            ->setMethods(['setNavigation', 'getNavigation'])
            ->getMock();
    }
}
