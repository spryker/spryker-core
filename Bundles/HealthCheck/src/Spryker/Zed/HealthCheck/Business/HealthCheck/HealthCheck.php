<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\HealthCheck\Business\HealthCheck;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Generated\Shared\Transfer\HealthCheckResponseTransfer;
use Spryker\Shared\HealthCheck\Processor\HealthCheckProcessorInterface;
use Spryker\Zed\HealthCheck\HealthCheckConfig;

class HealthCheck implements HealthCheckInterface
{
    /**
     * @var \Spryker\Shared\HealthCheck\Processor\HealthCheckProcessorInterface
     */
    protected $healthCheckProcessor;

    /**
     * @var \Spryker\Zed\HealthCheck\HealthCheckConfig
     */
    protected $healthCheckConfig;

    /**
     * @param \Spryker\Shared\HealthCheck\Processor\HealthCheckProcessorInterface $healthCheckProcessor
     * @param \Spryker\Zed\HealthCheck\HealthCheckConfig $healthCheckConfig
     */
    public function __construct(HealthCheckProcessorInterface $healthCheckProcessor, HealthCheckConfig $healthCheckConfig)
    {
        $this->healthCheckProcessor = $healthCheckProcessor;
        $this->healthCheckConfig = $healthCheckConfig;
    }

    /**
     * @param string|null $requestedServices
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function executeHealthCheck(?string $requestedServices = null): HealthCheckResponseTransfer
    {
        $healthCheckRequestTransfer = (new HealthCheckRequestTransfer())
            ->setAvailableServices($this->healthCheckConfig->getAvailableHealthCheckServices());

        if ($requestedServices !== null) {
            $healthCheckRequestTransfer->setRequestedServices(explode(',', $requestedServices));
        }

        $healthCheckRequestTransfer = $this->healthCheckProcessor->process($healthCheckRequestTransfer);

        return $healthCheckRequestTransfer;
    }
}
