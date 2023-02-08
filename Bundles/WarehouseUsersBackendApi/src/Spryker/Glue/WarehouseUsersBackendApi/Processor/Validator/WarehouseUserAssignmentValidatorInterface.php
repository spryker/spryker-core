<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi\Processor\Validator;

use Generated\Shared\Transfer\GlueRequestTransfer;

interface WarehouseUserAssignmentValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param string $warehouseUserAssignmentUserUuid
     *
     * @return bool
     */
    public function isCurrentUserAllowedToOperateWithWarehouseUserAssignment(
        GlueRequestTransfer $glueRequestTransfer,
        string $warehouseUserAssignmentUserUuid
    ): bool;
}
