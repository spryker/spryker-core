<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Twig;

use PHPUnit_Framework_TestCase;
use Spryker\Shared\Twig\Cache\CacheInterface;
use Spryker\Shared\Twig\Dependency\Service\TwigToUtilTextServiceInterface;
use Spryker\Shared\Twig\TwigFilesystemLoader;
use Twig_LoaderInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Twig
 * @group TwigFilesystemLoaderTest
 */
class TwigFilesystemLoaderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCanBeConstructedWithTemplatePathsArray()
    {
        $templatePaths = [];
        $filesystemLoader = new TwigFilesystemLoader($templatePaths, $this->getCacheMock(), $this->getUtilTextServiceMock());

        $this->assertInstanceOf(Twig_LoaderInterface::class, $filesystemLoader);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\Twig\Cache\CacheInterface
     */
    protected function getCacheMock()
    {
        $mockBuilder = $this->getMockBuilder(CacheInterface::class);

        return $mockBuilder->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\Twig\Dependency\Service\TwigToUtilTextServiceInterface
     */
    protected function getUtilTextServiceMock()
    {
        $mockBuilder = $this->getMockBuilder(TwigToUtilTextServiceInterface::class);

        return $mockBuilder->getMock();
    }

}
