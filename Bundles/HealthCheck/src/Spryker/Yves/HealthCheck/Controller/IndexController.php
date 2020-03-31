<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\HealthCheck\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Yves\HealthCheck\HealthCheckFactory getFactory()
 * @method \Spryker\Yves\HealthCheck\HealthCheckConfig getConfig()
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
        $healthCheckResponseTransfer = $this->getFactory()
            ->createHealthCheckProcessor()
            ->process($requestedServices);

        return $this->jsonResponse(
            [
                $healthCheckResponseTransfer->toArray(),
            ],
            $healthCheckResponseTransfer->getStatusCode()
        );
    }
}
