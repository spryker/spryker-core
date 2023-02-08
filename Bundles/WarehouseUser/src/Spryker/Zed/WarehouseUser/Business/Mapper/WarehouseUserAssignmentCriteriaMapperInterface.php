<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Business\Mapper;

use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer;

interface WarehouseUserAssignmentCriteriaMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionDeleteCriteriaTransfer $warehouseUserAssignmentCollectionDeleteCriteriaTransfer
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer $warehouseUserAssignmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer
     */
    public function mapWarehouseUserAssignmentCollectionDeleteCriteriaTransferToWarehouseUserAssignmentCriteriaTransfer(
        WarehouseUserAssignmentCollectionDeleteCriteriaTransfer $warehouseUserAssignmentCollectionDeleteCriteriaTransfer,
        WarehouseUserAssignmentCriteriaTransfer $warehouseUserAssignmentCriteriaTransfer
    ): WarehouseUserAssignmentCriteriaTransfer;
}
