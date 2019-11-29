<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\HealthCheck\Processor;

use Generated\Shared\Transfer\HealthCheckResponseTransfer;
use Spryker\Shared\HealthCheck\ConfigurationProvider\ConfigurationProviderInterface;
use Spryker\Shared\HealthCheck\Filter\Service\ServiceFilterInterface;

abstract class AbstractHealthCheckProcessor
{
    /**
     * @var \Spryker\Shared\HealthCheck\Filter\Service\ServiceFilterInterface
     */
    protected $serviceFilter;

    /**
     * @var \Spryker\Shared\HealthCheck\ConfigurationProvider\ConfigurationProviderInterface
     */
    protected $configurationProvider;

    /**
     * @param \Spryker\Shared\HealthCheck\Filter\Service\ServiceFilterInterface $serviceFilter
     * @param \Spryker\Shared\HealthCheck\ConfigurationProvider\ConfigurationProviderInterface $configurationProvider
     */
    public function __construct(ServiceFilterInterface $serviceFilter, ConfigurationProviderInterface $configurationProvider)
    {
        $this->serviceFilter = $serviceFilter;
        $this->configurationProvider = $configurationProvider;
    }

    /**
     * @param \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[] $filteredHealthCheckPlugins
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    protected function processFilteredHealthCheckPlugins(array $filteredHealthCheckPlugins): HealthCheckResponseTransfer
    {
        $healthCheckResponseTransfer = (new HealthCheckResponseTransfer())
            ->setStatus($this->configurationProvider->getSuccessHealthCheckStatusMessage())
            ->setStatusCode($this->configurationProvider->getSuccessHealthCheckStatusCode());

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
                $healthCheckResponseTransfer
                    ->setStatusCode($this->configurationProvider->getUnavailableHealthCheckStatusCode())
                    ->setStatus($this->configurationProvider->getUnavailableHealthCheckStatusMessage());
            }
        }

        return $healthCheckResponseTransfer;
    }
}
