<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\HealthCheck\Processor;

use Generated\Shared\Transfer\HealthCheckResponseTransfer;

interface ResponseProcessorInterface
{
    /**
     * @param \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer[] $healthCheckServiceResponseTransfers
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function processOutput(array $healthCheckServiceResponseTransfers): HealthCheckResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function processNonExistingServiceName(): HealthCheckResponseTransfer;
}
