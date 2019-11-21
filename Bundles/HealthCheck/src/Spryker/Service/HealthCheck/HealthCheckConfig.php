<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\HealthCheck;

use Spryker\Service\Kernel\AbstractBundleConfig;
use Spryker\Shared\HealthCheck\HealthCheckConstants;

/**
 * @method \Spryker\Shared\HealthCheck\HealthCheckConfig getSharedConfig()
 */
class HealthCheckConfig extends AbstractBundleConfig
{
    protected const DEFAULT_FORMATTER_NAME = 'default';

    /**
     * @return bool
     */
    public function isHealthCheckEnabled(): bool
    {
        return $this->get(HealthCheckConstants::HEALTH_CHECK_ENABLED, true);
    }

    /**
     * @return int
     */
    public function getSuccessHealthCheckStatusCode(): int
    {
        return $this->getSharedConfig()->getSuccessHealthCheckStatusCode();
    }

    /**
     * @return int
     */
    public function getForbiddenHealthCheckStatusCode(): int
    {
        return $this->getSharedConfig()->getForbiddenHealthCheckStatusCode();
    }

    /**
     * @return string
     */
    public function getForbiddenHealthCheckStatusMessage(): string
    {
        return $this->getSharedConfig()->getForbiddenHealthCheckStatusMessage();
    }

    /**
     * @return int
     */
    public function getUnavailableHealthCheckStatusCode(): int
    {
        return $this->getSharedConfig()->getUnavailableHealthCheckStatusCode();
    }

    /**
     * @return string
     */
    public function getSuccessHealthCheckStatusMessage(): string
    {
        return $this->getSharedConfig()->getSuccessHealthCheckStatusMessage();
    }

    /**
     * @return string
     */
    public function getUnavailableHealthCheckStatusMessage(): string
    {
        return $this->getSharedConfig()->getUnavailableHealthCheckStatusMessage();
    }

    /**
     * @return string
     */
    public function getDefaultFormatterName(): string
    {
        return static::DEFAULT_FORMATTER_NAME;
    }
}
