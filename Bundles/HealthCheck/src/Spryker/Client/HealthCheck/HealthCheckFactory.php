<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\HealthCheck;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Service\HealthCheck\HealthCheckServiceInterface;
use Spryker\Shared\HealthCheck\ChainFilter\ChainFilterInterface;
use Spryker\Shared\HealthCheck\ChainFilter\Filter\ServiceNameFilter;
use Spryker\Shared\HealthCheck\ChainFilter\Filter\ServiceWhiteListFilter;
use Spryker\Shared\HealthCheck\ChainFilter\ServiceChainFilter;
use Spryker\Shared\HealthCheck\Processor\HealthCheckProcessor;
use Spryker\Shared\HealthCheck\Processor\HealthCheckProcessorInterface;
use Spryker\Zed\HealthCheck\HealthCheckDependencyProvider;

/**
 * @method \Spryker\Client\HealthCheck\HealthCheckConfig getConfig()
 */
class HealthCheckFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\HealthCheck\Processor\HealthCheckProcessorInterface
     */
    public function createHealthCheckProcessor(): HealthCheckProcessorInterface
    {
        return new HealthCheckProcessor(
            $this->createServiceChainFilter(),
            $this->getHealthCheckPlugins(),
            $this->getHealthCheckService()
        );
    }

    /**
     * @return \Spryker\Shared\HealthCheck\ChainFilter\ChainFilterInterface
     */
    public function createServiceChainFilter(): ChainFilterInterface
    {
        $chainFilter = new ServiceChainFilter();
        $chainFilter
            ->addFilter($this->createServiceWhiteListFilter())
            ->addFilter($this->createServiceNameFilter());

        return $chainFilter;
    }

    /**
     * @return \Spryker\Shared\HealthCheck\ChainFilter\ChainFilterInterface
     */
    public function createServiceNameFilter(): ChainFilterInterface
    {
        return new ServiceNameFilter();
    }

    /**
     * @return \Spryker\Shared\HealthCheck\ChainFilter\ChainFilterInterface
     */
    public function createServiceWhiteListFilter(): ChainFilterInterface
    {
        return new ServiceWhiteListFilter();
    }

    /**
     * @return \Spryker\Service\HealthCheck\HealthCheckServiceInterface
     */
    public function getHealthCheckService(): HealthCheckServiceInterface
    {
        return $this->getProvidedDependency(HealthCheckDependencyProvider::SERVICE_HEALTH_CHECK);
    }

    /**
     * @return \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    public function getHealthCheckPlugins(): array
    {
        return $this->getProvidedDependency(HealthCheckDependencyProvider::PLUGINS_HEALTH_CHECK);
    }
}
