<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\HealthCheck\Processor;

use Generated\Shared\Transfer\HealthCheckResponseTransfer;
use Spryker\Client\HealthCheck\HealthCheckConfig;
use Spryker\Shared\HealthCheck\ChainFilter\ChainFilterInterface;
use Spryker\Shared\HealthCheck\Processor\AbstractHealthCheckProcessor;

class HealthCheckProcessor extends AbstractHealthCheckProcessor
{
    /**
     * @var \Spryker\Client\HealthCheck\HealthCheckConfig
     */
    protected $healthCheckConfig;

    /**
     * @param \Spryker\Shared\HealthCheck\ChainFilter\ChainFilterInterface $chainFilter
     * @param \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[] $healthCheckPlugins
     * @param \Spryker\Client\HealthCheck\HealthCheckConfig $healthCheckConfig
     */
    public function __construct(
        ChainFilterInterface $chainFilter,
        array $healthCheckPlugins,
        HealthCheckConfig $healthCheckConfig
    ) {
        parent::__construct($chainFilter, $healthCheckPlugins);
        $this->healthCheckConfig = $healthCheckConfig;
    }

    /**
     * @return bool
     */
    protected function isHealthCheckEnabled(): bool
    {
        return $this->healthCheckConfig->isHealthCheckEnabled();
    }

    /**
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    protected function createSuccessHealthCheckResponseTransfer(): HealthCheckResponseTransfer
    {
        return (new HealthCheckResponseTransfer())
            ->setStatus($this->healthCheckConfig->getSuccessHealthCheckStatusMessage())
            ->setStatusCode($this->healthCheckConfig->getSuccessHealthCheckStatusCode());
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
