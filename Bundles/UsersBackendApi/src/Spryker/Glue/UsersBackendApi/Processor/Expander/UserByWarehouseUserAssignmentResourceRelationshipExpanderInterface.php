<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UsersBackendApi\Processor\Expander;

interface UserByWarehouseUserAssignmentResourceRelationshipExpanderInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\GlueResourceTransfer> $warehouseUserAssignmentsResources
     *
     * @return array<\Generated\Shared\Transfer\GlueResourceTransfer>
     */
    public function expandWarehouseUserAssignmentsResourcesWithUsersResourceRelationships(
        array $warehouseUserAssignmentsResources
    ): array;
}
