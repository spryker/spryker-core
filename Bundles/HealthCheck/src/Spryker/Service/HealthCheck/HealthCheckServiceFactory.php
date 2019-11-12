<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\HealthCheck;

use Spryker\Service\HealthCheck\Filter\Service\ServiceFilter;
use Spryker\Service\HealthCheck\Filter\Service\ServiceFilterInterface;
use Spryker\Service\HealthCheck\Processor\HealthCheckServiceProcessor;
use Spryker\Service\HealthCheck\Processor\HealthCheckServiceProcessorInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\Kernel\Container;

class HealthCheckServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\HealthCheck\Processor\HealthCheckServiceProcessorInterface
     */
    public function createYvesHealthCheckServiceProcessor(): HealthCheckServiceProcessorInterface
    {
        return new HealthCheckServiceProcessor(
            $this->createYvesServiceFilter()
        );
    }

    /**
     * @return \Spryker\Service\HealthCheck\Filter\Service\ServiceFilterInterface
     */
    public function createYvesServiceFilter(): ServiceFilterInterface
    {
        return new ServiceFilter(
            $this->getYvesHealthCheckPlugins()
        );
    }

    /**
     * @return \Spryker\Service\HealthCheck\Processor\HealthCheckServiceProcessorInterface
     */
    public function createZedHealthCheckServiceProcessor(): HealthCheckServiceProcessorInterface
    {
        return new HealthCheckServiceProcessor(
            $this->createZedServiceFilter()
        );
    }

    /**
     * @return \Spryker\Service\HealthCheck\Filter\Service\ServiceFilterInterface
     */
    public function createZedServiceFilter(): ServiceFilterInterface
    {
        return new ServiceFilter(
            $this->getZedHealthCheckPlugins()
        );
    }

    /**
     * @return \Spryker\Service\HealthCheck\Processor\HealthCheckServiceProcessorInterface
     */
    public function createGlueHealthCheckServiceProcessor(): HealthCheckServiceProcessorInterface
    {
        return new HealthCheckServiceProcessor(
            $this->createGlueServiceFilter()
        );
    }

    /**
     * @return \Spryker\Service\HealthCheck\Filter\Service\ServiceFilterInterface
     */
    public function createGlueServiceFilter(): ServiceFilterInterface
    {
        return new ServiceFilter(
            $this->getGlueHealthCheckPlugins()
        );
    }

    /**
     * @return \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    public function getYvesHealthCheckPlugins(): array
    {
        return $this->getProvidedDependency(HealthCheckDependencyProvider::PLUGINS_YVES_HEALTH_CHECK);
    }

    /**
     * @return \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    public function getZedHealthCheckPlugins(): array
    {
        return $this->getProvidedDependency(HealthCheckDependencyProvider::PLUGINS_ZED_HEALTH_CHECK);
    }

    /**
     * @return \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    public function getGlueHealthCheckPlugins(): array
    {
        return $this->getProvidedDependency(HealthCheckDependencyProvider::PLUGINS_GLUE_HEALTH_CHECK);
    }
}
