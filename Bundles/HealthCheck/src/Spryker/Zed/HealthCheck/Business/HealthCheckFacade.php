<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\HealthCheck\Business;

use Generated\Shared\Transfer\HealthCheckResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\HealthCheck\Business\HealthCheckBusinessFactory getFactory()
 */
class HealthCheckFacade extends AbstractFacade implements HealthCheckFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string|null $requestedServices
     *
     * @return \Generated\Shared\Transfer\HealthCheckResponseTransfer
     */
    public function executeHealthCheck(?string $requestedServices = null): HealthCheckResponseTransfer
    {
        return $this->getFactory()->createHealthCheckProcessor()->process($requestedServices);
    }
}
