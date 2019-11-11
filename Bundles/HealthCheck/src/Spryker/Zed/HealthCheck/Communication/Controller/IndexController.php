<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\HealthCheck\Communication\Controller;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\HealthCheck\Communication\HealthCheckCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $healthCheckRequestTransfer = (new HealthCheckRequestTransfer())
            ->setApplication(APPLICATION);
        $healthCheckResponseTransfer = $this->getFactory()->getHealthCheckService()->processHealthCheck($healthCheckRequestTransfer);
    }
}
