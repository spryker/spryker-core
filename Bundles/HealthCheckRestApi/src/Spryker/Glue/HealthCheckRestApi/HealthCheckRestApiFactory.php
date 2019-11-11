<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\HealthCheckRestApi;

use Spryker\Glue\HealthCheckRestApi\Dependency\Service\HealthCheckRestApiToHealthCheckServiceInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class HealthCheckRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\HealthCheckRestApi\Dependency\Service\HealthCheckRestApiToHealthCheckServiceInterface
     */
    public function getCheckoutService(): HealthCheckRestApiToHealthCheckServiceInterface
    {
        return $this->getProvidedDependency(HealthCheckRestApiDependencyProvider::SERVICE_HEALTH_CHECK);
    }
}
