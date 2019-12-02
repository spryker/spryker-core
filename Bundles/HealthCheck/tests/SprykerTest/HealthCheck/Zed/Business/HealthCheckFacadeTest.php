<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\HealthCheck\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Generated\Shared\Transfer\HealthCheckResponseTransfer;
use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Shared\HealthCheck\HealthCheckConstants;
use Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface;
use Spryker\Zed\HealthCheck\Business\HealthCheckFacade;
use Spryker\Zed\HealthCheck\HealthCheckConfig;
use Spryker\Zed\HealthCheck\HealthCheckDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group HealthCheck
 * @group Business
 * @group Facade
 * @group HealthCheckFacadeTest
 * Add your own group annotations below this line
 */
class HealthCheckFacadeTest extends Unit
{
    protected const DATABASE_HEALTH_CHECK_PLUGIN_NAME = 'database';
    protected const SEARCH_HEALTH_CHECK_PLUGIN_NAME = 'search';
    protected const STORAGE_HEALTH_CHECK_PLUGIN_NAME = 'storage';

    /**
     * @var \SprykerTest\Zed\HealthCheck\HealthCheckBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\HealthCheck\Business\HealthCheckFacadeInterface
     */
    protected $healthCheckFacade;

    /**
     * @var \Spryker\Zed\HealthCheck\HealthCheckConfig
     */
    protected $healthCheckConfig;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->healthCheckFacade = new HealthCheckFacade();
        $this->healthCheckConfig = new HealthCheckConfig();

        $this->tester->setConfig(HealthCheckConstants::HEALTH_CHECK_ENABLED, true);
    }

    /**
     * @return void
     */
    public function testProcessedHealthCheckServicesWithEmptyRequestAndSuccessResponse(): void
    {
        $healthCheckPlugins = [
            $this->getHealthCheckPluginMock(static::DATABASE_HEALTH_CHECK_PLUGIN_NAME, true),
            $this->getHealthCheckPluginMock(static::SEARCH_HEALTH_CHECK_PLUGIN_NAME, true),
            $this->getHealthCheckPluginMock(static::STORAGE_HEALTH_CHECK_PLUGIN_NAME, true),
        ];

        $this->tester->setDependency(HealthCheckDependencyProvider::PLUGINS_HEALTH_CHECK, $healthCheckPlugins);

        $healthCheckRequestTransfer = new HealthCheckRequestTransfer();
        $healthCheckResponseTransfer = $this->healthCheckFacade->executeHealthCheck($healthCheckRequestTransfer);

        $this->assertInstanceOf(HealthCheckResponseTransfer::class, $healthCheckResponseTransfer);
        $this->assertEquals(count($healthCheckPlugins), $healthCheckResponseTransfer->getHealthCheckServiceResponses()->count());
        $this->assertSame($this->healthCheckConfig->getSuccessHealthCheckStatusMessage(), $healthCheckResponseTransfer->getStatus());
        $this->assertSame($this->healthCheckConfig->getSuccessHealthCheckStatusCode(), $healthCheckResponseTransfer->getStatusCode());
    }

    /**
     * @return void
     */
    public function testProcessedHealthCheckServicesWithEmptyRequestAndFailedResponse(): void
    {
        $healthCheckPlugins = [
            $this->getHealthCheckPluginMock(static::DATABASE_HEALTH_CHECK_PLUGIN_NAME, false),
            $this->getHealthCheckPluginMock(static::SEARCH_HEALTH_CHECK_PLUGIN_NAME, false),
            $this->getHealthCheckPluginMock(static::STORAGE_HEALTH_CHECK_PLUGIN_NAME, false),
        ];

        $this->tester->setDependency(HealthCheckDependencyProvider::PLUGINS_HEALTH_CHECK, $healthCheckPlugins);

        $healthCheckRequestTransfer = new HealthCheckRequestTransfer();
        $healthCheckResponseTransfer = $this->healthCheckFacade->executeHealthCheck($healthCheckRequestTransfer);

        $this->assertInstanceOf(HealthCheckResponseTransfer::class, $healthCheckResponseTransfer);
        $this->assertEquals(count($healthCheckPlugins), $healthCheckResponseTransfer->getHealthCheckServiceResponses()->count());
        $this->assertSame($this->healthCheckConfig->getUnavailableHealthCheckStatusMessage(), $healthCheckResponseTransfer->getStatus());
        $this->assertSame($this->healthCheckConfig->getUnavailableHealthCheckStatusCode(), $healthCheckResponseTransfer->getStatusCode());
    }

    /**
     * @return void
     */
    public function testProcessedHealthCheckServicesWithEmptyRequestAndAnyFailedResponse(): void
    {
        $healthCheckPlugins = [
            $this->getHealthCheckPluginMock(static::DATABASE_HEALTH_CHECK_PLUGIN_NAME, true),
            $this->getHealthCheckPluginMock(static::SEARCH_HEALTH_CHECK_PLUGIN_NAME, false),
            $this->getHealthCheckPluginMock(static::STORAGE_HEALTH_CHECK_PLUGIN_NAME, true),
        ];

        $this->tester->setDependency(HealthCheckDependencyProvider::PLUGINS_HEALTH_CHECK, $healthCheckPlugins);

        $healthCheckRequestTransfer = new HealthCheckRequestTransfer();
        $healthCheckResponseTransfer = $this->healthCheckFacade->executeHealthCheck($healthCheckRequestTransfer);

        $this->assertInstanceOf(HealthCheckResponseTransfer::class, $healthCheckResponseTransfer);
        $this->assertEquals(count($healthCheckPlugins), $healthCheckResponseTransfer->getHealthCheckServiceResponses()->count());
        $this->assertSame($this->healthCheckConfig->getUnavailableHealthCheckStatusMessage(), $healthCheckResponseTransfer->getStatus());
        $this->assertSame($this->healthCheckConfig->getUnavailableHealthCheckStatusCode(), $healthCheckResponseTransfer->getStatusCode());
    }

    /**
     * @return void
     */
    public function testProcessedHealthCheckServicesWithNotEnabledServices(): void
    {
        $this->tester->setDependency(HealthCheckDependencyProvider::PLUGINS_HEALTH_CHECK, []);

        $healthCheckRequestTransfer = new HealthCheckRequestTransfer();
        $healthCheckResponseTransfer = $this->healthCheckFacade->executeHealthCheck($healthCheckRequestTransfer);

        $this->assertInstanceOf(HealthCheckResponseTransfer::class, $healthCheckResponseTransfer);
        $this->assertSame($this->healthCheckConfig->getSuccessHealthCheckStatusMessage(), $healthCheckResponseTransfer->getStatus());
        $this->assertSame($this->healthCheckConfig->getSuccessHealthCheckStatusCode(), $healthCheckResponseTransfer->getStatusCode());
    }

    /**
     * @return void
     */
    public function testProcessedHealthCheckServicesWithRequestedServiceResponse(): void
    {
        $healthCheckPlugins = [
            $this->getHealthCheckPluginMock(static::DATABASE_HEALTH_CHECK_PLUGIN_NAME, true),
            $this->getHealthCheckPluginMock(static::SEARCH_HEALTH_CHECK_PLUGIN_NAME, true),
            $this->getHealthCheckPluginMock(static::STORAGE_HEALTH_CHECK_PLUGIN_NAME, true),
        ];

        $this->tester->setDependency(HealthCheckDependencyProvider::PLUGINS_HEALTH_CHECK, $healthCheckPlugins);

        $requestedHealthCheckServices = [static::DATABASE_HEALTH_CHECK_PLUGIN_NAME, static::STORAGE_HEALTH_CHECK_PLUGIN_NAME];
        $healthCheckRequestTransfer = (new HealthCheckRequestTransfer())
            ->setServices(implode(',', $requestedHealthCheckServices));

        $healthCheckResponseTransfer = $this->healthCheckFacade->executeHealthCheck($healthCheckRequestTransfer);

        $this->assertInstanceOf(HealthCheckResponseTransfer::class, $healthCheckResponseTransfer);
        $this->assertEquals(count($requestedHealthCheckServices), $healthCheckResponseTransfer->getHealthCheckServiceResponses()->count());
        $this->assertSame($this->healthCheckConfig->getSuccessHealthCheckStatusMessage(), $healthCheckResponseTransfer->getStatus());
        $this->assertSame($this->healthCheckConfig->getSuccessHealthCheckStatusCode(), $healthCheckResponseTransfer->getStatusCode());
    }

    /**
     * @return void
     */
    public function testProcessedHealthCheckServicesWithRequestedNotExistingService(): void
    {
        $healthCheckPlugins = [
            $this->getHealthCheckPluginMock(static::DATABASE_HEALTH_CHECK_PLUGIN_NAME, true),
            $this->getHealthCheckPluginMock(static::SEARCH_HEALTH_CHECK_PLUGIN_NAME, true),
            $this->getHealthCheckPluginMock(static::STORAGE_HEALTH_CHECK_PLUGIN_NAME, true),
        ];

        $this->tester->setDependency(HealthCheckDependencyProvider::PLUGINS_HEALTH_CHECK, $healthCheckPlugins);

        $requestedHealthCheckServices = ['REQUESTED_NON_EXISTED_SERVICE_NAME', static::STORAGE_HEALTH_CHECK_PLUGIN_NAME];
        $healthCheckRequestTransfer = (new HealthCheckRequestTransfer())
            ->setServices(implode(',', $requestedHealthCheckServices));

        $healthCheckResponseTransfer = $this->healthCheckFacade->executeHealthCheck($healthCheckRequestTransfer);

        $this->assertInstanceOf(HealthCheckResponseTransfer::class, $healthCheckResponseTransfer);
        $this->assertLessThan(count($requestedHealthCheckServices), $healthCheckResponseTransfer->getHealthCheckServiceResponses()->count());
        $this->assertSame($this->healthCheckConfig->getSuccessHealthCheckStatusMessage(), $healthCheckResponseTransfer->getStatus());
        $this->assertSame($this->healthCheckConfig->getSuccessHealthCheckStatusCode(), $healthCheckResponseTransfer->getStatusCode());
    }

    /**
     * @return void
     */
    public function testProcessedHealthCheckServicesWithDisabledHealthCheck(): void
    {
        $healthCheckPlugins = [
            $this->getHealthCheckPluginMock(static::DATABASE_HEALTH_CHECK_PLUGIN_NAME, true),
            $this->getHealthCheckPluginMock(static::SEARCH_HEALTH_CHECK_PLUGIN_NAME, true),
            $this->getHealthCheckPluginMock(static::STORAGE_HEALTH_CHECK_PLUGIN_NAME, true),
        ];

        $this->tester->setDependency(HealthCheckDependencyProvider::PLUGINS_HEALTH_CHECK, $healthCheckPlugins);
        $this->tester->setConfig(HealthCheckConstants::HEALTH_CHECK_ENABLED, false);

        $healthCheckRequestTransfer = new HealthCheckRequestTransfer();
        $healthCheckResponseTransfer = $this->healthCheckFacade->executeHealthCheck($healthCheckRequestTransfer);

        $this->assertInstanceOf(HealthCheckResponseTransfer::class, $healthCheckResponseTransfer);
        $this->assertEmpty($healthCheckResponseTransfer->getHealthCheckServiceResponses()->count());
        $this->assertSame($this->healthCheckConfig->getForbiddenHealthCheckStatusCode(), $healthCheckResponseTransfer->getStatusCode());
        $this->assertSame($this->healthCheckConfig->getForbiddenHealthCheckStatusMessage(), $healthCheckResponseTransfer->getMessage());
    }

    /**
     * @param string $serviceName
     * @param bool $serviceStatus
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface
     */
    protected function getHealthCheckPluginMock(string $serviceName, bool $serviceStatus)
    {
        $healthCheckPluginMock = $this->getMockBuilder(HealthCheckPluginInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $healthCheckPluginMock
            ->method('getName')
            ->willReturn($serviceName);

        $healthCheckPluginMock
            ->method('check')
            ->willReturn($this->createHealthCheckServiceResponseTransfer($serviceName, $serviceStatus));

        return $healthCheckPluginMock;
    }

    /**
     * @param string $serviceName
     * @param bool $serviceStatus
     *
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    protected function createHealthCheckServiceResponseTransfer(string $serviceName, bool $serviceStatus): HealthCheckServiceResponseTransfer
    {
        return (new HealthCheckServiceResponseTransfer())
            ->setName($serviceName)
            ->setStatus($serviceStatus);
    }
}
