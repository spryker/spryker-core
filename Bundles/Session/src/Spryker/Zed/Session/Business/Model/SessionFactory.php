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
     * @var int
     */
    protected $sessionLifeTime;

    /**
     * @var string
     */
    protected $environmentName;

    /**
     * @var \Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface
     */
    protected $monitoringService;

    /**
     * @param int $sessionLifeTime
     * @param string $environmentName
     * @param \Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface $monitoringService
     */
    public function __construct(int $sessionLifeTime, string $environmentName, SessionToMonitoringServiceInterface $monitoringService)
    {
        $this->sessionLifeTime = $sessionLifeTime;
        $this->environmentName = $environmentName;
        $this->monitoringService = $monitoringService;
    }

    /**
     * @return int
     */
    public function getSessionLifetime(): int
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
