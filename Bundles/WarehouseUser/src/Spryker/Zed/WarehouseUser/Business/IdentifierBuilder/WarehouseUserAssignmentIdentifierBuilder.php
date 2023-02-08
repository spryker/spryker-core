<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Business\IdentifierBuilder;

use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;

class WarehouseUserAssignmentIdentifierBuilder implements WarehouseUserAssignmentIdentifierBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
     *
     * @return string
     */
    public function buildIdentifier(WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer): string
    {
        return $warehouseUserAssignmentTransfer->getIdWarehouseUserAssignment() !== null
            ? (string)$warehouseUserAssignmentTransfer->getIdWarehouseUserAssignment()
            : spl_object_hash($warehouseUserAssignmentTransfer);
    }
}
