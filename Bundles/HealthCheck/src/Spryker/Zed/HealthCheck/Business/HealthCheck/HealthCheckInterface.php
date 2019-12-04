<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\HealthCheck\Business\HealthCheck;

use Generated\Shared\Transfer\HealthCheckResponseTransfer;

interface HealthCheckInterface
{
    /**
     * @param string|null $requestedServices
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function executeHealthCheck(?string $requestedServices = null): HealthCheckResponseTransfer;
}
