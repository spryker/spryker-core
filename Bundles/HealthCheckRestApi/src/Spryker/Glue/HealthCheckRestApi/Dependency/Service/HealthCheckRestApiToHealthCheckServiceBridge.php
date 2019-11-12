<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\HealthCheckRestApi\Dependency\Service;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Generated\Shared\Transfer\HealthCheckResponseTransfer;
use Spryker\Service\HealthCheck\HealthCheckServiceInterface;

class HealthCheckRestApiToHealthCheckServiceBridge implements HealthCheckRestApiToHealthCheckServiceInterface
{
    /**
     * @var \Spryker\Service\HealthCheck\HealthCheckServiceInterface
     */
    protected $healthCheckService;

    /**
     * @param \Spryker\Service\HealthCheck\HealthCheckServiceInterface
     */
    public function __construct($healthCheckService)
    {
        $this->healthCheckService = $healthCheckService;
    }

    /**
     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer $healthCheckResponseTransfer
     */
    public function checkGlueHealthCheck(HealthCheckRequestTransfer $healthCheckRequestTransfer): HealthCheckResponseTransfer
    {
        return $this->healthCheckService->checkGlueHealthCheck($healthCheckRequestTransfer);
    }
}
