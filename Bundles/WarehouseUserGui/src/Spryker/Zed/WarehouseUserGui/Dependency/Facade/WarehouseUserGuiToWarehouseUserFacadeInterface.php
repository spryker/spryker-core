<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUserGui\Dependency\Facade;

use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionRequestTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer;

interface WarehouseUserGuiToWarehouseUserFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionRequestTransfer $warehouseUserAssignmentCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    public function createWarehouseUserAssignmentCollection(
        WarehouseUserAssignmentCollectionRequestTransfer $warehouseUserAssignmentCollectionRequestTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionDeleteCriteriaTransfer $warehouseUserAssignmentCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    public function deleteWarehouseUserAssignmentCollection(
        WarehouseUserAssignmentCollectionDeleteCriteriaTransfer $warehouseUserAssignmentCollectionDeleteCriteriaTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer;
}
