<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\HealthCheck\Zed;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Client\HealthCheck\Dependency\Client\HealthCheckToZedRequestClientInterface;

class HealthCheckZedStub implements HealthCheckZedStubInterface
{
    /**
     * @var \Spryker\Client\HealthCheck\Dependency\Client\HealthCheckToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\HealthCheck\Dependency\Client\HealthCheckToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(HealthCheckToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function executeZedRequestHealthCheck(): HealthCheckServiceResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer $healthCheckServiceResponseTransfer */
        $healthCheckServiceResponseTransfer = $this->zedRequestClient
            ->call('/health-check/gateway/health-check', new HealthCheckServiceResponseTransfer());

        return $healthCheckServiceResponseTransfer;
    }
}
