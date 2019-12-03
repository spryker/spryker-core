<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\HealthCheck\Processor;

use Generated\Shared\Transfer\HealthCheckResponseTransfer;
use Spryker\Service\HealthCheck\HealthCheckConfig;

class ResponseProcessor implements ResponseProcessorInterface
{
    /**
     * @var \Spryker\Service\HealthCheck\HealthCheckConfig
     */
    protected $healthCheckConfig;

    /**
     * @param \Spryker\Service\HealthCheck\HealthCheckConfig $healthCheckConfig
     */
    public function __construct(HealthCheckConfig $healthCheckConfig)
    {
        $this->healthCheckConfig = $healthCheckConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer[] $healthCheckServiceResponseTransfers
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function processOutput(array $healthCheckServiceResponseTransfers): HealthCheckResponseTransfer
    {
        if ($this->healthCheckConfig->isHealthCheckEnabled() === false) {
            return $this->createForbiddenHealthCheckResponseTransfer();
        }

        $healthCheckResponseTransfer = $this->createSuccessHealthCheckResponseTransfer($healthCheckServiceResponseTransfers);
        $healthCheckResponseTransfer = $this->validateGeneralSystemStatus($healthCheckResponseTransfer);

        return $healthCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HealthCheckResponseTransfer $healthCheckResponseTransfer
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    protected function validateGeneralSystemStatus(
        HealthCheckResponseTransfer $healthCheckResponseTransfer
    ): HealthCheckResponseTransfer {
        foreach ($healthCheckResponseTransfer->getHealthCheckServiceResponses() as $healthCheckServiceResponseTransfer) {
            if ($healthCheckServiceResponseTransfer->getStatus() === false) {
                return $this->updateHealthCheckResponseTransferWithUnavailableHealthCheckStatus($healthCheckResponseTransfer);
            }
        }

        return $healthCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer[] $healthCheckServiceResponseTransfers
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    protected function createSuccessHealthCheckResponseTransfer(array $healthCheckServiceResponseTransfers): HealthCheckResponseTransfer
    {
        $healthCheckResponseTransfer = (new HealthCheckResponseTransfer())
            ->setStatus($this->healthCheckConfig->getSuccessHealthCheckStatusMessage())
            ->setStatusCode($this->healthCheckConfig->getSuccessHealthCheckStatusCode());

        foreach ($healthCheckServiceResponseTransfers as $healthCheckServiceResponseTransfer) {
            $healthCheckResponseTransfer->addHealthCheckServiceResponse($healthCheckServiceResponseTransfer);
        }

        return $healthCheckResponseTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    protected function createForbiddenHealthCheckResponseTransfer(): HealthCheckResponseTransfer
    {
        return (new HealthCheckResponseTransfer())
            ->setStatus($this->healthCheckConfig->getUnavailableHealthCheckStatusMessage())
            ->setStatusCode($this->healthCheckConfig->getForbiddenHealthCheckStatusCode())
            ->setMessage($this->healthCheckConfig->getForbiddenHealthCheckStatusMessage());
    }

    /**
     * @param \Generated\Shared\Transfer\HealthCheckResponseTransfer $healthCheckResponseTransfer
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    protected function updateHealthCheckResponseTransferWithUnavailableHealthCheckStatus(HealthCheckResponseTransfer $healthCheckResponseTransfer): HealthCheckResponseTransfer
    {
        return $healthCheckResponseTransfer
            ->setStatusCode($this->healthCheckConfig->getUnavailableHealthCheckStatusCode())
            ->setStatus($this->healthCheckConfig->getUnavailableHealthCheckStatusMessage());
    }
}
