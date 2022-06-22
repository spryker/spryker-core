<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GlueApplication\Cache\Reader;

use Codeception\Configuration;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Spryker\Glue\GlueApplication\Cache\Reader\ControllerCacheReader;
use Spryker\Glue\GlueApplication\Cache\Writer\ControllerCacheWriterInterface;
use Spryker\Glue\GlueApplication\Dependency\External\GlueApplicationToSymfonyFilesystemAdapter;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplication\Resource\MissingResource;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group GlueApplication
 * @group Cache
 * @group Reader
 * @group ControllerCacheReaderTest
 * Add your own group annotations below this line
 */
class ControllerCacheReaderTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueApplication\GlueApplicationTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const FAKE_CONTROLLER_INVALID = 'FAKE_CONTROLLER_INVALID';

    /**
     * @var string
     */
    protected const FAKE_METHOD_INVALID = 'FAKE_METHOD_INVALID';

    /**
     * @var string
     */
    protected const CONTROLLER = '_controller';

    /**
     * @var string
     */
    protected const ROUTE = '_route';

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testGetActionParametersReturnEmptyArrayIfMissingResource(): void
    {
        //Arrange
        $resourceMock = $this->createMock(MissingResource::class);
        $glueRequestTransfer = new GlueRequestTransfer();

        $controllerCacheReader = new ControllerCacheReader(
            $this->createMock(ControllerCacheWriterInterface::class),
            $this->createMock(GlueApplicationConfig::class),
        );

        //Act
        $parameters = $controllerCacheReader->getActionParameters([], $resourceMock, $glueRequestTransfer);

        //Assert
        $this->assertEmpty($parameters);
    }

    /**
     * @return void
     */
    public function testGetActionParametersReturnsNullIfRequestedMethodDoesNotExist(): void
    {
        //Arrange
        $resourceMock = $this->createMock(ResourceInterface::class);
        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setResource((new GlueResourceTransfer())
                ->setParameters([
                    static::CONTROLLER => [
                        static::FAKE_CONTROLLER_INVALID,
                        static::FAKE_METHOD_INVALID,
                    ],
                    static::ROUTE => $this->tester::FAKE_PATH,
                ]))
            ->setApplication($this->tester::FAKE_APPLICATION);
        $executableResource = [
            0 => $this->tester::FAKE_CONTROLLER,
            1 => $this->tester::FAKE_METHOD,
        ];

        //Act
        $controllerCacheReader = new ControllerCacheReader($this->createControllerCacheWriterMock(), $this->createConfigMock());
        $parameters = $controllerCacheReader->getActionParameters($executableResource, $resourceMock, $glueRequestTransfer);

        //Assert
        $this->assertNull($parameters);
    }

    /**
     * @return void
     */
    public function testGetActionParametersReturnsParametersSuccessfully(): void
    {
        //Arrange
        $resourceMock = $this->createMock(ResourceInterface::class);
        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setResource((new GlueResourceTransfer())
                ->setParameters([
                    static::CONTROLLER => [
                        $this->tester::FAKE_CONTROLLER,
                        $this->tester::FAKE_METHOD,
                    ],
                    static::ROUTE => $this->tester::FAKE_PATH,
                ]))
            ->setApplication($this->tester::FAKE_APPLICATION);
        $executableResource = [
            0 => $this->tester::FAKE_CONTROLLER,
            1 => $this->tester::FAKE_METHOD,
        ];

        //Act
        $controllerCacheReader = new ControllerCacheReader($this->createControllerCacheWriterMock(), $this->createConfigMock());
        $parameters = $controllerCacheReader->getActionParameters($executableResource, $resourceMock, $glueRequestTransfer);

        //Assert
        $this->assertNotEmpty($parameters);
        $this->assertArrayHasKey($this->tester::FAKE_PARAMETER_BAR, $parameters);
        $this->assertArrayHasKey($this->tester::FAKE_PARAMETER_FOO, $parameters);
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->tester->removeCacheFile();
        parent::tearDown();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Cache\Writer\ControllerCacheWriterInterface
     */
    protected function createControllerCacheWriterMock(): ControllerCacheWriterInterface
    {
        $controllerCacheWriterMock = $this->createMock(ControllerCacheWriterInterface::class);
        $controllerCacheWriterMock->expects($this->once())
            ->method('cache')
            ->willReturnCallback(
                function () {
                    (new GlueApplicationToSymfonyFilesystemAdapter())
                        ->dumpFile(
                            Configuration::dataDir() . DIRECTORY_SEPARATOR . GlueApplicationConfig::API_CONTROLLER_CACHE_FILENAME,
                            serialize($this->tester->haveApiControllerConfigurationTransfers()),
                        );
                },
            );

        return $controllerCacheWriterMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\GlueApplicationConfig
     */
    protected function createConfigMock(): GlueApplicationConfig
    {
        $configMock = $this->createMock(GlueApplicationConfig::class);
        $configMock->expects($this->once())
            ->method('getControllerCachePath')
            ->willReturn(Configuration::dataDir());

        return $configMock;
    }
}
