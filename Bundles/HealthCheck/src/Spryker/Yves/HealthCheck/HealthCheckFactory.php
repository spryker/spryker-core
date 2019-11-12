<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\HealthCheck;

use Spryker\Service\HealthCheck\HealthCheckServiceInterface;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * @method \Spryker\Yves\HealthCheck\HealthCheckConfig getConfig()
 */
class HealthCheckFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Service\HealthCheck\HealthCheckServiceInterface
     */
    public function getHealthCheckService(): HealthCheckServiceInterface
    {
        return $this->getProvidedDependency(HealthCheckDependencyProvider::SERVICE_HEALTH_CHECK);
    }
}
