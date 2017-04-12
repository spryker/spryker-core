<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Twig\Business\Model\CacheWarmer;

use PHPUnit_Framework_TestCase;
use Spryker\Shared\Twig\Cache\CacheWriterInterface;
use Spryker\Zed\Twig\Business\Model\CacheWarmerInterface;
use Spryker\Zed\Twig\Business\Model\CacheWarmer\CacheWarmer;
use Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilderInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Twig
 * @group Business
 * @group Model
 * @group CacheWarmer
 * @group CacheWarmerTest
 */
class CacheWarmerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCanBeInstantiated()
    {
        $cacheWriterMock = $this->getCacheWriterMock();
        $cacheWarmer = new CacheWarmer($cacheWriterMock, $this->getTemplatePathMapBuilderMock());

        $this->assertInstanceOf(CacheWarmerInterface::class, $cacheWarmer);
    }

    /**
     * @return void
     */
    public function testWarmUpCallsTemplatePathMapBuilderAndCacheWriter()
    {
        $cacheWriterMock = $this->getCacheWriterMock();
        $cacheWriterMock->expects($this->once())->method('write');

        $templatePathBuilderMock = $this->getTemplatePathMapBuilderMock();
        $templatePathBuilderMock->expects($this->once())->method('build')->willReturn([]);
        $cacheWarmer = new CacheWarmer($cacheWriterMock, $templatePathBuilderMock);

        $cacheWarmer->warmUp();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\Twig\Cache\CacheWriterInterface
     */
    protected function getCacheWriterMock()
    {
        $mockBuilder = $this->getMockBuilder(CacheWriterInterface::class)
            ->setMethods(['write']);

        return $mockBuilder->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilderInterface
     */
    protected function getTemplatePathMapBuilderMock()
    {
        $mockBuilder = $this->getMockBuilder(TemplatePathMapBuilderInterface::class)
            ->setMethods(['build']);

        return $mockBuilder->getMock();
    }

}
