<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Business;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;

interface PublishAndSynchronizeHealthCheckStorageFacadeInterface
{
    /**
     * Specification:
     * - Writes P&S storage data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByPublishAndSynchronizeHealthCheckEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Returns a successful `HealthCheckServiceResponseTransfer` when the data received from the storage is not older than the configured threshold.
     * - Returns a failed `HealthCheckServiceResponseTransfer` when no data was received from the storage or when the data is older than the configured threshold.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function performHealthCheck(): HealthCheckServiceResponseTransfer;
}
