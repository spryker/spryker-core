<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Business\Model;

use Spryker\Shared\Session\Business\Model\SessionFactory as SharedSessionFactory;
use Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface;

class SessionFactory extends SharedSessionFactory
{
    /**
     * @var \Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface
     */
    protected $monitoringService;

    /**
     * @var int
     */
    protected $sessionLifeTime;

    /**
     * @var string
     */
    protected $environmentName;

    /**
     * @param \Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface $monitoringService
     * @param int $sessionLifeTime
     * @param string $environmentName
     */
    public function __construct(SessionToMonitoringServiceInterface $monitoringService, int $sessionLifeTime, string $environmentName)
    {
        $this->monitoringService = $monitoringService;
        $this->sessionLifeTime = $sessionLifeTime;
        $this->environmentName = $environmentName;
    }

    /**
     * @return int
     */
    public function getSessionLifetime()
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
