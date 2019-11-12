<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\HealthCheck;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;

interface HealthCheckClientInterface
{
    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function doHealthCheck(): HealthCheckServiceResponseTransfer;
}
