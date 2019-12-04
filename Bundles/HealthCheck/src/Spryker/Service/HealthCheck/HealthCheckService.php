<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\HealthCheck;

use Generated\Shared\Transfer\HealthCheckResponseTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\HealthCheck\HealthCheckServiceFactory getFactory()
 */
class HealthCheckService extends AbstractService implements HealthCheckServiceInterface
{
    /**
     * @param \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer[] $healthCheckServiceResponseTransfers
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function processOutput(array $healthCheckServiceResponseTransfers): HealthCheckResponseTransfer
    {
        return $this->getFactory()->createHealthCheckResponseProcessor()->processOutput($healthCheckServiceResponseTransfers);
    }

    /**
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function processNonExistingServiceName(): HealthCheckResponseTransfer
    {
        return $this->getFactory()->createHealthCheckResponseProcessor()->processNonExistingServiceName();
    }
}
