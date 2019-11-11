<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Search;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;

interface SearchServiceInterface
{
    /**
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function checkSearchHealthIndicator(): HealthCheckServiceResponseTransfer;
}
