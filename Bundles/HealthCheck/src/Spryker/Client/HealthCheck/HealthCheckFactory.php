<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\HealthCheck;

use Spryker\Client\HealthCheck\Dependency\Client\HealthCheckToZedRequestClientInterface;
use Spryker\Client\HealthCheck\Zed\HealthCheckZedStub;
use Spryker\Client\HealthCheck\Zed\HealthCheckZedStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

class HealthCheckFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\HealthCheck\Zed\HealthCheckZedStubInterface
     */
    public function createHealthCheckZedStub(): HealthCheckZedStubInterface
    {
        return new HealthCheckZedStub(
            $this->getZedRequestClient()
        );
    }

    /**
     * @return \Spryker\Client\HealthCheck\Dependency\Client\HealthCheckToZedRequestClientInterface
     */
    public function getZedRequestClient(): HealthCheckToZedRequestClientInterface
    {
        return $this->getProvidedDependency(HealthCheckDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
