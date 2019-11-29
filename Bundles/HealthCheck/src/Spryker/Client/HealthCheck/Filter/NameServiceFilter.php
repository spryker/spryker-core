<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\HealthCheck\Filter;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Spryker\Shared\HealthCheck\Filter\Service\AbstractServiceFilter;
use Spryker\Shared\HealthCheck\Filter\Service\ServiceFilterInterface;

class NameServiceFilter extends AbstractServiceFilter implements ServiceFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
     *
     * @return \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    public function filter(HealthCheckRequestTransfer $healthCheckRequestTransfer): array
    {
        $requestedServices = $healthCheckRequestTransfer->getServices();

        if (strlen($requestedServices) === 0) {
            return $this->healthCheckPlugins;
        }

        return $this->filterServicesByName($requestedServices);
    }
}
