<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Monitoring\Dependency\Service;

class MonitoringToUtilNetworkServiceBridge implements MonitoringToUtilNetworkServiceInterface
{
    /**
     * @var \Spryker\Service\UtilNetwork\UtilNetworkServiceInterface
     */
    protected $utilNetworkService;

    /**
     * @param \Spryker\Service\UtilNetwork\UtilNetworkServiceInterface $utilNetworkService
     */
    public function __construct($utilNetworkService)
    {
        $this->utilNetworkService = $utilNetworkService;
    }

    /**
     * @return string
     */
    public function getHostName(): string
    {
        return $this->utilNetworkService->getHostName();
    }
}
