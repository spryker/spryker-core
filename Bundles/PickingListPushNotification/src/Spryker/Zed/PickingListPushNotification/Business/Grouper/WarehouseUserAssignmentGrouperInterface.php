<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingListPushNotification\Business\Grouper;

use ArrayObject;

interface WarehouseUserAssignmentGrouperInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     *
     * @return array<string, array<string, array<int, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer>>>
     */
    public function groupWarehouseUserAssignmentTransfersByUserUuidAndWarehouseUuid(
        ArrayObject $warehouseUserAssignmentTransfers
    ): array;
}
