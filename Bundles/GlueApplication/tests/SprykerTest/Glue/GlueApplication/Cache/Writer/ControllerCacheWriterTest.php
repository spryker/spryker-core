<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GlueApplication\Cache\Writer;

use Codeception\Configuration;
use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\Cache\Writer\ControllerCacheWriter;
use Spryker\Glue\GlueApplication\Dependency\External\GlueApplicationToSymfonyFilesystemAdapter;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerCacheCollectorPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerConfigurationCacheCollectorPluginInterface;
use Symfony\Component\Finder\Finder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group GlueApplication
 * @group Cache
 * @group Writer
 * @group ControllerCacheWriterTest
 * Add your own group annotations below this line
 */
class ControllerCacheWriterTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueApplication\GlueApplicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @return void
     */
    public function testCacheShouldCreateFile(): void
    {
        //Arrange
        $configMock = $this->createMock(GlueApplicationConfig::class);
        $configMock->expects($this->once())
            ->method('getControllerCachePath')
            ->willReturn(Configuration::dataDir());

        $controllerCacheCollectorPluginMock = $this->createMock(ControllerCacheCollectorPluginInterface::class);
        $controllerCacheCollectorPluginMock->expects($this->once())
            ->method('getControllerConfiguration')
            ->willReturn(
                $this->tester->haveApiControllerConfigurationTransfers(),
            );

        //Act
        (new ControllerCacheWriter([$controllerCacheCollectorPluginMock], $configMock, new GlueApplicationToSymfonyFilesystemAdapter(), []))->cache();

        //Assert
        $finder = new Finder();
        $finder->in(Configuration::dataDir())->name(GlueApplicationConfig::API_CONTROLLER_CACHE_FILENAME);
        $this->assertCount(1, $finder);
    }

    /**
     * @return void
     */
    public function testCacheShouldCreateOneFile(): void
    {
        //Arrange
        $configMock = $this->createMock(GlueApplicationConfig::class);
        $configMock->expects($this->once())
            ->method('getControllerCachePath')
            ->willReturn(Configuration::dataDir());

        $controllerConfigurationCacheCollectorPluginMock = $this->createMock(ControllerConfigurationCacheCollectorPluginInterface::class);
        $controllerConfigurationCacheCollectorPluginMock->expects($this->once())
            ->method('getControllerConfiguration')
            ->willReturn(
                $this->tester->haveApiControllerConfigurationTransfers(),
            );

        //Act
        (new ControllerCacheWriter([], $configMock, new GlueApplicationToSymfonyFilesystemAdapter(), [$controllerConfigurationCacheCollectorPluginMock]))->cache();

        //Assert
        $finder = new Finder();
        $finder->in(Configuration::dataDir())->name(GlueApplicationConfig::API_CONTROLLER_CACHE_FILENAME);
        $this->assertCount(1, $finder);
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->tester->removeCacheFile();
        parent::tearDown();
    }
}
