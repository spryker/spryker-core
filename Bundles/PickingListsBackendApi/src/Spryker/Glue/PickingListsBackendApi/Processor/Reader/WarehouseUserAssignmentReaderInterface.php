<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Reader;

use Generated\Shared\Transfer\UserTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer;

interface WarehouseUserAssignmentReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer
     */
    public function getWarehouseUserAssignmentCollection(UserTransfer $userTransfer): WarehouseUserAssignmentCollectionTransfer;
}
