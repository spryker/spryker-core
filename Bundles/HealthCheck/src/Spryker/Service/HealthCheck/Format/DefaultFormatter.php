<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\HealthCheck\Format;

use Generated\Shared\Transfer\HealthCheckResponseTransfer;
use Spryker\Service\HealthCheck\HealthCheckConfig;

class DefaultFormatter implements FormatterInterface
{
    protected const FORMAT_NAME = 'default';

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
     * @return string
     */
    public function getFormatName(): string
    {
        return static::FORMAT_NAME;
    }

    /**
     * @param \Generated\Shared\Transfer\HealthCheckResponseTransfer $healthCheckResponseTransfer
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function formatMessage(HealthCheckResponseTransfer $healthCheckResponseTransfer): HealthCheckResponseTransfer
    {
        foreach ($healthCheckResponseTransfer->getHealthCheckServiceResponses() as $healthCheckServiceResponseTransfer) {
            if ($healthCheckServiceResponseTransfer->getStatus() === false) {
                $healthCheckResponseTransfer
                    ->setStatusCode($this->healthCheckConfig->getUnavailableHealthCheckStatusCode())
                    ->setStatus($this->healthCheckConfig->getUnavailableHealthCheckStatusMessage());
            }
        }

        return $healthCheckResponseTransfer;
    }
}
