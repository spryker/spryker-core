<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingListPushNotification\Business\Grouper;

use ArrayObject;

class PickingListGrouper implements PickingListGrouperInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PickingListTransfer> $pickingListTransfers
     *
     * @return array<string, array<int, \Generated\Shared\Transfer\PickingListTransfer>>
     */
    public function groupPickingListsByWarehouseUuid(ArrayObject $pickingListTransfers): array
    {
        $groupedPickingListTransfers = [];
        foreach ($pickingListTransfers as $pickingListTransfer) {
            $warehouseUuid = $pickingListTransfer->getWarehouseOrFail()->getUuidOrFail();
            $groupedPickingListTransfers[$warehouseUuid][] = $pickingListTransfer;
        }

        return $groupedPickingListTransfers;
    }
}
