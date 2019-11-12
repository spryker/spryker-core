<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\HealthCheck\Processor;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Generated\Shared\Transfer\HealthCheckResponseTransfer;
use Spryker\Service\HealthCheck\Filter\Service\ServiceFilterInterface;
use Spryker\Service\HealthCheck\HealthCheckConfig;

class HealthCheckServiceProcessor implements HealthCheckServiceProcessorInterface
{
    protected const SUCCESS_STATUS = 200;
    protected const FORBIDDEN_STATUS = 403;

    /**
     * @var \Spryker\Service\HealthCheck\Filter\Service\ServiceFilterInterface
     */
    protected $serviceFilter;

    /**
     * @var \Spryker\Service\HealthCheck\HealthCheckConfig
     */
    protected $healthCheckConfig;

    /**
     * @param \Spryker\Service\HealthCheck\Filter\Service\ServiceFilterInterface $serviceFilter
     * @param \Spryker\Service\HealthCheck\HealthCheckConfig $healthCheckConfig
     */
    public function __construct(ServiceFilterInterface $serviceFilter, HealthCheckConfig $healthCheckConfig)
    {
        $this->serviceFilter = $serviceFilter;
        $this->healthCheckConfig = $healthCheckConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function process(HealthCheckRequestTransfer $healthCheckRequestTransfer): HealthCheckResponseTransfer
    {
        $filteredHealthCheckPlugins = $this->serviceFilter->filter($healthCheckRequestTransfer);
        $healthCheckResponseTransfer = (new HealthCheckResponseTransfer())
            ->setStatus(static::SUCCESS_STATUS);

        if ($this->healthCheckConfig->isHealthCheckEnabled() === false) {
            return $healthCheckResponseTransfer
                ->setStatus(static::FORBIDDEN_STATUS);
        }

        foreach ($filteredHealthCheckPlugins as $filteredHealthCheckPlugin) {
            $healthCheckServiceResponseTransfer = $filteredHealthCheckPlugin->check();
            $healthCheckServiceResponseTransfer->setName($filteredHealthCheckPlugin->getName());
            $healthCheckResponseTransfer->addHealthCheckServiceResponse($healthCheckServiceResponseTransfer);
        }

        return $healthCheckResponseTransfer;
    }
}
