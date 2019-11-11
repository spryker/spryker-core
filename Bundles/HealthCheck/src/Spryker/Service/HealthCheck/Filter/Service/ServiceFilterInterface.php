<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\HealthCheck\Filter\Service;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;

interface ServiceFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
     *
     * @return \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    public function filter(HealthCheckRequestTransfer $healthCheckRequestTransfer): array;
}
