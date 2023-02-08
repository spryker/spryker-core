<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi\Processor\Updater;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentsRestAttributesTransfer;

interface WarehouseUserAssignmentUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentsRestAttributesTransfer $warehouseUserAssignmentsRestAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function updateWarehouseUserAssignment(
        WarehouseUserAssignmentsRestAttributesTransfer $warehouseUserAssignmentsRestAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer;
}
