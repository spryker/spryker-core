<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Extractor;

use ArrayObject;
use Generated\Shared\Transfer\PickingListCollectionTransfer;

interface PickingListExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return list<string>
     */
    public function extraWarehouseUuidsFromPickingListCollection(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): array;

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return list<string>
     */
    public function extraUserUuidsFromPickingListCollection(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): array;

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PickingListTransfer> $pickingListTransfers
     *
     * @return list<string>
     */
    public function extractPickingListUuids(ArrayObject $pickingListTransfers): array;
}
