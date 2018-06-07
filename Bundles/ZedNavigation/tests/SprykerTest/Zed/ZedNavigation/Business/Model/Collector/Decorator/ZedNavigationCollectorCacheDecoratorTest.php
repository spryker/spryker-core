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
use Spryker\Zed\ZedNavigation\ZedNavigationConfig;

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
    public function testIfCacheIsNotEnabledGetNavigationMustReturnNavigationFromCollector(): void
    {
        //prepare
        $expectedNavigation = [['key' => 'value']];
        $navigationCacheMock = $this->getZedNavigationCacheMock();
        $navigationCollectorMock = $this->getZedNavigationCollectorMock();
        $configMock = $this->getZedNavigationConfigMock();
        $navigationCollectorCacheDecorator = new ZedNavigationCollectorCacheDecorator(
            $navigationCollectorMock,
            $navigationCacheMock,
            $configMock
        );

        //assert
        $navigationCacheMock
            ->expects($this->never())
            ->method('getNavigation');
        $navigationCollectorMock
            ->expects($this->once())
            ->method('getNavigation')
            ->will($this->returnValue($expectedNavigation));
        $configMock
            ->expects($this->once())
            ->method('isNavigationCacheEnabled')
            ->will($this->returnValue(false));

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
    public function testIfCacheIsEnabledGetNavigationMustReturnNavigationFromCache(): void
    {
        //prepare
        $expectedNavigation = [['key' => 'value']];
        $navigationCacheMock = $this->getZedNavigationCacheMock();
        $navigationCollectorMock = $this->getZedNavigationCollectorMock();
        $configMock = $this->getZedNavigationConfigMock();
        $navigationCollectorCacheDecorator = new ZedNavigationCollectorCacheDecorator(
            $navigationCollectorMock,
            $navigationCacheMock,
            $configMock
        );

        //assert
        $navigationCacheMock
            ->expects($this->once())
            ->method('getNavigation')
            ->will($this->returnValue($expectedNavigation));
        $navigationCollectorMock
            ->expects($this->never())
            ->method('getNavigation');
        $configMock
            ->expects($this->once())
            ->method('isNavigationCacheEnabled')
            ->will($this->returnValue(true));


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
    public function testReturnsCollectedNavigationWhenCacheIsEnabledButCacheDoesNotExists(): void
    {
        //prepare
        $expectedNavigation = [['key' => 'value']];
        $navigationCacheMock = $this->getZedNavigationCacheMock();
        $navigationCollectorMock = $this->getZedNavigationCollectorMock();
        $configMock = $this->getZedNavigationConfigMock();
        $navigationCollectorCacheDecorator = new ZedNavigationCollectorCacheDecorator(
            $navigationCollectorMock,
            $navigationCacheMock,
            $configMock
        );

        //assert
        $navigationCacheMock
            ->expects($this->once())
            ->method('getNavigation')
            ->will($this->returnValue($expectedNavigation));
        $navigationCacheMock
            ->expects($this->once())
            ->method('hasContent')
            ->with($this->returnValue(false));
        $navigationCacheMock
            ->expects($this->once())
            ->method('setNavigation')
            ->with($this->equalTo($expectedNavigation));
        $navigationCollectorMock
            ->expects($this->never())
            ->method('getNavigation');

        //act
        $navigation = $navigationCollectorCacheDecorator->getNavigation();

        //assert
        $this->assertSame(
            $expectedNavigation,
            $navigation
        );
    }
}
