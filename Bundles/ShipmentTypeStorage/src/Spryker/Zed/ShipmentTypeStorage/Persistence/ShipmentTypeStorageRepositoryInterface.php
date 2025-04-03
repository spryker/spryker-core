<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface ShipmentTypeStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<int> $shipmentTypeIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getShipmentTypeStorageSynchronizationDataTransfers(FilterTransfer $filterTransfer, array $shipmentTypeIds = []): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<int> $shipmentTypeListIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getShipmentTypeListStorageSynchronizationDataTransfers(FilterTransfer $filterTransfer, array $shipmentTypeListIds = []): array;
}
