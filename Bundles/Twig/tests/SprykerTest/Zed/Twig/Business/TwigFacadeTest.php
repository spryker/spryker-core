<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Twig\Business;

use Codeception\Test\Unit;
use Spryker\Zed\Twig\Business\Model\CacheWarmerInterface;
use Spryker\Zed\Twig\Business\TwigBusinessFactory;
use Spryker\Zed\Twig\Business\TwigFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Twig
 * @group Business
 * @group Facade
 * @group TwigFacadeTest
 * Add your own group annotations below this line
 */
class TwigFacadeTest extends Unit
{
    /**
     * @return void
     */
    public function testWarmUpCacheDelegatesToCacheWarmerModel()
    {
        $factoryMock = $this->getFactoryMock();
        $twigFacade = new TwigFacade();
        $twigFacade->setFactory($factoryMock);

        $twigFacade->warmUpCache();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Twig\Business\TwigBusinessFactory
     */
    protected function getFactoryMock()
    {
        $mockBuilder = $this->getMockBuilder(TwigBusinessFactory::class)
            ->setMethods(['createCacheWarmer']);

        $mock = $mockBuilder->getMock();
        $mock->expects($this->once())->method('createCacheWarmer')->willReturn($this->getCacheWarmerMock());

        return $mock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Twig\Business\Model\CacheWarmerInterface
     */
    protected function getCacheWarmerMock()
    {
        $mockBuilder = $this->getMockBuilder(CacheWarmerInterface::class);

        return $mockBuilder->getMock();
    }
}
