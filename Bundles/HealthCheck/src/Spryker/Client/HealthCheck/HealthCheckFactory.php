<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\HealthCheck;

use Spryker\Client\HealthCheck\ConfigurationProvider\ConfigurationProvider;
use Spryker\Client\HealthCheck\Dependency\Client\HealthCheckToZedRequestClientInterface;
use Spryker\Client\HealthCheck\Filter\NameServiceFilter;
use Spryker\Client\HealthCheck\HealthIndicator\HealthIndicatorInterface;
use Spryker\Client\HealthCheck\HealthIndicator\ZedRequestHealthIndicator;
use Spryker\Client\HealthCheck\Processor\HealthCheckProcessor;
use Spryker\Client\HealthCheck\Zed\HealthCheckZedStub;
use Spryker\Client\HealthCheck\Zed\HealthCheckZedStubInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\HealthCheck\ConfigurationProvider\ConfigurationProviderInterface;
use Spryker\Shared\HealthCheck\Filter\Service\ServiceFilterInterface;
use Spryker\Shared\HealthCheck\Processor\HealthCheckProcessorInterface;

/**
 * @method \Spryker\Client\HealthCheck\HealthCheckConfig getConfig()
 */
class HealthCheckFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\HealthCheck\Zed\HealthCheckZedStubInterface
     */
    public function createHealthCheckZedStub(): HealthCheckZedStubInterface
    {
        return new HealthCheckZedStub(
            $this->getZedRequestClient()
        );
    }

    /**
     * @return \Spryker\Shared\HealthCheck\Processor\HealthCheckProcessorInterface
     */
    public function createHealthCheckProcessor(): HealthCheckProcessorInterface
    {
        return new HealthCheckProcessor(
            $this->createNameServiceFilter(),
            $this->createConfigurationProvider()
        );
    }

    /**
     * @return \Spryker\Shared\HealthCheck\Filter\Service\ServiceFilterInterface
     */
    public function createNameServiceFilter(): ServiceFilterInterface
    {
        return new NameServiceFilter(
            $this->getHealthCheckPlugins()
        );
    }

    /**
     * @return \Spryker\Shared\HealthCheck\ConfigurationProvider\ConfigurationProviderInterface
     */
    public function createConfigurationProvider(): ConfigurationProviderInterface
    {
        return new ConfigurationProvider(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Client\HealthCheck\HealthIndicator\HealthIndicatorInterface
     */
    public function createZedRequestHealthCheckIndicator(): HealthIndicatorInterface
    {
        return new ZedRequestHealthIndicator(
            $this->createHealthCheckZedStub()
        );
    }

    /**
     * @return \Spryker\Client\HealthCheck\Dependency\Client\HealthCheckToZedRequestClientInterface
     */
    public function getZedRequestClient(): HealthCheckToZedRequestClientInterface
    {
        return $this->getProvidedDependency(HealthCheckDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    public function getHealthCheckPlugins(): array
    {
        return $this->getProvidedDependency(HealthCheckDependencyProvider::PLUGINS_HEALTH_CHECK);
    }
}
