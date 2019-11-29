<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\HealthCheck\Processor;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Generated\Shared\Transfer\HealthCheckResponseTransfer;
use Spryker\Shared\HealthCheck\Processor\AbstractHealthCheckProcessor;
use Spryker\Shared\HealthCheck\Processor\HealthCheckProcessorInterface;

class HealthCheckProcessor extends AbstractHealthCheckProcessor implements HealthCheckProcessorInterface
{
    /**
     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function process(HealthCheckRequestTransfer $healthCheckRequestTransfer): HealthCheckResponseTransfer
    {
        if ($this->configurationProvider->isHealthCheckEnabled() === false) {
            return (new HealthCheckResponseTransfer())
                ->setStatus($this->configurationProvider->getUnavailableHealthCheckStatusMessage())
                ->setStatusCode($this->configurationProvider->getForbiddenHealthCheckStatusCode())
                ->setMessage($this->configurationProvider->getForbiddenHealthCheckStatusMessage());
        }

        $filteredHealthCheckPlugins = $this->serviceFilter->filter($healthCheckRequestTransfer);
        $healthCheckResponseTransfer = $this->processFilteredHealthCheckPlugins($filteredHealthCheckPlugins);
        $healthCheckResponseTransfer = $this->validateGeneralSystemStatus($healthCheckResponseTransfer);

        return $healthCheckResponseTransfer;
    }
}
