<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\HealthCheck\Filter\Service;

abstract class AbstractServiceFilter
{
    /**
     * @var \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected $healthCheckPlugins;

    /**
     * @param \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[] $healthCheckPlugins
     */
    public function __construct(array $healthCheckPlugins)
    {
        $this->healthCheckPlugins = $healthCheckPlugins;
    }

    /**
     * @param string $requestedServices
     *
     * @return \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected function filterServicesByName(string $requestedServices): array
    {
        $requestedServicesArray = explode(',', $requestedServices);
        $filteredServicePlugins = [];

        foreach ($this->healthCheckPlugins as $healthCheckPlugin) {
            if (in_array($healthCheckPlugin->getName(), $requestedServicesArray)) {
                $filteredServicePlugins[] = $healthCheckPlugin;
            }
        }

        return $filteredServicePlugins;
    }
}
