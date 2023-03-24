<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Mapper;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PickingListConditionsTransfer;
use Generated\Shared\Transfer\PickingListCriteriaTransfer;

class PickingListMapper implements PickingListMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\PickingListCriteriaTransfer $pickingListCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCriteriaTransfer
     */
    public function mapOrderTransferToPickingListCriteriaTransfer(
        OrderTransfer $orderTransfer,
        PickingListCriteriaTransfer $pickingListCriteriaTransfer
    ): PickingListCriteriaTransfer {
        $pickingListConditionsTransfer = new PickingListConditionsTransfer();
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $pickingListConditionsTransfer->addSalesOrderItemUuid($itemTransfer->getUuidOrFail());
        }

        return $pickingListCriteriaTransfer->setPickingListConditions($pickingListConditionsTransfer);
    }
}
