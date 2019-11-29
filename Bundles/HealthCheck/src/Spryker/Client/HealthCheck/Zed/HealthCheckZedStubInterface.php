<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\HealthCheck\Zed;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;

interface HealthCheckZedStubInterface
{
    /**
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function executeZedRequestHealthCheck(): HealthCheckServiceResponseTransfer;
}
