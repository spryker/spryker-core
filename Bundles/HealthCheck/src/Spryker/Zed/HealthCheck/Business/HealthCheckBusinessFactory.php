<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\HealthCheck\Business;

use Spryker\Shared\HealthCheck\ConfigurationProvider\ConfigurationProviderInterface;
use Spryker\Shared\HealthCheck\Filter\Service\ServiceFilterInterface;
use Spryker\Shared\HealthCheck\Processor\HealthCheckProcessorInterface;
use Spryker\Zed\HealthCheck\Business\ConfigurationProvider\ConfigurationProvider;
use Spryker\Zed\HealthCheck\Business\Filter\NameServiceFilter;
use Spryker\Zed\HealthCheck\Business\Processor\HealthCheckProcessor;
use Spryker\Zed\HealthCheck\HealthCheckDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\HealthCheck\HealthCheckConfig getConfig()
 */
class HealthCheckBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Shared\HealthCheck\Processor\HealthCheckProcessorInterface
     */
    public function createHealthCheckProcessor(): HealthCheckProcessorInterface
    {
        return new HealthCheckProcessor(
            $this->createServiceNameFilter(),
            $this->createConfigurationProvider()
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
     * @return \Spryker\Shared\HealthCheck\Filter\Service\ServiceFilterInterface
     */
    public function createServiceNameFilter(): ServiceFilterInterface
    {
        return new NameServiceFilter(
            $this->getHealthCheckPlugins()
        );
    }

    /**
     * @return \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    public function getHealthCheckPlugins(): array
    {
        return $this->getProvidedDependency(HealthCheckDependencyProvider::PLUGINS_HEALTH_CHECK);
    }
}
