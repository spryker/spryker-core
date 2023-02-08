<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Business\Updater;

interface WarehouseUserAssignmentStatusUpdaterInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     *
     * @return void
     */
    public function deactivatePreviouslyActivatedWarehouseUserAssignments(array $warehouseUserAssignmentTransfers): void;
}
