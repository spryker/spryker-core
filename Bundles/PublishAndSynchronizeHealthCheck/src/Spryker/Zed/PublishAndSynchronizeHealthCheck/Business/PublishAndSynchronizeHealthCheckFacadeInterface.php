<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheck\Business;

use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCollectionTransfer;
use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCriteriaTransfer;
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

    /**
     * Specification:
     * - Fetches a collection of publish and synchronize health checks from the Persistence.
     * - Uses `PublishAndSynchronizeHealthCheckCriteriaTransfer.publishAndSynchronizeHealthCheckConditions.publishAndSynchronizeHealthCheckIds` to filter publish and synchronize health checks by publishAndSynchronizeHealthCheckIds.
     * - Uses `PublishAndSynchronizeHealthCheckCriteriaTransfer.pagination.limit` and PublishAndSynchronizeHealthCheckCriteriaTransfer.pagination.offset` to paginate results with limit and offset.
     * - Returns `PublishAndSynchronizeHealthCheckCollectionTransfer` filled with found publish and synchronize health checks.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCriteriaTransfer $publishAndSynchronizeHealthCheckCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCollectionTransfer
     */
    public function getPublishAndSynchronizeHealthCheckCollection(
        PublishAndSynchronizeHealthCheckCriteriaTransfer $publishAndSynchronizeHealthCheckCriteriaTransfer
    ): PublishAndSynchronizeHealthCheckCollectionTransfer;
}
