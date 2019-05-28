<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Communication;

use Spryker\Shared\Session\Business\Model\SessionFactory;
use Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface;
use Spryker\Zed\Session\SessionConfig;

class SessionHandlerFactory extends SessionFactory
{
    /**
     * @var \Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface
     */
    protected $monitoringService;

    /**
     * @var \Spryker\Zed\Session\SessionConfig
     */
    protected $sessionConfig;

    /**
     * @param \Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface $monitoringService
     * @param \Spryker\Zed\Session\SessionConfig $sessionConfig
     */
    public function __construct(
        SessionToMonitoringServiceInterface $monitoringService,
        SessionConfig $sessionConfig
    ) {
        $this->monitoringService = $monitoringService;
        $this->sessionConfig = $sessionConfig;
    }

    /**
     * @return int
     */
    protected function getSessionLifetime(): int
    {
        return $this->sessionConfig->getSessionLifeTime();
    }

    /**
     * @return string
     */
    protected function getEnvironmentName(): string
    {
        return $this->sessionConfig->getEnvironmentName();
    }

    /**
     * @return \Spryker\Shared\Session\Dependency\Service\SessionToMonitoringServiceInterface
     */
    public function getMonitoringService(): SessionToMonitoringServiceInterface
    {
        return $this->monitoringService;
    }
}
