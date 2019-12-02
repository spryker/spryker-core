<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedRequest\Communication\Controller;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

class GatewayController extends AbstractGatewayController
{
    /**
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
     */
    public function healthCheckAction(): HealthCheckServiceResponseTransfer
    {
        return new HealthCheckServiceResponseTransfer();
    }
}
