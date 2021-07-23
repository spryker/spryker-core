<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheck\Persistence;

use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer;

interface PublishAndSynchronizeHealthCheckEntityManagerInterface
{
    /**
     * @return \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer
     */
    public function upsertPublishAndSynchronizeHealthCheckEntity(): PublishAndSynchronizeHealthCheckTransfer;
}
