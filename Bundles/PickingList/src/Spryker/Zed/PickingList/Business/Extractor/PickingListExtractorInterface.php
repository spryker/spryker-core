<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Extractor;

use Generated\Shared\Transfer\PickingListCollectionTransfer;

interface PickingListExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return array<string>
     */
    public function extraWarehouseUuidsFromPickingListCollection(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): array;

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return array<string>
     */
    public function extraUserUuidsFromPickingListCollection(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): array;
}
