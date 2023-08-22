<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi\Processor\Creator;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentsBackendApiAttributesTransfer;

interface WarehouseUserAssignmentCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentsBackendApiAttributesTransfer $warehouseUserAssignmentsBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createWarehouseUserAssignment(
        WarehouseUserAssignmentsBackendApiAttributesTransfer $warehouseUserAssignmentsBackendApiAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer;
}
