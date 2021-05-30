<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence;

use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer;
use Orm\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence\SpyPublishAndSynchronizeHealthCheckSearch;

interface PublishAndSynchronizeHealthCheckSearchRepositoryInterface
{
    /**
     * @param int $idPublishAndSynchronizeHealthCheck
     *
     * @return \Orm\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence\SpyPublishAndSynchronizeHealthCheckSearch
     */
    public function findOrCreatePublishAndSynchronizeHealthCheckSearchByIdPublishAndSynchronizeHealthCheck(
        int $idPublishAndSynchronizeHealthCheck
    ): SpyPublishAndSynchronizeHealthCheckSearch;

    /**
     * @param int $idPublishAndSynchronizeHealthCheck
     *
     * @return \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer
     */
    public function getPublishAndSynchronizeHealthCheckTransferByIdPublishAndSynchronizeHealthCheck(
        int $idPublishAndSynchronizeHealthCheck
    ): PublishAndSynchronizeHealthCheckTransfer;
}
