<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\WarehouseUserAssignmentsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;

interface WarehouseUserAssignmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentsBackendApiAttributesTransfer $warehouseUserAssignmentsBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentsBackendApiAttributesTransfer
     */
    public function mapWarehouseUserAssignmentTransferToWarehouseUserAssignmentsBackendApiAttributesTransfer(
        WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer,
        WarehouseUserAssignmentsBackendApiAttributesTransfer $warehouseUserAssignmentsBackendApiAttributesTransfer
    ): WarehouseUserAssignmentsBackendApiAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentsBackendApiAttributesTransfer $warehouseUserAssignmentsBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer
     */
    public function mapWarehouseUserAssignmentsBackendApiAttributesTransferToWarehouseUserAssignmentTransfer(
        WarehouseUserAssignmentsBackendApiAttributesTransfer $warehouseUserAssignmentsBackendApiAttributesTransfer,
        WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
    ): WarehouseUserAssignmentTransfer;
}
