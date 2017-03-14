<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Twig\Business;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Twig\Business\Model\CacheWarmerInterface;
use Spryker\Zed\Twig\Business\TwigBusinessFactory;
use Spryker\Zed\Twig\Business\TwigFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Twig
 * @group Business
 * @group TwigFacadeTest
 */
class TwigFacadeTest extends PHPUnit_Framework_TestCase
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Twig\Business\TwigBusinessFactory
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Twig\Business\Model\CacheWarmerInterface
     */
    protected function getCacheWarmerMock()
    {
        $mockBuilder = $this->getMockBuilder(CacheWarmerInterface::class);

        return $mockBuilder->getMock();
    }

}
