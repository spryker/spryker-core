<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\HealthCheck\ConfigurationProvider;

use Spryker\Client\HealthCheck\HealthCheckConfig;
use Spryker\Shared\HealthCheck\ConfigurationProvider\ConfigurationProviderInterface;

class ConfigurationProvider implements ConfigurationProviderInterface
{
    /**
     * @var \Spryker\Client\HealthCheck\HealthCheckConfig
     */
    protected $healthCheckConfig;

    /**
     * @param \Spryker\Client\HealthCheck\HealthCheckConfig $healthCheckConfig
     */
    public function __construct(HealthCheckConfig $healthCheckConfig)
    {
        $this->healthCheckConfig = $healthCheckConfig;
    }

    /**
     * @return bool
     */
    public function isHealthCheckEnabled(): bool
    {
        return $this->healthCheckConfig->isHealthCheckEnabled();
    }

    /**
     * @return int
     */
    public function getSuccessHealthCheckStatusCode(): int
    {
        return $this->healthCheckConfig->getSuccessHealthCheckStatusCode();
    }

    /**
     * @return string
     */
    public function getSuccessHealthCheckStatusMessage(): string
    {
        return $this->healthCheckConfig->getSuccessHealthCheckStatusMessage();
    }

    /**
     * @return int
     */
    public function getUnavailableHealthCheckStatusCode(): int
    {
        return $this->healthCheckConfig->getUnavailableHealthCheckStatusCode();
    }

    /**
     * @return string
     */
    public function getUnavailableHealthCheckStatusMessage(): string
    {
        return $this->healthCheckConfig->getUnavailableHealthCheckStatusMessage();
    }

    /**
     * @return int
     */
    public function getForbiddenHealthCheckStatusCode(): int
    {
        return $this->healthCheckConfig->getForbiddenHealthCheckStatusCode();
    }

    /**
     * @return string
     */
    public function getForbiddenHealthCheckStatusMessage(): string
    {
        return $this->healthCheckConfig->getForbiddenHealthCheckStatusMessage();
    }
}
