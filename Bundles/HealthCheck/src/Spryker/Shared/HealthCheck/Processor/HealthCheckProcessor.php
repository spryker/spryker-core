<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\HealthCheck\Processor;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Generated\Shared\Transfer\HealthCheckResponseTransfer;
use Spryker\Shared\HealthCheck\ChainFilter\ChainFilterInterface;
use Spryker\Shared\HealthCheck\Validator\ValidatorInterface;

class HealthCheckProcessor implements HealthCheckProcessorInterface
{
    /**
     * @var \Spryker\Shared\HealthCheck\Validator\ValidatorInterface
     */
    protected $validator;

    /**
     * @var \Spryker\Shared\HealthCheck\ChainFilter\ChainFilterInterface
     */
    protected $chainFilter;

    /**
     * @var \Spryker\Shared\HealthCheck\Processor\ResponseProcessorInterface
     */
    protected $responseProcessor;

    /**
     * @var \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected $healthCheckPlugins;

    /**
     * @param \Spryker\Shared\HealthCheck\Validator\ValidatorInterface $validator
     * @param \Spryker\Shared\HealthCheck\ChainFilter\ChainFilterInterface $chainFilter
     * @param \Spryker\Shared\HealthCheck\Processor\ResponseProcessorInterface $responseProcessor
     * @param \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[] $healthCheckPlugins
     */
    public function __construct(
        ValidatorInterface $validator,
        ChainFilterInterface $chainFilter,
        ResponseProcessorInterface $responseProcessor,
        array $healthCheckPlugins
    ) {
        $this->validator = $validator;
        $this->chainFilter = $chainFilter;
        $this->responseProcessor = $responseProcessor;
        $this->healthCheckPlugins = $healthCheckPlugins;
    }

    /**
     * @param string|null $requestedServices
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function process(?string $requestedServices = null): HealthCheckResponseTransfer
    {
        $healthCheckRequestTransfer = $this->createHealthCheckRequestTransfer($requestedServices);
        $isValidRequestedServices = $this->validator->validate($this->healthCheckPlugins, $healthCheckRequestTransfer);

        if ($isValidRequestedServices === false) {
            return $this->responseProcessor->processNonExistingServiceName();
        }

        $filteredHealthCheckPlugins = $this->chainFilter->filter($this->healthCheckPlugins, $healthCheckRequestTransfer);
        $healthCheckResponseTransfer = $this->processFilteredHealthCheckPlugins($filteredHealthCheckPlugins);

        return $healthCheckResponseTransfer;
    }

    /**
     * @param string|null $requestedServices
     *
     * @return \Generated\Shared\Transfer\HealthCheckRequestTransfer
     */
    protected function createHealthCheckRequestTransfer(?string $requestedServices = null): HealthCheckRequestTransfer
    {
        $healthCheckRequestTransfer = new HealthCheckRequestTransfer();

        if ($requestedServices === null) {
            return $healthCheckRequestTransfer;
        }

        return $healthCheckRequestTransfer
            ->setRequestedServices(explode(',', $requestedServices));
    }

    /**
     * @param \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[] $filteredHealthCheckPlugins
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    protected function processFilteredHealthCheckPlugins(array $filteredHealthCheckPlugins): HealthCheckResponseTransfer
    {
        $healthCheckServiceResponseTransfers = [];

        foreach ($filteredHealthCheckPlugins as $filteredHealthCheckPlugin) {
            $healthCheckServiceResponseTransfer = $filteredHealthCheckPlugin->check();
            $healthCheckServiceResponseTransfer->setName($filteredHealthCheckPlugin->getName());
            $healthCheckServiceResponseTransfers[] = $healthCheckServiceResponseTransfer;
        }

        return $this->responseProcessor->processOutput($healthCheckServiceResponseTransfers);
    }
}
