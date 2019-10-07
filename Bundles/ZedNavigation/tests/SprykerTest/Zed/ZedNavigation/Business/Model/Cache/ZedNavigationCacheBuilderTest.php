<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedNavigation\Business\Model\Cache;

use Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheBuilder;
use SprykerTest\Zed\ZedNavigation\Business\ZedNavigationBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ZedNavigation
 * @group Business
 * @group Model
 * @group Cache
 * @group ZedNavigationCacheBuilderTest
 * Add your own group annotations below this line
 */
class ZedNavigationCacheBuilderTest extends ZedNavigationBusinessTester
{
    /**
     * @return void
     */
    public function testWriteNavigationCacheMustReadNavigationFromCollectorAndPassItToTheCache()
    {
        //prepare
        $navigationCacheMock = $this->getZedNavigationCacheMock();
        $navigationCollectorMock = $this->getZedNavigationCollectorMock();
        $navigationCacheBuilder = new ZedNavigationCacheBuilder($navigationCollectorMock, $navigationCacheMock);
        $expectedNavigation = [['key' => 'value']];

        //assert
        $navigationCacheMock->expects($this->once())
            ->method('setNavigation')
            ->with($this->equalTo($expectedNavigation));
        $navigationCollectorMock->expects($this->once())
            ->method('getNavigation')
            ->will($this->returnValue($expectedNavigation));

        //act
        $navigationCacheBuilder->writeNavigationCache();
    }
}
