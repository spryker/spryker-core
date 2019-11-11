<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\HealthCheck\Processor;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Generated\Shared\Transfer\HealthCheckResponseTransfer;
use Spryker\Service\HealthCheck\Filter\Service\ServiceFilterInterface;

class HealthCheckServiceProcessor implements HealthCheckServiceProcessorInterface
{
    /**
     * @var \Spryker\Service\HealthCheck\Filter\Service\ServiceFilterInterface
     */
    protected $serviceFilter;

    /**
     * @param \Spryker\Service\HealthCheck\Filter\Service\ServiceFilterInterface
     */
    public function __construct(ServiceFilterInterface $serviceFilter)
    {
        $this->serviceFilter = $serviceFilter;
    }

    /**
     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function process(HealthCheckRequestTransfer $healthCheckRequestTransfer): HealthCheckResponseTransfer
    {
        $filteredHealthCheckPlugins = $this->serviceFilter->filter($healthCheckRequestTransfer);
        $healthCheckResponseTransfer = new HealthCheckResponseTransfer();

        foreach ($filteredHealthCheckPlugins as $filteredHealthCheckPlugin) {
            $healthCheckServiceResponseTransfer = $filteredHealthCheckPlugin->check();
            $healthCheckResponseTransfer
                ->addHealthCheckServiceResponse($healthCheckServiceResponseTransfer);
        }

        dump($healthCheckResponseTransfer); die;

        return $healthCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
     *
     * @return \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected function filterHealthCheckPluginsByApplication(HealthCheckRequestTransfer $healthCheckRequestTransfer): array
    {
        return $this->healthCheckPlugins[$healthCheckRequestTransfer->getApplication()];
    }
}
