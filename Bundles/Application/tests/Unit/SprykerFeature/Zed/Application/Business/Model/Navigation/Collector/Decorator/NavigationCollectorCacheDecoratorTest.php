<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Application\Business\Model\Navigation\Collector\Decorator;

use SprykerFeature\Zed\Application\Business\Model\Navigation\Collector\Decorator\NavigationCollectorCacheDecorator;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Application
 * @group Business
 * @group NavigationCollectorCacheDecorator
 */
class NavigationCollectorCacheDecoratorTest extends \PHPUnit_Framework_TestCase
{

    public function testIfCacheIsNotEnabledGetNavigationMustReturnNavigationFromCollector()
    {
        $navigationCacheMock = $this->getMock(
            'SprykerFeature\Zed\Application\Business\Model\Navigation\Cache\NavigationCacheInterface',
            ['isEnabled', 'setNavigation', 'getNavigation']
        );
        $navigationCacheMock->expects($this->once())
            ->method('isEnabled')
            ->will($this->returnValue(false))
        ;
        $navigationCacheMock->expects($this->never())
            ->method('getNavigation')
        ;

        $navigationCollectorMock = $this->getMock(
            'SprykerFeature\Zed\Application\Business\Model\Navigation\Collector\NavigationCollectorInterface',
            ['getNavigation']
        );
        $navigationCollectorMock->expects($this->once())
            ->method('getNavigation')
            ->will($this->returnValue([]))
        ;

        $navigationCollectorCacheDecorator = new NavigationCollectorCacheDecorator($navigationCollectorMock, $navigationCacheMock);

        $this->assertInternalType(
            'array',
            $navigationCollectorCacheDecorator->getNavigation()
        );
    }

    public function testIfCacheIsEnabledGetNavigationMustReturnNavigationFromCache()
    {
        $navigationCacheMock = $this->getMock(
            'SprykerFeature\Zed\Application\Business\Model\Navigation\Cache\NavigationCacheInterface',
            ['isEnabled', 'setNavigation', 'getNavigation']
        );
        $navigationCacheMock->expects($this->once())
            ->method('isEnabled')
            ->will($this->returnValue(true))
        ;
        $navigationCacheMock->expects($this->once())
            ->method('getNavigation')
            ->will($this->returnValue([]))
        ;

        $navigationCollectorMock = $this->getMock(
            'SprykerFeature\Zed\Application\Business\Model\Navigation\Collector\NavigationCollectorInterface',
            ['getNavigation']
        );
        $navigationCollectorMock->expects($this->never())
            ->method('getNavigation')
        ;

        $navigationCollectorCacheDecorator = new NavigationCollectorCacheDecorator($navigationCollectorMock, $navigationCacheMock);

        $this->assertInternalType(
            'array',
            $navigationCollectorCacheDecorator->getNavigation()
        );
    }

}
