<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest\HealthIndicator;

use Exception;
use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Shared\ZedRequest\Client\AbstractZedClientInterface;

class ZedRequestHealthIndicator implements HealthIndicatorInterface
{
    /**
     * @var \Spryker\Shared\ZedRequest\Client\AbstractZedClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Shared\ZedRequest\Client\AbstractZedClientInterface $zedRequestClient
     */
    public function __construct(AbstractZedClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function executeHealthCheck(): HealthCheckServiceResponseTransfer
    {
        try {
            /** @var \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer $healthCheckServiceResponseTransfer */
            $healthCheckServiceResponseTransfer = $this->zedRequestClient
                ->call('/zed-request/gateway/health-check', new HealthCheckServiceResponseTransfer());

            return $healthCheckServiceResponseTransfer
                ->setStatus(true);
        } catch (Exception $e) {
            return (new HealthCheckServiceResponseTransfer())
                ->setStatus(false)
                ->setMessage($e->getMessage());
        }
    }
}
