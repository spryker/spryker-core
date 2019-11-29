<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\HealthCheck\ConfigurationProvider;

interface ConfigurationProviderInterface
{
    /**
     * @return bool
     */
    public function isHealthCheckEnabled(): bool;

    /**
     * @return int
     */
    public function getSuccessHealthCheckStatusCode(): int;

    /**
     * @return string
     */
    public function getSuccessHealthCheckStatusMessage(): string;

    /**
     * @return int
     */
    public function getUnavailableHealthCheckStatusCode(): int;

    /**
     * @return string
     */
    public function getUnavailableHealthCheckStatusMessage(): string;

    /**
     * @return int
     */
    public function getForbiddenHealthCheckStatusCode(): int;

    /**
     * @return string
     */
    public function getForbiddenHealthCheckStatusMessage(): string;
}
