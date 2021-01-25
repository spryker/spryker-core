<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\HealthCheck\ChainFilter\Filter;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Spryker\Shared\HealthCheck\ChainFilter\FilterInterface;

class ServiceNameFilter implements FilterInterface
{
    /**
     * @param \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[] $healthCheckPlugins
     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
     *
     * @return \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    public function filter(array $healthCheckPlugins, HealthCheckRequestTransfer $healthCheckRequestTransfer): array
    {
        $requestedServices = $healthCheckRequestTransfer->getRequestedServices();

        if (count($requestedServices) === 0) {
            return $healthCheckPlugins;
        }

        return $this->filterByServiceName($healthCheckPlugins, $requestedServices);
    }

    /**
     * @param \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[] $healthCheckPlugins
     * @param string[] $requestedServices
     *
     * @return \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected function filterByServiceName(array $healthCheckPlugins, array $requestedServices): array
    {
        $filteredServicePlugins = [];

        foreach ($healthCheckPlugins as $healthCheckPlugin) {
            if (in_array($healthCheckPlugin->getName(), $requestedServices)) {
                $filteredServicePlugins[] = $healthCheckPlugin;
            }
        }

        return $filteredServicePlugins;
    }
}
