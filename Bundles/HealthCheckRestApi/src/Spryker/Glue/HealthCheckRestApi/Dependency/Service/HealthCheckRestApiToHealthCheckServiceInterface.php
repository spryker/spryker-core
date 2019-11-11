<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\HealthCheckRestApi\Dependency\Service;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Generated\Shared\Transfer\HealthCheckResponseTransfer;

interface HealthCheckRestApiToHealthCheckServiceInterface
{
    /**
     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function processHealthCheck(HealthCheckRequestTransfer $healthCheckRequestTransfer): HealthCheckResponseTransfer;
}
