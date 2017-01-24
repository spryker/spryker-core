<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ZedRequest\Dependency\Service;

use Spryker\Service\UtilNetwork\UtilNetworkServiceInterface;

class ZedRequestToUtilNetworkBridge implements ZedRequestToUtilNetworkInterface
{

    /**
     * @var UtilNetworkServiceInterface
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
    public function getRequestId()
    {
        return $this->utilNetworkService->getRequestId();
    }

}
