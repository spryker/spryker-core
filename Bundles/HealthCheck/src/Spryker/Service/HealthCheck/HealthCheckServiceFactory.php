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
    public function createHealthCheckServiceProcessor(): HealthCheckServiceProcessorInterface
    {
        return new HealthCheckServiceProcessor(
            $this->createServiceFilter()
        );
    }

    /**
     * @return \Spryker\Service\HealthCheck\Filter\Service\ServiceFilterInterface
     */
    public function createServiceFilter(): ServiceFilterInterface
    {
        return new ServiceFilter(
            $this->getHealthCheckPlugins()
        );
    }

    /**
     * @return \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    public function getHealthCheckPlugins(): array
    {
        return $this->getProvidedDependency(HealthCheckDependencyProvider::PLUGINS_HEALTH_CHECK);
    }
}
