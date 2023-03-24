<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Filter;

use Generated\Shared\Transfer\PickingListCollectionResponseTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;

interface PickingListFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function getValidPickingLists(
        PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
    ): PickingListCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function getInvalidPickingLists(
        PickingListCollectionResponseTransfer $pickingListCollectionResponseTransfer
    ): PickingListCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $validPickingListCollectionTransfer
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $invalidPickingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function mergeValidAndInvalidPickingLists(
        PickingListCollectionTransfer $validPickingListCollectionTransfer,
        PickingListCollectionTransfer $invalidPickingListCollectionTransfer
    ): PickingListCollectionTransfer;
}
