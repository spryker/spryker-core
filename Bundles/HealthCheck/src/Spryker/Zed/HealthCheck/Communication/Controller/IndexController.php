<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\HealthCheck\Communication\Controller;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Spryker\HealthCheck\src\Spryker\Zed\HealthCheck\Communication\Exception\HealthCheckDisabledException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\HealthCheck\Communication\HealthCheckCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    protected const KEY_SERVICE = 'service';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
//        if ($this->getFactory()->getConfig()->isHealthCheckEnabled()) {
//            throw new HealthCheckDisabledException();
//        }

        $requestedServices = $request->get(static::KEY_SERVICE);

        $healthCheckRequestTransfer = (new HealthCheckRequestTransfer())
            ->setApplication(APPLICATION)
            ->setServices($requestedServices);

        $healthCheckResponseTransfer = $this->getFactory()
            ->getHealthCheckService()
            ->checkZedHealthCheck($healthCheckRequestTransfer);

        return new JsonResponse($healthCheckResponseTransfer->toArray());
    }
}
