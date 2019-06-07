<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Session\Model;

use Spryker\Shared\Session\Business\Model\SessionFactory;
use Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface;

class SessionHandlerFactory extends SessionFactory
{
    /**
     * @var int
     */
    protected $sessionLifeTime;

    /**
     * @var \Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface
     */
    protected $monitoringService;

    /**
     * @var string
     */
    protected $environmentName;

    /**
     * @param int $sessionLifeTime
     * @param \Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface $monitoringService
     * @param string $environmentName
     */
    public function __construct(int $sessionLifeTime, SessionToMonitoringServiceInterface $monitoringService, string $environmentName)
    {
        $this->sessionLifeTime = $sessionLifeTime;
        $this->monitoringService = $monitoringService;
        $this->environmentName = $environmentName;
    }

    /**
     * @return int
     */
    protected function getSessionLifetime(): int
    {
        return (int)$this->sessionLifeTime;
    }

    /**
     * @return \Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface
     */
    public function getMonitoringService(): SessionToMonitoringServiceInterface
    {
        return $this->monitoringService;
    }

    /**
     * @return string
     */
    protected function getEnvironmentName(): string
    {
        return $this->environmentName;
    }
}
