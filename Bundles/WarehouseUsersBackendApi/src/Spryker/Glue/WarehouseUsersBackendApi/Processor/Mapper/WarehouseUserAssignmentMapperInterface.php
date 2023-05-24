<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiWarehouseUserAssignmentsAttributesTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentTransfer;

interface WarehouseUserAssignmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
     * @param \Generated\Shared\Transfer\ApiWarehouseUserAssignmentsAttributesTransfer $apiWarehouseUserAssignmentsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiWarehouseUserAssignmentsAttributesTransfer
     */
    public function mapWarehouseUserAssignmentTransferToApiWarehouseUserAssignmentsAttributesTransfer(
        WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer,
        ApiWarehouseUserAssignmentsAttributesTransfer $apiWarehouseUserAssignmentsAttributesTransfer
    ): ApiWarehouseUserAssignmentsAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\ApiWarehouseUserAssignmentsAttributesTransfer $apiWarehouseUserAssignmentsAttributesTransfer
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer
     */
    public function mapApiWarehouseUserAssignmentsAttributesTransferToWarehouseUserAssignmentTransfer(
        ApiWarehouseUserAssignmentsAttributesTransfer $apiWarehouseUserAssignmentsAttributesTransfer,
        WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer
    ): WarehouseUserAssignmentTransfer;
}
