<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\HealthCheck;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\HealthCheck\HealthCheckConfig getSharedConfig()
 */
class HealthCheckConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isHealthCheckEnabled(): bool
    {
        return $this->getSharedConfig()->isHealthCheckEnabled();
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
    public function getUnavailableHealthCheckStatusMessage(): string
    {
        return $this->getSharedConfig()->getUnavailableHealthCheckStatusMessage();
    }

    /**
     * @return int
     */
    public function getSuccessHealthCheckStatusCode(): int
    {
        return $this->getSharedConfig()->getSuccessHealthCheckStatusCode();
    }

    /**
     * @return string
     */
    public function getSuccessHealthCheckStatusMessage(): string
    {
        return $this->getSharedConfig()->getSuccessHealthCheckStatusMessage();
    }
}
