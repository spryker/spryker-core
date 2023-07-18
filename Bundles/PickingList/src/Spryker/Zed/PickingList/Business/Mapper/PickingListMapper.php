<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\PickingListConditionsTransfer;
use Generated\Shared\Transfer\PickingListCriteriaTransfer;

class PickingListMapper implements PickingListMapperInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     * @param \Generated\Shared\Transfer\PickingListCriteriaTransfer $pickingListCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCriteriaTransfer
     */
    public function mapOrderCollectionToPickingListCriteriaTransfer(
        ArrayObject $orderTransfers,
        PickingListCriteriaTransfer $pickingListCriteriaTransfer
    ): PickingListCriteriaTransfer {
        $pickingListConditionsTransfer = new PickingListConditionsTransfer();
        foreach ($orderTransfers as $orderTransfer) {
            foreach ($orderTransfer->getItems() as $itemTransfer) {
                $pickingListConditionsTransfer->addSalesOrderItemUuid($itemTransfer->getUuidOrFail());
            }
        }

        return $pickingListCriteriaTransfer->setPickingListConditions($pickingListConditionsTransfer);
    }
}
