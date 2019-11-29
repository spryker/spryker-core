<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\HealthCheck\HealthIndicator;

use Exception;
use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Client\HealthCheck\HealthCheckClientInterface;

class ZedRequestHealthIndicator implements HealthIndicatorInterface
{
    /**
     * @var \Spryker\Client\HealthCheck\HealthCheckClientInterface
     */
    protected $healthCheckClient;

    /**
     * @param \Spryker\Client\HealthCheck\HealthCheckClientInterface $healthCheckClient
     */
    public function __construct(HealthCheckClientInterface $healthCheckClient)
    {
        $this->healthCheckClient = $healthCheckClient;
    }

    /**
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function executeHealthCheck(): HealthCheckServiceResponseTransfer
    {
        try {
            $healthCheckServiceResponseTransfer = $this->healthCheckClient->executeZedRequestHealthCheck();

            return $healthCheckServiceResponseTransfer
                ->setStatus(true);
        } catch (Exception $e) {
            return (new HealthCheckServiceResponseTransfer())
                ->setStatus(false)
                ->setMessage($e->getMessage());
        }
    }
}
