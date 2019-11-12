<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ZedRequest\Dependency\Client;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;

class ZedRequestToHealthCheckClientBridge implements ZedRequestToHealthCheckClientInterface
{
    /**
     * @var \Spryker\Client\HealthCheck\HealthCheckClientInterface
     */
    protected $healthCheckClient;

    /**
     * @param \Spryker\Client\HealthCheck\HealthCheckClientInterface
     */
    public function __construct($healthCheckClient)
    {
        $this->healthCheckClient = $healthCheckClient;
    }

    /**
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function doHealthCheck(): HealthCheckServiceResponseTransfer
    {
        return $this->healthCheckClient->doHealthCheck();
    }
}
