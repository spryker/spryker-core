<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\HealthCheck\Processor;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Generated\Shared\Transfer\HealthCheckResponseTransfer;
use Spryker\Service\HealthCheck\HealthCheckServiceInterface;
use Spryker\Shared\HealthCheck\ChainFilter\ChainFilterInterface;

class HealthCheckProcessor implements HealthCheckProcessorInterface
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
     * @var \Spryker\Service\HealthCheck\HealthCheckServiceInterface
     */
    protected $healthCheckService;

    /**
     * @param \Spryker\Shared\HealthCheck\ChainFilter\ChainFilterInterface $chainFilter
     * @param \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[] $healthCheckPlugins
     * @param \Spryker\Service\HealthCheck\HealthCheckServiceInterface $healthCheckService
     */
    public function __construct(ChainFilterInterface $chainFilter, array $healthCheckPlugins, HealthCheckServiceInterface $healthCheckService)
    {
        $this->chainFilter = $chainFilter;
        $this->healthCheckPlugins = $healthCheckPlugins;
        $this->healthCheckService = $healthCheckService;
    }

    /**
     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function process(HealthCheckRequestTransfer $healthCheckRequestTransfer): HealthCheckResponseTransfer
    {
        $filteredHealthCheckPlugins = $this->chainFilter->filter($this->healthCheckPlugins, $healthCheckRequestTransfer);
        $healthCheckResponseTransfer = $this->processFilteredHealthCheckPlugins($filteredHealthCheckPlugins);

        return $healthCheckResponseTransfer;
    }

    /**
     * @param \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[] $filteredHealthCheckPlugins
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    protected function processFilteredHealthCheckPlugins(array $filteredHealthCheckPlugins): HealthCheckResponseTransfer
    {
        $healthCheckServiceResponseTransfers = [];

        foreach ($filteredHealthCheckPlugins as $filteredHealthCheckServiceName => $filteredHealthCheckPlugin) {
            $healthCheckServiceResponseTransfer = $filteredHealthCheckPlugin->check();
            $healthCheckServiceResponseTransfer->setName($filteredHealthCheckServiceName);
            $healthCheckServiceResponseTransfers[] = $healthCheckServiceResponseTransfer;
        }

        return $this->healthCheckService->processOutput($healthCheckServiceResponseTransfers);
    }
}
