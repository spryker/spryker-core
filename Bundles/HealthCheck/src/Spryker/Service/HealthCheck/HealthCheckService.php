<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\HealthCheck;

use Generated\Shared\Transfer\HealthCheckRequestTransfer;
use Generated\Shared\Transfer\HealthCheckResponseTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\HealthCheck\HealthCheckServiceFactory getFactory()
 */
class HealthCheckService extends AbstractService implements HealthCheckServiceInterface
{
//    /**
//     * {@inheritDoc}
//     *
//     * @api
//     *
//     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
//     *
//     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
//     */
//    public function checkYvesHealthCheck(HealthCheckRequestTransfer $healthCheckRequestTransfer): HealthCheckResponseTransfer
//    {
//    }
//
//    /**
//     * {@inheritDoc}
//     *
//     * @api
//     *
//     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
//     *
//     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
//     */
//    public function checkZedHealthCheck(HealthCheckRequestTransfer $healthCheckRequestTransfer): HealthCheckResponseTransfer
//    {
//    }
//
//    /**
//     * {@inheritDoc}
//     *
//     * @api
//     *
//     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
//     *
//     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
//     */
//    public function checkGlueHealthCheck(HealthCheckRequestTransfer $healthCheckRequestTransfer): HealthCheckResponseTransfer
//    {
//    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HealthCheckRequestTransfer $healthCheckRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function processHealthCheck(HealthCheckRequestTransfer $healthCheckRequestTransfer): HealthCheckResponseTransfer
    {
         return $this->getFactory()->createHealthCheckServiceProcessor()->process($healthCheckRequestTransfer);
    }
}
