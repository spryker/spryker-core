<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ZedRequest;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;

interface ZedRequestServiceInterface
{
    /**
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function checkZedRequestHealthIndicator(): HealthCheckServiceResponseTransfer;
}
