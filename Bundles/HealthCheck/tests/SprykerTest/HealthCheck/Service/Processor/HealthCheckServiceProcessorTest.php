<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\HealthCheck\Service\Processor;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Generated\Shared\Transfer\HealthCheckResponseTransfer;
use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Service\HealthCheck\HealthCheckConfig;
use Spryker\Service\HealthCheck\HealthCheckDependencyProvider;
use Spryker\Service\HealthCheck\HealthCheckServiceFactory;
use Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface;
use Spryker\Shared\HealthCheck\HealthCheckConstants;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group HealthCheck
 * @group Service
 * @group Processor
 * @group HealthCheckServiceProcessorTest
 * Add your own group annotations below this line
 */
class HealthCheckServiceProcessorTest extends Unit
{
    protected const DATABASE_HEALTH_CHECK_PLUGIN_NAME = 'database';
    protected const SEARCH_HEALTH_CHECK_PLUGIN_NAME = 'search';
    protected const STORAGE_HEALTH_CHECK_PLUGIN_NAME = 'storage';

    /**
     * @var \SprykerTest\Service\HealthCheck\HealthCheckServiceTester
     */
    protected $tester;

    /**
     * @var \Spryker\Service\HealthCheck\HealthCheckServiceFactory
     */
    protected $healthCheckServiceFactory;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $healthCheckServiceConfig = new HealthCheckConfig();
        $this->healthCheckServiceFactory = new HealthCheckServiceFactory();
        $this->healthCheckServiceFactory->setConfig($healthCheckServiceConfig);
    }

    /**
     * @return void
     */
    public function testProcessedHealthCheckServicesWithEmptyRequestAndSuccessServiceResponse(): void
    {
        $healthCheckServicePlugins = [
            $this->getHealthCheckPluginMock(static::DATABASE_HEALTH_CHECK_PLUGIN_NAME, true),
            $this->getHealthCheckPluginMock(static::SEARCH_HEALTH_CHECK_PLUGIN_NAME, true),
            $this->getHealthCheckPluginMock(static::STORAGE_HEALTH_CHECK_PLUGIN_NAME, true),
        ];

        $this->tester->setDependency(HealthCheckDependencyProvider::PLUGINS_ZED_HEALTH_CHECK, $healthCheckServicePlugins);

        $healthCheckRequestTransfer = new HealthCheckRequestTransfer();
        $healthCheckResponseTransfer = $this->healthCheckServiceFactory->createZedHealthCheckServiceProcessor()->process($healthCheckRequestTransfer);

        $this->assertInstanceOf(HealthCheckResponseTransfer::class, $healthCheckResponseTransfer);
        $this->assertEquals(count($healthCheckServicePlugins), $healthCheckResponseTransfer->getHealthCheckServiceResponses()->count());
        $this->assertSame($this->healthCheckServiceFactory->getConfig()->getSuccessHealthCheckStatusMessage(), $healthCheckResponseTransfer->getStatus());
        $this->assertSame($this->healthCheckServiceFactory->getConfig()->getSuccessHealthCheckStatusCode(), $healthCheckResponseTransfer->getStatusCode());
    }

    /**
     * @return void
     */
    public function testProcessedHealthCheckServicesWithEmptyRequestAndFailedServiceResponse(): void
    {
        $healthCheckServicePlugins = [
            $this->getHealthCheckPluginMock(static::DATABASE_HEALTH_CHECK_PLUGIN_NAME, false),
            $this->getHealthCheckPluginMock(static::SEARCH_HEALTH_CHECK_PLUGIN_NAME, false),
            $this->getHealthCheckPluginMock(static::STORAGE_HEALTH_CHECK_PLUGIN_NAME, false),
        ];

        $this->tester->setDependency(HealthCheckDependencyProvider::PLUGINS_ZED_HEALTH_CHECK, $healthCheckServicePlugins);

        $healthCheckRequestTransfer = new HealthCheckRequestTransfer();
        $healthCheckResponseTransfer = $this->healthCheckServiceFactory->createZedHealthCheckServiceProcessor()->process($healthCheckRequestTransfer);

        $this->assertInstanceOf(HealthCheckResponseTransfer::class, $healthCheckResponseTransfer);
        $this->assertEquals(count($healthCheckServicePlugins), $healthCheckResponseTransfer->getHealthCheckServiceResponses()->count());
        $this->assertSame($this->healthCheckServiceFactory->getConfig()->getUnavailableHealthCheckStatusMessage(), $healthCheckResponseTransfer->getStatus());
        $this->assertSame($this->healthCheckServiceFactory->getConfig()->getUnavailableHealthCheckStatusCode(), $healthCheckResponseTransfer->getStatusCode());
    }

    /**
     * @return void
     */
    public function testProcessedHealthCheckServicesWithEmptyRequestAndAnyFailedServiceResponse(): void
    {
        $healthCheckServicePlugins = [
            $this->getHealthCheckPluginMock(static::DATABASE_HEALTH_CHECK_PLUGIN_NAME, true),
            $this->getHealthCheckPluginMock(static::SEARCH_HEALTH_CHECK_PLUGIN_NAME, false),
            $this->getHealthCheckPluginMock(static::STORAGE_HEALTH_CHECK_PLUGIN_NAME, true),
        ];

        $this->tester->setDependency(HealthCheckDependencyProvider::PLUGINS_ZED_HEALTH_CHECK, $healthCheckServicePlugins);

        $healthCheckRequestTransfer = new HealthCheckRequestTransfer();
        $healthCheckResponseTransfer = $this->healthCheckServiceFactory->createZedHealthCheckServiceProcessor()->process($healthCheckRequestTransfer);

        $this->assertInstanceOf(HealthCheckResponseTransfer::class, $healthCheckResponseTransfer);
        $this->assertEquals(count($healthCheckServicePlugins), $healthCheckResponseTransfer->getHealthCheckServiceResponses()->count());
        $this->assertSame($this->healthCheckServiceFactory->getConfig()->getUnavailableHealthCheckStatusMessage(), $healthCheckResponseTransfer->getStatus());
        $this->assertSame($this->healthCheckServiceFactory->getConfig()->getUnavailableHealthCheckStatusCode(), $healthCheckResponseTransfer->getStatusCode());
    }

    /**
     * @return void
     */
    public function testProcessedHealthCheckServicesWithNotEnabledServices(): void
    {
        $this->tester->setDependency(HealthCheckDependencyProvider::PLUGINS_ZED_HEALTH_CHECK, []);

        $healthCheckRequestTransfer = new HealthCheckRequestTransfer();
        $healthCheckResponseTransfer = $this->healthCheckServiceFactory->createZedHealthCheckServiceProcessor()->process($healthCheckRequestTransfer);

        $this->assertInstanceOf(HealthCheckResponseTransfer::class, $healthCheckResponseTransfer);
        $this->assertSame($this->healthCheckServiceFactory->getConfig()->getSuccessHealthCheckStatusMessage(), $healthCheckResponseTransfer->getStatus());
        $this->assertSame($this->healthCheckServiceFactory->getConfig()->getSuccessHealthCheckStatusCode(), $healthCheckResponseTransfer->getStatusCode());
    }

    /**
     * @return void
     */
    public function testProcessedHealthCheckServicesWithRequestedServiceResponse(): void
    {
        $healthCheckServicePlugins = [
            $this->getHealthCheckPluginMock(static::DATABASE_HEALTH_CHECK_PLUGIN_NAME, true),
            $this->getHealthCheckPluginMock(static::SEARCH_HEALTH_CHECK_PLUGIN_NAME, true),
            $this->getHealthCheckPluginMock(static::STORAGE_HEALTH_CHECK_PLUGIN_NAME, true),
        ];

        $this->tester->setDependency(HealthCheckDependencyProvider::PLUGINS_ZED_HEALTH_CHECK, $healthCheckServicePlugins);

        $requestedHealthCheckServices = [static::DATABASE_HEALTH_CHECK_PLUGIN_NAME, static::STORAGE_HEALTH_CHECK_PLUGIN_NAME];
        $healthCheckRequestTransfer = (new HealthCheckRequestTransfer())
            ->setServices(implode(',', $requestedHealthCheckServices));

        $healthCheckResponseTransfer = $this->healthCheckServiceFactory->createZedHealthCheckServiceProcessor()->process($healthCheckRequestTransfer);

        $this->assertInstanceOf(HealthCheckResponseTransfer::class, $healthCheckResponseTransfer);
        $this->assertEquals(count($requestedHealthCheckServices), $healthCheckResponseTransfer->getHealthCheckServiceResponses()->count());
        $this->assertSame($this->healthCheckServiceFactory->getConfig()->getSuccessHealthCheckStatusMessage(), $healthCheckResponseTransfer->getStatus());
        $this->assertSame($this->healthCheckServiceFactory->getConfig()->getSuccessHealthCheckStatusCode(), $healthCheckResponseTransfer->getStatusCode());
    }

    /**
     * @return void
     */
    public function testProcessedHealthCheckServicesWithRequestedNotExistingService(): void
    {
        $healthCheckServicePlugins = [
            $this->getHealthCheckPluginMock(static::DATABASE_HEALTH_CHECK_PLUGIN_NAME, true),
            $this->getHealthCheckPluginMock(static::SEARCH_HEALTH_CHECK_PLUGIN_NAME, true),
            $this->getHealthCheckPluginMock(static::STORAGE_HEALTH_CHECK_PLUGIN_NAME, true),
        ];

        $this->tester->setDependency(HealthCheckDependencyProvider::PLUGINS_ZED_HEALTH_CHECK, $healthCheckServicePlugins);

        $requestedHealthCheckServices = ['REQUESTED_NON_EXISTED_SERVICE_NAME', static::STORAGE_HEALTH_CHECK_PLUGIN_NAME];
        $healthCheckRequestTransfer = (new HealthCheckRequestTransfer())
            ->setServices(implode(',', $requestedHealthCheckServices));

        $healthCheckResponseTransfer = $this->healthCheckServiceFactory->createZedHealthCheckServiceProcessor()->process($healthCheckRequestTransfer);

        $this->assertInstanceOf(HealthCheckResponseTransfer::class, $healthCheckResponseTransfer);
        $this->assertLessThan(count($requestedHealthCheckServices), $healthCheckResponseTransfer->getHealthCheckServiceResponses()->count());
        $this->assertSame($this->healthCheckServiceFactory->getConfig()->getSuccessHealthCheckStatusMessage(), $healthCheckResponseTransfer->getStatus());
        $this->assertSame($this->healthCheckServiceFactory->getConfig()->getSuccessHealthCheckStatusCode(), $healthCheckResponseTransfer->getStatusCode());
    }

    /**
     * @return void
     */
    public function testProcessedHealthCheckServicesWithDisabledHealthCheck(): void
    {
        $healthCheckServicePlugins = [
            $this->getHealthCheckPluginMock(static::DATABASE_HEALTH_CHECK_PLUGIN_NAME, true),
            $this->getHealthCheckPluginMock(static::SEARCH_HEALTH_CHECK_PLUGIN_NAME, true),
            $this->getHealthCheckPluginMock(static::STORAGE_HEALTH_CHECK_PLUGIN_NAME, true),
        ];

        $this->tester->setDependency(HealthCheckDependencyProvider::PLUGINS_ZED_HEALTH_CHECK, $healthCheckServicePlugins);
        $this->tester->setConfig(HealthCheckConstants::HEALTH_CHECK_ENABLED, false);

        $healthCheckRequestTransfer = new HealthCheckRequestTransfer();
        $healthCheckResponseTransfer = $this->healthCheckServiceFactory->createZedHealthCheckServiceProcessor()->process($healthCheckRequestTransfer);

        $this->assertInstanceOf(HealthCheckResponseTransfer::class, $healthCheckResponseTransfer);
        $this->assertEmpty($healthCheckResponseTransfer->getHealthCheckServiceResponses()->count());
        $this->assertSame($this->healthCheckServiceFactory->getConfig()->getForbiddenHealthCheckStatusCode(), $healthCheckResponseTransfer->getStatusCode());
        $this->assertSame($this->healthCheckServiceFactory->getConfig()->getForbiddenHealthCheckStatusMessage(), $healthCheckResponseTransfer->getMessage());
    }

    /**
     * @param string $serviceName
     * @param bool $serviceStatus
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface
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
