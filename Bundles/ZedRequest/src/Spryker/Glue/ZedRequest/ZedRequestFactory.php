<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ZedRequest;

use Spryker\Client\ZedRequest\ZedRequestClientInterface;
use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ZedRequest\HealthCheck\HealthCheckInterface;
use Spryker\Glue\ZedRequest\HealthCheck\ZedRequestHealthCheck;

/**
 * @method \Spryker\Glue\ZedRequest\ZedRequestConfig getConfig()
 */
class ZedRequestFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ZedRequest\HealthCheck\HealthCheckInterface
     */
    public function createZedRequestHealthChecker(): HealthCheckInterface
    {
        return new ZedRequestHealthCheck(
            $this->getZedRequestClient()
        );
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    public function getZedRequestClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(ZedRequestDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
