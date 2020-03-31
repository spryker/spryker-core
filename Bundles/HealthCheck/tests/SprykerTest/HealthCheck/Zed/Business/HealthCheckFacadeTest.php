<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\HealthCheck\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\HealthCheckResponseTransfer;
use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Shared\HealthCheck\HealthCheckConfig;
use Spryker\Shared\HealthCheck\HealthCheckConstants;
use Spryker\Shared\HealthCheck\Processor\ResponseProcessor;
use Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface;
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
    protected const DATABASE_SERVICE_NAME = 'database';
    protected const SEARCH_SERVICE_NAME = 'search';
    protected const STORAGE_SERVICE_NAME = 'storage';

    protected const IS_SERVICE_HEALTHY = true;
    protected const IS_SERVICE_UNHEALTHY = false;

    /**
     * @var \SprykerTest\Zed\HealthCheck\HealthCheckBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setConfig(HealthCheckConstants::HEALTH_CHECK_ENABLED, true);
    }

    /**
     * @return void
     */
    public function testProcessedHealthCheckServicesWithEmptyRequestAndSuccessResponse(): void
    {
        // Arrange
        $healthCheckPlugins = $this->getHealthCheckPlugins(
            static::IS_SERVICE_HEALTHY,
            static::IS_SERVICE_HEALTHY,
            static::IS_SERVICE_HEALTHY
        );

        $this->tester->setDependency(HealthCheckDependencyProvider::PLUGINS_HEALTH_CHECK, $healthCheckPlugins);

        // Act
        $healthCheckResponseTransfer = $this->tester->getFacade()->executeHealthCheck();

        // Assert
        $this->assertInstanceOf(HealthCheckResponseTransfer::class, $healthCheckResponseTransfer);
        $this->assertEquals(count($healthCheckPlugins), $healthCheckResponseTransfer->getHealthCheckServiceResponses()->count());
        $this->assertSame(ResponseProcessor::HEALTH_CHECK_SUCCESS_STATUS_MESSAGE, $healthCheckResponseTransfer->getStatus());
        $this->assertSame(ResponseProcessor::HEALTH_CHECK_SUCCESS_STATUS_CODE, $healthCheckResponseTransfer->getStatusCode());
    }

    /**
     * @return void
     */
    public function testProcessedHealthCheckServicesWithEmptyRequestAndFailedResponse(): void
    {
        // Arrange
        $healthCheckPlugins = $this->getHealthCheckPlugins(
            static::IS_SERVICE_UNHEALTHY,
            static::IS_SERVICE_UNHEALTHY,
            static::IS_SERVICE_UNHEALTHY
        );
        $this->tester->setDependency(HealthCheckDependencyProvider::PLUGINS_HEALTH_CHECK, $healthCheckPlugins);

        // Act
        $healthCheckResponseTransfer = $this->tester->getFacade()->executeHealthCheck();

        // Assert
        $this->assertInstanceOf(HealthCheckResponseTransfer::class, $healthCheckResponseTransfer);
        $this->assertEquals(count($healthCheckPlugins), $healthCheckResponseTransfer->getHealthCheckServiceResponses()->count());
        $this->assertSame(ResponseProcessor::HEALTH_CHECK_UNAVAILABLE_STATUS_MESSAGE, $healthCheckResponseTransfer->getStatus());
        $this->assertSame(ResponseProcessor::HEALTH_CHECK_UNAVAILABLE_STATUS_CODE, $healthCheckResponseTransfer->getStatusCode());
    }

    /**
     * @return void
     */
    public function testProcessedHealthCheckServicesWithEmptyRequestAndAnyFailedResponse(): void
    {
        // Arrange
        $healthCheckPlugins = $this->getHealthCheckPlugins(
            static::IS_SERVICE_HEALTHY,
            static::IS_SERVICE_UNHEALTHY,
            static::IS_SERVICE_HEALTHY
        );
        $this->tester->setDependency(HealthCheckDependencyProvider::PLUGINS_HEALTH_CHECK, $healthCheckPlugins);

        // Act
        $healthCheckResponseTransfer = $this->tester->getFacade()->executeHealthCheck();

        // Assert
        $this->assertInstanceOf(HealthCheckResponseTransfer::class, $healthCheckResponseTransfer);
        $this->assertEquals(count($healthCheckPlugins), $healthCheckResponseTransfer->getHealthCheckServiceResponses()->count());
        $this->assertSame(ResponseProcessor::HEALTH_CHECK_UNAVAILABLE_STATUS_MESSAGE, $healthCheckResponseTransfer->getStatus());
        $this->assertSame(ResponseProcessor::HEALTH_CHECK_UNAVAILABLE_STATUS_CODE, $healthCheckResponseTransfer->getStatusCode());
    }

    /**
     * @return void
     */
    public function testProcessedHealthCheckServicesWithNotEnabledServices(): void
    {
        // Arrange
        $this->tester->setDependency(HealthCheckDependencyProvider::PLUGINS_HEALTH_CHECK, []);

        // Act
        $healthCheckResponseTransfer = $this->tester->getFacade()->executeHealthCheck();

        // Assert
        $this->assertInstanceOf(HealthCheckResponseTransfer::class, $healthCheckResponseTransfer);
        $this->assertSame(ResponseProcessor::HEALTH_CHECK_SUCCESS_STATUS_MESSAGE, $healthCheckResponseTransfer->getStatus());
        $this->assertSame(ResponseProcessor::HEALTH_CHECK_SUCCESS_STATUS_CODE, $healthCheckResponseTransfer->getStatusCode());
    }

    /**
     * @return void
     */
    public function testProcessedHealthCheckServicesWithRequestedServiceResponse(): void
    {
        // Arrange
        $healthCheckPlugins = $this->getHealthCheckPlugins(
            static::IS_SERVICE_HEALTHY,
            static::IS_SERVICE_HEALTHY,
            static::IS_SERVICE_HEALTHY
        );

        $this->tester->setDependency(HealthCheckDependencyProvider::PLUGINS_HEALTH_CHECK, $healthCheckPlugins);

        // Act
        $requestedHealthCheckServices = [static::DATABASE_SERVICE_NAME, static::STORAGE_SERVICE_NAME];
        $healthCheckResponseTransfer = $this->tester->getFacade()->executeHealthCheck(implode(',', $requestedHealthCheckServices));

        // Assert
        $this->assertInstanceOf(HealthCheckResponseTransfer::class, $healthCheckResponseTransfer);
        $this->assertEquals(count($requestedHealthCheckServices), $healthCheckResponseTransfer->getHealthCheckServiceResponses()->count());
        $this->assertSame(ResponseProcessor::HEALTH_CHECK_SUCCESS_STATUS_MESSAGE, $healthCheckResponseTransfer->getStatus());
        $this->assertSame(ResponseProcessor::HEALTH_CHECK_SUCCESS_STATUS_CODE, $healthCheckResponseTransfer->getStatusCode());
    }

    /**
     * @return void
     */
    public function testProcessedHealthCheckServicesWithRequestedNotExistingService(): void
    {
        // Arrange
        $healthCheckPlugins = $this->getHealthCheckPlugins(
            static::IS_SERVICE_HEALTHY,
            static::IS_SERVICE_HEALTHY,
            static::IS_SERVICE_HEALTHY
        );

        $this->tester->setDependency(HealthCheckDependencyProvider::PLUGINS_HEALTH_CHECK, $healthCheckPlugins);

        // Act
        $requestedHealthCheckServices = ['REQUESTED_NON_EXISTED_SERVICE_NAME', static::STORAGE_SERVICE_NAME];
        $healthCheckResponseTransfer = $this->tester->getFacade()->executeHealthCheck(implode(',', $requestedHealthCheckServices));

        // Assert
        $this->assertInstanceOf(HealthCheckResponseTransfer::class, $healthCheckResponseTransfer);
        $this->assertLessThan(count($requestedHealthCheckServices), $healthCheckResponseTransfer->getHealthCheckServiceResponses()->count());
        $this->assertSame(ResponseProcessor::HEALTH_CHECK_BAD_REQUEST_STATUS_MESSAGE, $healthCheckResponseTransfer->getMessage());
        $this->assertSame(ResponseProcessor::HEALTH_CHECK_BAD_REQUEST_STATUS_CODE, $healthCheckResponseTransfer->getStatusCode());
    }

    /**
     * @return void
     */
    public function testProcessedHealthCheckServicesWithDisabledHealthCheck(): void
    {
        // Arrange
        $healthCheckPlugins = $this->getHealthCheckPlugins(
            static::IS_SERVICE_HEALTHY,
            static::IS_SERVICE_HEALTHY,
            static::IS_SERVICE_HEALTHY
        );

        $this->tester->setDependency(HealthCheckDependencyProvider::PLUGINS_HEALTH_CHECK, $healthCheckPlugins);
        $this->tester->setConfig(HealthCheckConstants::HEALTH_CHECK_ENABLED, false);

        // Act
        $healthCheckResponseTransfer = $this->tester->getFacade()->executeHealthCheck();

        // Assert
        $this->assertInstanceOf(HealthCheckResponseTransfer::class, $healthCheckResponseTransfer);
        $this->assertEmpty($healthCheckResponseTransfer->getHealthCheckServiceResponses()->count());
        $this->assertSame(ResponseProcessor::HEALTH_CHECK_FORBIDDEN_STATUS_CODE, $healthCheckResponseTransfer->getStatusCode());
        $this->assertSame(ResponseProcessor::HEALTH_CHECK_FORBIDDEN_STATUS_MESSAGE, $healthCheckResponseTransfer->getMessage());
    }

    /**
     * @param bool $isDatabaseServiceHealthy
     * @param bool $isServiceServiceHealthy
     * @param bool $isStorageServiceHealthy
     *
     * @return \PHPUnit\Framework\MockObject\MockObject[]|\Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected function getHealthCheckPlugins(
        bool $isDatabaseServiceHealthy,
        bool $isServiceServiceHealthy,
        bool $isStorageServiceHealthy
    ): array {
        return [
            static::DATABASE_SERVICE_NAME => $this->getHealthCheckPluginMock(static::DATABASE_SERVICE_NAME, $isDatabaseServiceHealthy),
            static::SEARCH_SERVICE_NAME => $this->getHealthCheckPluginMock(static::SEARCH_SERVICE_NAME, $isServiceServiceHealthy),
            static::STORAGE_SERVICE_NAME => $this->getHealthCheckPluginMock(static::STORAGE_SERVICE_NAME, $isStorageServiceHealthy),
        ];
    }

    /**
     * @param string $serviceName
     * @param bool $serviceStatus
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface
     */
    protected function getHealthCheckPluginMock(string $serviceName, bool $serviceStatus): HealthCheckPluginInterface
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

    /**
     * @return \Spryker\Shared\HealthCheck\HealthCheckConfig
     */
    protected function getConfig(): HealthCheckConfig
    {
        return new HealthCheckConfig();
    }
}
