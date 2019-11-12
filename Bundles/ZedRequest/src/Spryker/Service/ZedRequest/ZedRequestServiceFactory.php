<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ZedRequest;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\ZedRequest\Dependency\Client\ZedRequestToHealthCheckClientInterface;
use Spryker\Service\ZedRequest\HealthIndicator\HealthIndicator;
use Spryker\Service\ZedRequest\HealthIndicator\HealthIndicatorInterface;

class ZedRequestServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\ZedRequest\HealthIndicator\HealthIndicatorInterface
     */
    public function createZedRequestHealthIndicator(): HealthIndicatorInterface
    {
        return new HealthIndicator(
            $this->getHealthCheckClient()
        );
    }

    /**
     * @return \Spryker\Service\ZedRequest\Dependency\Client\ZedRequestToHealthCheckClientInterface
     */
    public function getHealthCheckClient(): ZedRequestToHealthCheckClientInterface
    {
        return $this->getProvidedDependency(ZedRequestDependencyProvider::CLIENT_HEALTH_CHECK);
    }
}
