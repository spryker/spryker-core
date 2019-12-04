<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\HealthCheck;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class HealthCheckConfig extends AbstractSharedConfig
{
    protected const HEALTH_CHECK_SUCCESS_STATUS_CODE = 200;
    protected const HEALTH_CHECK_SUCCESS_STATUS_MESSAGE = 'healthy';

    protected const HEALTH_CHECK_UNAVAILABLE_STATUS_CODE = 503;
    protected const HEALTH_CHECK_UNAVAILABLE_STATUS_MESSAGE = 'unhealthy';

    protected const HEALTH_CHECK_FORBIDDEN_STATUS_CODE = 403;
    protected const HEALTH_CHECK_FORBIDDEN_STATUS_MESSAGE = 'HealthCheck endpoints are disabled for all applications.';

    protected const HEALTH_CHECK_BAD_REQUEST_STATUS_CODE = 400;
    protected const HEALTH_CHECK_BAD_REQUEST_STATUS_MESSAGE = 'Requested services not found.';

    /**
     * @return bool
     */
    public function isHealthCheckEnabled(): bool
    {
        return $this->get(HealthCheckConstants::HEALTH_CHECK_ENABLED, false);
    }

    /**
     * @return int
     */
    public function getSuccessHealthCheckStatusCode(): int
    {
        return static::HEALTH_CHECK_SUCCESS_STATUS_CODE;
    }

    /**
     * @return string
     */
    public function getSuccessHealthCheckStatusMessage(): string
    {
        return static::HEALTH_CHECK_SUCCESS_STATUS_MESSAGE;
    }

    /**
     * @return int
     */
    public function getUnavailableHealthCheckStatusCode(): int
    {
        return static::HEALTH_CHECK_UNAVAILABLE_STATUS_CODE;
    }

    /**
     * @return string
     */
    public function getUnavailableHealthCheckStatusMessage(): string
    {
        return static::HEALTH_CHECK_UNAVAILABLE_STATUS_MESSAGE;
    }

    /**
     * @return int
     */
    public function getForbiddenHealthCheckStatusCode(): int
    {
        return static::HEALTH_CHECK_FORBIDDEN_STATUS_CODE;
    }

    /**
     * @return string
     */
    public function getForbiddenHealthCheckStatusMessage(): string
    {
        return static::HEALTH_CHECK_FORBIDDEN_STATUS_MESSAGE;
    }

    /**
     * @return int
     */
    public function getBadRequestHealthCheckStatusCode(): int
    {
        return static::HEALTH_CHECK_BAD_REQUEST_STATUS_CODE;
    }

    /**
     * @return string
     */
    public function getBadRequestHealthCheckStatusMessage(): string
    {
        return static::HEALTH_CHECK_BAD_REQUEST_STATUS_MESSAGE;
    }
}
