<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer;

interface WarehouseUserAssignmentValidatorInterface
{
    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    public function validateCollection(
        ArrayObject $warehouseUserAssignmentTransfers
    ): WarehouseUserAssignmentCollectionResponseTransfer;
}
