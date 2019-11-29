<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Session;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\Session\Business\HealthCheckIndicator\HealthCheckIndicatorInterface;
use Spryker\Shared\Session\Business\HealthCheckIndicator\SessionHealthCheckIndicator;

class SessionFactory extends AbstractFactory
{
    /**
     * @param \Spryker\Client\Session\SessionClientInterface $sessionClient
     *
     * @return \Spryker\Shared\Session\Business\HealthCheckIndicator\HealthCheckIndicatorInterface
     */
    public function createSessionHealthCheckIndicator(SessionClientInterface $sessionClient): HealthCheckIndicatorInterface
    {
        return new SessionHealthCheckIndicator($sessionClient);
    }
}
