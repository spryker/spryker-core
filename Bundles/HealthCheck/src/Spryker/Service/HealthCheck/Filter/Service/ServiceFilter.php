<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\HealthCheck\Filter\Service;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;

class ServiceFilter implements ServiceFilterInterface
{
    /**
     * @var \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected $healthCheckPlugins;

    /**
     * @param \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[] $healthCheckPlugins
     */
    public function __construct(array $healthCheckPlugins)
    {
        $this->healthCheckPlugins = $healthCheckPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
     *
     * @return \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    public function filter(HealthCheckRequestTransfer $healthCheckRequestTransfer): array
    {
        //tmp
        $this->healthCheckPlugins = $this->healthCheckPlugins[$healthCheckRequestTransfer->getApplication()];

        $requestedServices = $healthCheckRequestTransfer->getServices();

        if ($requestedServices === [] || $requestedServices === null) {
            return $this->healthCheckPlugins;
        }

        return $this->filterServicesByName($requestedServices);
    }

    /**
     * @param string[] $requestedServices
     *
     * @return \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected function filterServicesByName(array $requestedServices): array
    {
        $filteredServicePlugins = [];

        foreach ($this->healthCheckPlugins as $healthCheckPlugin) {
            if(in_array($healthCheckPlugin->getName(), $requestedServices)) {
                $filteredServicePlugins[] = $healthCheckPlugin;
            }
        }

        return $filteredServicePlugins;
    }
}
