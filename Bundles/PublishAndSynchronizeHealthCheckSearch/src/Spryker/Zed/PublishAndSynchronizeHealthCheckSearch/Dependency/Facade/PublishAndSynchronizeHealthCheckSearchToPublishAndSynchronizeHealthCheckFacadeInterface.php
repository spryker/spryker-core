<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Dependency\Facade;

use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCollectionTransfer;
use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCriteriaTransfer;

interface PublishAndSynchronizeHealthCheckSearchToPublishAndSynchronizeHealthCheckFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCriteriaTransfer $publishAndSynchronizeHealthCheckCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCollectionTransfer
     */
    public function getPublishAndSynchronizeHealthCheckCollection(
        PublishAndSynchronizeHealthCheckCriteriaTransfer $publishAndSynchronizeHealthCheckCriteriaTransfer
    ): PublishAndSynchronizeHealthCheckCollectionTransfer;
}
