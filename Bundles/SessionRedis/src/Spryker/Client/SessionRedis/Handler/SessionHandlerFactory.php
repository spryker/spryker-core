<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SessionRedis\Handler;

use Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceInterface;
use Spryker\Shared\SessionRedis\Handler\AbstractSessionHandlerFactory;

class SessionHandlerFactory extends AbstractSessionHandlerFactory
{
    /**
     * @var \Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceInterface
     */
    protected $monitoringService;

    /**
     * @var int
     */
    protected $sessionLifetime;

    /**
     * @param \Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceInterface $monitoringService
     * @param int $sessionLifetime
     */
    public function __construct(SessionRedisToMonitoringServiceInterface $monitoringService, int $sessionLifetime)
    {
        $this->monitoringService = $monitoringService;
        $this->sessionLifetime = $sessionLifetime;
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Dependency\Service\SessionRedisToMonitoringServiceInterface
     */
    protected function getMonitoringService(): SessionRedisToMonitoringServiceInterface
    {
        return $this->monitoringService;
    }

    /**
     * @return int
     */
    protected function getSessionLifetime(): int
    {
        return $this->sessionLifetime;
    }
}
