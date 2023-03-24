<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Expander;

use Generated\Shared\Transfer\PickingListCollectionTransfer;

interface PickingListExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function expandPickingListCollectionWithOrderItems(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): PickingListCollectionTransfer;
}
