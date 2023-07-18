<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListCriteriaTransfer;

interface PickingListReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PickingListCriteriaTransfer $pickingListCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function getPickingListCollection(
        PickingListCriteriaTransfer $pickingListCriteriaTransfer
    ): PickingListCollectionTransfer;

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     *
     * @return array<int, list<\Generated\Shared\Transfer\PickingListTransfer>>
     */
    public function getPickingListTransfersGroupedByIdSalesOrder(ArrayObject $orderTransfers): array;
}
