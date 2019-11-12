<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Session;

use Spryker\Client\Session\SessionClientInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\Session\HealthIndicator\HealthIndicatorInterface;
use Spryker\Service\Session\HealthIndicator\YvesHealthIndicator;
use Spryker\Service\Session\HealthIndicator\ZedHealthIndicator;

class SessionServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\Session\HealthIndicator\HealthIndicatorInterface
     */
    public function createZedHealthCheckIndicator(): HealthIndicatorInterface
    {
        return new ZedHealthIndicator();
    }

    /**
     * @return \Spryker\Service\Session\HealthIndicator\HealthIndicatorInterface
     */
    public function createYvesHealthCheckIndicator(): HealthIndicatorInterface
    {
        return new YvesHealthIndicator(
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
