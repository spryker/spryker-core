<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\HealthCheck\Processor;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Generated\Shared\Transfer\HealthCheckResponseTransfer;
use Spryker\Shared\HealthCheck\ChainFilter\ChainFilterInterface;

abstract class AbstractHealthCheckProcessor implements HealthCheckProcessorInterface
{
    /**
     * @var \Spryker\Shared\HealthCheck\ChainFilter\ChainFilterInterface
     */
    protected $chainFilter;

    /**
     * @var \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected $healthCheckPlugins;

    /**
     * @param \Spryker\Shared\HealthCheck\ChainFilter\ChainFilterInterface $chainFilter
     * @param \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[] $healthCheckPlugins
     */
    public function __construct(ChainFilterInterface $chainFilter, array $healthCheckPlugins)
    {
        $this->chainFilter = $chainFilter;
        $this->healthCheckPlugins = $healthCheckPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function process(HealthCheckRequestTransfer $healthCheckRequestTransfer): HealthCheckResponseTransfer
    {
        if ($this->isHealthCheckEnabled() === false) {
            return $this->createForbiddenHealthCheckResponseTransfer();
        }

        $filteredHealthCheckPlugins = $this->chainFilter->filter($this->healthCheckPlugins, $healthCheckRequestTransfer);
        $healthCheckResponseTransfer = $this->processFilteredHealthCheckPlugins($filteredHealthCheckPlugins);
        $healthCheckResponseTransfer = $this->validateGeneralSystemStatus($healthCheckResponseTransfer);

        return $healthCheckResponseTransfer;
    }

    /**
     * @param \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[] $filteredHealthCheckPlugins
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    protected function processFilteredHealthCheckPlugins(array $filteredHealthCheckPlugins): HealthCheckResponseTransfer
    {
        $healthCheckResponseTransfer = $this->createSuccessHealthCheckResponseTransfer();

        foreach ($filteredHealthCheckPlugins as $filteredHealthCheckPlugin) {
            $healthCheckServiceResponseTransfer = $filteredHealthCheckPlugin->check();
            $healthCheckServiceResponseTransfer->setName($filteredHealthCheckPlugin->getName());
            $healthCheckResponseTransfer->addHealthCheckServiceResponse($healthCheckServiceResponseTransfer);
        }

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
     * @return bool
     */
    abstract protected function isHealthCheckEnabled(): bool;

    /**
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    abstract protected function createForbiddenHealthCheckResponseTransfer(): HealthCheckResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    abstract protected function createSuccessHealthCheckResponseTransfer(): HealthCheckResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\HealthCheckResponseTransfer $healthCheckResponseTransfer
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    abstract protected function updateHealthCheckResponseTransferWithUnavailableHealthCheckStatus(HealthCheckResponseTransfer $healthCheckResponseTransfer): HealthCheckResponseTransfer;
}
