<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Dependency\Service;

class SessionRedisToMonitoringServiceBridge implements SessionRedisToMonitoringServiceInterface
{
    /**
     * @var \Spryker\Service\Monitoring\MonitoringServiceInterface
     */
    protected $monitoringService;

    /**
     * @param \Spryker\Service\Monitoring\MonitoringServiceInterface $monitoringService
     */
    public function __construct($monitoringService)
    {
        $this->monitoringService = $monitoringService;
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function addCustomParameter(string $key, $value): void
    {
        $this->monitoringService->addCustomParameter($key, $value);
    }
}
