<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\HealthCheck\ChainFilter;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;

interface FilterInterface
{
    /**
     * @param \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[] $healthCheckPlugins
     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
     *
     * @return \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    public function filter(array $healthCheckPlugins, HealthCheckRequestTransfer $healthCheckRequestTransfer): array;
}
