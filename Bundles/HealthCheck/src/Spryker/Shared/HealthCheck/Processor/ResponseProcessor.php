<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\HealthCheck\Processor;

use Generated\Shared\Transfer\HealthCheckResponseTransfer;
use Symfony\Component\HttpFoundation\Response;

class ResponseProcessor implements ResponseProcessorInterface
{
    public const HEALTH_CHECK_SUCCESS_STATUS_CODE = Response::HTTP_OK;
    public const HEALTH_CHECK_SUCCESS_STATUS_MESSAGE = 'healthy';

    public const HEALTH_CHECK_UNAVAILABLE_STATUS_CODE = Response::HTTP_SERVICE_UNAVAILABLE;
    public const HEALTH_CHECK_UNAVAILABLE_STATUS_MESSAGE = 'unhealthy';

    public const HEALTH_CHECK_FORBIDDEN_STATUS_CODE = Response::HTTP_FORBIDDEN;
    public const HEALTH_CHECK_FORBIDDEN_STATUS_MESSAGE = 'HealthCheck endpoints are disabled for all applications.';

    public const HEALTH_CHECK_BAD_REQUEST_STATUS_CODE = Response::HTTP_BAD_REQUEST;
    public const HEALTH_CHECK_BAD_REQUEST_STATUS_MESSAGE = 'Requested services not found.';

    /**
     * @var bool
     */
    protected $isHealthCheckEnabled;

    /**
     * @param bool $isHealthCheckEnabled
     */
    public function __construct(bool $isHealthCheckEnabled)
    {
        $this->isHealthCheckEnabled = $isHealthCheckEnabled;
    }

    /**
     * @param \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer[] $healthCheckServiceResponseTransfers
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function processOutput(array $healthCheckServiceResponseTransfers): HealthCheckResponseTransfer
    {
        if ($this->isHealthCheckEnabled === false) {
            return $this->createForbiddenHealthCheckResponseTransfer();
        }

        $healthCheckResponseTransfer = $this->createHealthCheckResponseTransfer($healthCheckServiceResponseTransfers);
        $healthCheckResponseTransfer = $this->processSystemStatus($healthCheckResponseTransfer);

        return $healthCheckResponseTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function processNonExistingServiceName(): HealthCheckResponseTransfer
    {
        return (new HealthCheckResponseTransfer())
            ->setMessage(static::HEALTH_CHECK_BAD_REQUEST_STATUS_MESSAGE)
            ->setStatusCode(static::HEALTH_CHECK_BAD_REQUEST_STATUS_CODE);
    }

    /**
     * @param \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer[] $healthCheckServiceResponseTransfers
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    protected function createHealthCheckResponseTransfer(array $healthCheckServiceResponseTransfers): HealthCheckResponseTransfer
    {
        $healthCheckResponseTransfer = (new HealthCheckResponseTransfer())
            ->setStatus(static::HEALTH_CHECK_SUCCESS_STATUS_MESSAGE)
            ->setStatusCode(static::HEALTH_CHECK_SUCCESS_STATUS_CODE);

        foreach ($healthCheckServiceResponseTransfers as $healthCheckServiceResponseTransfer) {
            $healthCheckResponseTransfer->addHealthCheckServiceResponse($healthCheckServiceResponseTransfer);
        }

        return $healthCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HealthCheckResponseTransfer $healthCheckResponseTransfer
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    protected function processSystemStatus(
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
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    protected function createForbiddenHealthCheckResponseTransfer(): HealthCheckResponseTransfer
    {
        return (new HealthCheckResponseTransfer())
            ->setStatusCode(static::HEALTH_CHECK_FORBIDDEN_STATUS_CODE)
            ->setMessage(static::HEALTH_CHECK_FORBIDDEN_STATUS_MESSAGE);
    }

    /**
     * @param \Generated\Shared\Transfer\HealthCheckResponseTransfer $healthCheckResponseTransfer
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    protected function updateHealthCheckResponseTransferWithUnavailableHealthCheckStatus(
        HealthCheckResponseTransfer $healthCheckResponseTransfer
    ): HealthCheckResponseTransfer {
        return $healthCheckResponseTransfer
            ->setStatusCode(static::HEALTH_CHECK_UNAVAILABLE_STATUS_CODE)
            ->setStatus(static::HEALTH_CHECK_UNAVAILABLE_STATUS_MESSAGE);
    }
}
