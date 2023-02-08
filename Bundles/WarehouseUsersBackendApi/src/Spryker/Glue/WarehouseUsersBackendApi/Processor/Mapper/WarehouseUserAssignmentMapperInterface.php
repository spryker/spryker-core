<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\WarehouseUserAssignmentsRestAttributesTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;

interface WarehouseUserAssignmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentsRestAttributesTransfer $warehouseUserAssignmentsRestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentsRestAttributesTransfer
     */
    public function mapWarehouseUserAssignmentTransferToWarehouseUserAssignmentsRestAttributesTransfer(
        WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer,
        WarehouseUserAssignmentsRestAttributesTransfer $warehouseUserAssignmentsRestAttributesTransfer
    ): WarehouseUserAssignmentsRestAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentsRestAttributesTransfer $warehouseUserAssignmentsRestAttributesTransfer
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer
     */
    public function mapWarehouseUserAssignmentsRestAttributesTransferToWarehouseUserAssignmentTransfer(
        WarehouseUserAssignmentsRestAttributesTransfer $warehouseUserAssignmentsRestAttributesTransfer,
        WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
    ): WarehouseUserAssignmentTransfer;
}
