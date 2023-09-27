<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Extractor;

use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer;

class WarehouseUserAssignmentExtractor implements WarehouseUserAssignmentExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer $warehouseUserAssignmentCollectionTransfer
     *
     * @return list<string>
     */
    public function extractWarehouseUuids(WarehouseUserAssignmentCollectionTransfer $warehouseUserAssignmentCollectionTransfer): array
    {
        $warehouseUuids = [];
        foreach ($warehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments() as $warehouseUserAssignmentTransfer) {
            $warehouseUuids[] = $warehouseUserAssignmentTransfer->getWarehouseOrFail()->getUuidOrFail();
        }

        return $warehouseUuids;
    }
}
