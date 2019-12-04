<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Session;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Session\HealthCheck\HealthCheckInterface;
use Spryker\Client\Session\HealthCheck\SessionHealthCheck;

class SessionFactory extends AbstractFactory
{
    /**
     * @param \Spryker\Client\Session\SessionClientInterface $sessionClient
     *
     * @return \Spryker\Client\Session\HealthCheck\HealthCheckInterface
     */
    public function createSessionHealthChecker(): HealthCheckInterface
    {
        return new SessionHealthCheck(
            $this->getSessionClient()
        );
    }

    /**
     * @return \Spryker\Client\Session\SessionClientInterface
     */
    public function getSessionClient(): SessionClientInterface
    {
        return $this->getProvidedDependency(SessionDependencyProvider::CLIENT_SESSION);
    }
}
