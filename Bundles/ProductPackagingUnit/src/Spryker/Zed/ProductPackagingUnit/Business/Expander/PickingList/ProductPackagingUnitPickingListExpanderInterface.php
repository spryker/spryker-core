<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Expander\PickingList;

use Generated\Shared\Transfer\PickingListCollectionTransfer;

interface ProductPackagingUnitPickingListExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function expandPickingListCollection(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): PickingListCollectionTransfer;
}
