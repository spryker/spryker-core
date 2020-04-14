<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\HealthCheck\Processor;

use Generated\Shared\Transfer\HealthCheckResponseTransfer;

interface HealthCheckProcessorInterface
{
    /**
     * @param string|null $requestedServices
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function process(?string $requestedServices = null): HealthCheckResponseTransfer;
}
