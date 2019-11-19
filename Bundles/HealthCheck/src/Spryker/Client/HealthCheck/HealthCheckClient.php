<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\HealthCheck;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\HealthCheck\HealthCheckFactory getFactory()
 */
class HealthCheckClient extends AbstractClient implements HealthCheckClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function doHealthCheck(): HealthCheckServiceResponseTransfer
    {
        return $this->getFactory()->createHealthCheckZedStub()->doHealthCheck();
    }
}
