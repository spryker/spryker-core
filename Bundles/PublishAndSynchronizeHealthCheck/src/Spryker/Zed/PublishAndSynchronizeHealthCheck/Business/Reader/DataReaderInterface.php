<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\Reader;

use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer;

interface DataReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer $publishAndSynchronizeHealthCheckTransfer
     *
     * @return \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer
     */
    public function getPublishAndSynchronizeHealthCheckTransfer(
        PublishAndSynchronizeHealthCheckTransfer $publishAndSynchronizeHealthCheckTransfer
    ): PublishAndSynchronizeHealthCheckTransfer;
}
