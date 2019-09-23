<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Twig\Business\Model\CacheWarmer;

use Codeception\Test\Unit;
use Spryker\Zed\Twig\Business\Model\CacheWarmer\CacheWarmerComposite;
use Spryker\Zed\Twig\Business\Model\CacheWarmerInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Twig
 * @group Business
 * @group Model
 * @group CacheWarmer
 * @group CacheWarmerCompositeTest
 * Add your own group annotations below this line
 */
class CacheWarmerCompositeTest extends Unit
{
    /**
     * @return void
     */
    public function testCanBeInstantiated()
    {
        $cacheWarmerMock1 = $this->getCacheWarmerMock();
        $cacheWarmerMock2 = $this->getCacheWarmerMock();

        $cacheWarmerComposite = new CacheWarmerComposite([
            $cacheWarmerMock1,
            $cacheWarmerMock2,
        ]);

        $this->assertInstanceOf(CacheWarmerInterface::class, $cacheWarmerComposite);
    }

    /**
     * @return void
     */
    public function testWarmUpCallsAllAppliedCacheWarmer()
    {
        $cacheWarmerMock1 = $this->getCacheWarmerMock();
        $cacheWarmerMock1->expects($this->once())->method('warmUp');
        $cacheWarmerMock2 = $this->getCacheWarmerMock();
        $cacheWarmerMock2->expects($this->once())->method('warmUp');

        $cacheWarmerComposite = new CacheWarmerComposite([
            $cacheWarmerMock1,
            $cacheWarmerMock2,
        ]);

        $cacheWarmerComposite->warmUp();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Twig\Business\Model\CacheWarmerInterface
     */
    private function getCacheWarmerMock()
    {
        $mockBuilder = $this->getMockBuilder(CacheWarmerInterface::class)
            ->setMethods(['warmUp']);

        return $mockBuilder->getMock();
    }
}
