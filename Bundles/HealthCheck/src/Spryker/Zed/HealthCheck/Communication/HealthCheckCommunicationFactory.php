<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\HealthCheck\Communication;

use Spryker\Service\HealthCheck\HealthCheckServiceInterface;
use Spryker\Zed\HealthCheck\HealthCheckDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\HealthCheck\HealthCheckConfig getConfig()
 */
class HealthCheckCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Service\HealthCheck\HealthCheckServiceInterface
     */
    public function getHealthCheckService(): HealthCheckServiceInterface
    {
        return $this->getProvidedDependency(HealthCheckDependencyProvider::SERVICE_HEALTH_CHECK);
    }
}
