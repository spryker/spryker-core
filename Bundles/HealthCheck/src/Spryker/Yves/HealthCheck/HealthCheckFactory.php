<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\HealthCheck;

use Spryker\Client\HealthCheck\HealthCheckClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * @method \Spryker\Yves\HealthCheck\HealthCheckConfig getConfig()
 */
class HealthCheckFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\HealthCheck\HealthCheckClientInterface
     */
    public function getHealthCheckClient(): HealthCheckClientInterface
    {
        return $this->getProvidedDependency(HealthCheckDependencyProvider::CLIENT_HEALTH_CHECK);
    }
}
