<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\HealthCheck\HealthIndicator;

use Exception;
use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Client\HealthCheck\Zed\HealthCheckZedStubInterface;

class ZedRequestHealthIndicator implements HealthIndicatorInterface
{
    /**
     * @var \Spryker\Client\HealthCheck\Zed\HealthCheckZedStubInterface
     */
    protected $healthCheckZedRequestStub;

    /**
     * @param \Spryker\Client\HealthCheck\Zed\HealthCheckZedStubInterface $healthCheckZedRequestStub
     */
    public function __construct(HealthCheckZedStubInterface $healthCheckZedRequestStub)
    {
        $this->healthCheckZedRequestStub = $healthCheckZedRequestStub;
    }

    /**
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function executeHealthCheck(): HealthCheckServiceResponseTransfer
    {
        try {
            $healthCheckServiceResponseTransfer = $this->healthCheckZedRequestStub->executeZedRequestHealthCheck();

            return $healthCheckServiceResponseTransfer
                ->setStatus(true);
        } catch (Exception $e) {
            return (new HealthCheckServiceResponseTransfer())
                ->setStatus(false)
                ->setMessage($e->getMessage());
        }
    }
}
