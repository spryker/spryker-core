<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\HealthCheck\Processor;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Generated\Shared\Transfer\HealthCheckResponseTransfer;
use Spryker\Service\HealthCheck\Filter\Service\ServiceFilterInterface;
use Spryker\Service\HealthCheck\Format\Encoder\FormatEncoderInterface;
use Spryker\Service\HealthCheck\HealthCheckConfig;

class HealthCheckServiceProcessor implements HealthCheckServiceProcessorInterface
{
    /**
     * @var \Spryker\Service\HealthCheck\Filter\Service\ServiceFilterInterface
     */
    protected $serviceFilter;

    /**
     * @var \Spryker\Service\HealthCheck\Format\Encoder\FormatEncoderInterface
     */
    protected $formatEncoder;

    /**
     * @var \Spryker\Service\HealthCheck\HealthCheckConfig
     */
    protected $healthCheckConfig;

    /**
     * @param \Spryker\Service\HealthCheck\Filter\Service\ServiceFilterInterface $serviceFilter
     * @param \Spryker\Service\HealthCheck\Format\Encoder\FormatEncoderInterface $formatEncoder
     * @param \Spryker\Service\HealthCheck\HealthCheckConfig $healthCheckConfig
     */
    public function __construct(
        ServiceFilterInterface $serviceFilter,
        FormatEncoderInterface $formatEncoder,
        HealthCheckConfig $healthCheckConfig
    ) {
        $this->serviceFilter = $serviceFilter;
        $this->formatEncoder = $formatEncoder;
        $this->healthCheckConfig = $healthCheckConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function process(HealthCheckRequestTransfer $healthCheckRequestTransfer): HealthCheckResponseTransfer
    {
        $healthCheckResponseTransfer = (new HealthCheckResponseTransfer())
            ->setStatus($this->healthCheckConfig->getSuccessHealthCheckStatusMessage())
            ->setStatusCode($this->healthCheckConfig->getSuccessHealthCheckStatusCode());

        if ($this->healthCheckConfig->isHealthCheckEnabled() === false) {
            return $healthCheckResponseTransfer
                ->setStatus($this->healthCheckConfig->getUnavailableHealthCheckStatusMessage())
                ->setStatusCode($this->healthCheckConfig->getForbiddenHealthCheckStatusCode())
                ->setMessage($this->healthCheckConfig->getForbiddenHealthCheckStatusMessage());
        }

        $filteredHealthCheckPlugins = $this->serviceFilter->filter($healthCheckRequestTransfer);
        $healthCheckResponseTransfer = $this->processFilteredHealthCheckPlugins($filteredHealthCheckPlugins, $healthCheckResponseTransfer);
        $healthCheckResponseTransfer = $this->processOutputFormat($healthCheckRequestTransfer, $healthCheckResponseTransfer);

        return $healthCheckResponseTransfer;
    }

    /**
     * @param \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[] $filteredHealthCheckPlugins
     * @param \Generated\Shared\Transfer\HealthCheckResponseTransfer $healthCheckResponseTransfer
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    protected function processFilteredHealthCheckPlugins(array $filteredHealthCheckPlugins, HealthCheckResponseTransfer $healthCheckResponseTransfer): HealthCheckResponseTransfer
    {
        foreach ($filteredHealthCheckPlugins as $filteredHealthCheckPlugin) {
            $healthCheckServiceResponseTransfer = $filteredHealthCheckPlugin->check();
            $healthCheckServiceResponseTransfer->setName($filteredHealthCheckPlugin->getName());
            $healthCheckResponseTransfer->addHealthCheckServiceResponse($healthCheckServiceResponseTransfer);
        }

        return $healthCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
     * @param \Generated\Shared\Transfer\HealthCheckResponseTransfer $healthCheckResponseTransfer
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    protected function processOutputFormat(HealthCheckRequestTransfer $healthCheckRequestTransfer, HealthCheckResponseTransfer $healthCheckResponseTransfer): HealthCheckResponseTransfer
    {
        $outputFormat = $healthCheckRequestTransfer->getFormat() ?? $this->healthCheckConfig->getDefaultFormatterName();

        return $this->formatEncoder->encode($healthCheckResponseTransfer, $outputFormat);
    }
}
