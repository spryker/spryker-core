<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Grouper;

use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer;

class WarehouseUserAssignmentGrouper implements WarehouseUserAssignmentGrouperInterface
{
    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer $warehouseUserAssignmentCollectionTransfer
     *
     * @return array<string, array<string, array<int, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer>>>
     */
    public function groupWarehouseUserAssignmentCollectionByUserUuidAndWarehouseUuid(
        WarehouseUserAssignmentCollectionTransfer $warehouseUserAssignmentCollectionTransfer
    ): array {
        $warehouseUserAssignmentsGroupedByUserUuidAndWarehouseUuid = [];
        foreach ($warehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments() as $warehouseUserAssignmentTransfer) {
            $userUuid = $warehouseUserAssignmentTransfer->getUserUuidOrFail();
            $warehouseUuid = $warehouseUserAssignmentTransfer->getWarehouseOrFail()->getUuidOrFail();
            $warehouseUserAssignmentsGroupedByUserUuidAndWarehouseUuid[$userUuid][$warehouseUuid][] = $warehouseUserAssignmentTransfer;
        }

        return $warehouseUserAssignmentsGroupedByUserUuidAndWarehouseUuid;
    }
}
