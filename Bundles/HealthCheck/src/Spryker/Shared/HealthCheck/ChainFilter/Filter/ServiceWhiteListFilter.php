<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\HealthCheck\ChainFilter\Filter;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Spryker\Shared\HealthCheck\ChainFilter\ChainFilterInterface;

class ServiceWhiteListFilter implements ChainFilterInterface
{
    /**
     * @param \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[] $healthCheckPlugins
     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
     *
     * @return \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    public function filter(array $healthCheckPlugins, HealthCheckRequestTransfer $healthCheckRequestTransfer): array
    {
        $availableServices = $healthCheckRequestTransfer->getAvailableServices();

        if (count($availableServices) === 0) {
            return $healthCheckPlugins;
        }

        return $this->filterByWhiteListServices($healthCheckPlugins, $availableServices);
    }

    /**
     * @param \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[] $healthCheckPlugins
     * @param string[] $availableServices
     *
     * @return \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected function filterByWhiteListServices(array $healthCheckPlugins, array $availableServices): array
    {
        $filteredServicePlugins = [];

        foreach ($healthCheckPlugins as $healthCheckPlugin) {
            if (in_array($healthCheckPlugin->getName(), $availableServices)) {
                $filteredServicePlugins[] = $healthCheckPlugin;
            }
        }

        return $filteredServicePlugins;
    }
}
