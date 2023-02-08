<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Persistence;

use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;

interface WarehouseUserEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer
     */
    public function createWarehouseUserAssignment(WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer): WarehouseUserAssignmentTransfer;

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer
     */
    public function updateWarehouseUserAssignment(WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer): WarehouseUserAssignmentTransfer;

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer $warehouseUserAssignmentCollectionTransfer
     *
     * @return void
     */
    public function deleteWarehouseUserAssignments(WarehouseUserAssignmentCollectionTransfer $warehouseUserAssignmentCollectionTransfer): void;
}
