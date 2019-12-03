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
        $whiteListServices = $healthCheckRequestTransfer->getWhiteListServices();

        if (count($whiteListServices) === 0) {
            return $healthCheckPlugins;
        }

        return $this->filterByWhiteLitServices($healthCheckPlugins, $whiteListServices);
    }

    /**
     * @param \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[] $healthCheckPlugins
     * @param string[] $whiteListServices
     *
     * @return \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected function filterByWhiteLitServices(array $healthCheckPlugins, array $whiteListServices): array
    {
        $filteredServicePlugins = [];

        foreach ($healthCheckPlugins as $healthCheckPluginName => $healthCheckPlugin) {
            if (in_array($healthCheckPluginName, $whiteListServices)) {
                $filteredServicePlugins[$healthCheckPluginName] = $healthCheckPlugin;
            }
        }

        return $filteredServicePlugins;
    }
}
