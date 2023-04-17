<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UsersBackendApi\Processor\Filter;

use Generated\Shared\Transfer\GlueResourceTransfer;

class WarehouseUserAssignmentResourceFilter implements WarehouseUserAssignmentResourceFilterInterface
{
    /**
     * @uses \Spryker\Glue\WarehouseUsersBackendApi\WarehouseUsersBackendApiConfig::RESOURCE_TYPE_WAREHOUSE_USER_ASSIGNMENTS
     *
     * @var string
     */
    protected const RESOURCE_TYPE_WAREHOUSE_USER_ASSIGNMENTS = 'warehouse-user-assignments';

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     *
     * @return list<\Generated\Shared\Transfer\GlueResourceTransfer>
     */
    public function filterWarehouseUserAssignmentResources(array $glueResourceTransfers): array
    {
        $warehouseUserAssignmentsResourceTransfers = [];
        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            if ($this->isApplicableWarehouseUserAssignmentsResource($glueResourceTransfer)) {
                $warehouseUserAssignmentsResourceTransfers[] = $glueResourceTransfer;
            }
        }

        return $warehouseUserAssignmentsResourceTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     *
     * @return bool
     */
    protected function isApplicableWarehouseUserAssignmentsResource(
        GlueResourceTransfer $glueResourceTransfer
    ): bool {
        return $glueResourceTransfer->getType() === static::RESOURCE_TYPE_WAREHOUSE_USER_ASSIGNMENTS;
    }
}
