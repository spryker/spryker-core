<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ZedRequest\HealthIndicator;

use Exception;
use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Service\ZedRequest\Dependency\Client\ZedRequestToHealthCheckClientInterface;

class HealthIndicator implements HealthIndicatorInterface
{
    /**
     * @var \Spryker\Service\ZedRequest\Dependency\Client\ZedRequestToHealthCheckClientInterface
     */
    protected $healthCheckClient;

    /**
     * @param \Spryker\Service\ZedRequest\Dependency\Client\ZedRequestToHealthCheckClientInterface $healthCheckClient
     */
    public function __construct(ZedRequestToHealthCheckClientInterface $healthCheckClient)
    {
        $this->healthCheckClient = $healthCheckClient;
    }

    /**
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function executeHealthCheck(): HealthCheckServiceResponseTransfer
    {
        try {
            $healthCheckServiceResponseTransfer = $this->healthCheckClient->doHealthCheck();
        } catch (Exception $e) {
            return (new HealthCheckServiceResponseTransfer())
                ->setStatus(false)
                ->setMessage($e->getMessage());
        }

        return (new HealthCheckServiceResponseTransfer())
            ->setStatus(true);
    }
}
