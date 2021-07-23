<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheck\Business;

use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer;

interface PublishAndSynchronizeHealthCheckFacadeInterface
{
    /**
     * Specification:
     * - Creates or updates an entity that will be used for checking that the P&S process works as expected.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer
     */
    public function savePublishAndSynchronizeHealthCheckEntity(): PublishAndSynchronizeHealthCheckTransfer;
}
