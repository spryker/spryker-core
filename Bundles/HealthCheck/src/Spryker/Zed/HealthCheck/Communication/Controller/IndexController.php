<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\HealthCheck\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\HealthCheck\Business\HealthCheckFacade getFacade()
 * @method \Spryker\Zed\HealthCheck\Communication\HealthCheckCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    protected const KEY_HEALTH_CHECK_SERVICES = 'services';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $requestedServices = $request->query->get(static::KEY_HEALTH_CHECK_SERVICES);
        $healthCheckResponseTransfer = $this->getFacade()->executeHealthCheck($requestedServices);

        return $this->jsonResponse(
            [
                $healthCheckResponseTransfer->toArray(),
            ],
            $healthCheckResponseTransfer->getStatusCode()
        );
    }
}
