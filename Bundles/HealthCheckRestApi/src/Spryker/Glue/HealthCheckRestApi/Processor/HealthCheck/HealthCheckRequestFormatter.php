<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\HealthCheckRestApi\Processor\HealthCheck;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\HealthCheckRestApi\HealthCheckRestApiConfig;

class HealthCheckRequestFormatter implements HealthCheckRequestFormatterInterface
{
    protected const KEY_HEALTH_CHECK_SERVICES = 'services';

    /**
     * @var \Spryker\Glue\HealthCheckRestApi\HealthCheckRestApiConfig
     */
    protected $healthCheckRestApiConfig;

    /**
     * @param \Spryker\Glue\HealthCheckRestApi\HealthCheckRestApiConfig $healthCheckRestApiConfig
     */
    public function __construct(HealthCheckRestApiConfig $healthCheckRestApiConfig)
    {
        $this->healthCheckRestApiConfig = $healthCheckRestApiConfig;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\HealthCheckRequestTransfer
     */
    public function getHealthCheckRequestTransfer(RestRequestInterface $restRequest): HealthCheckRequestTransfer
    {
        $requestedServices = $restRequest->getHttpRequest()->query->get(static::KEY_HEALTH_CHECK_SERVICES);

        $healthCheckRequestTransfer = (new HealthCheckRequestTransfer())
            ->setAvailableServices($this->healthCheckRestApiConfig->getAvailableServiceNames());

        if ($requestedServices !== null) {
            $healthCheckRequestTransfer->setRequestedServices(explode(',', $requestedServices));
        }

        return $healthCheckRequestTransfer;
    }
}
