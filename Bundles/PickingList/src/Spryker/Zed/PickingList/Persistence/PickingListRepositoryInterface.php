<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Persistence;

use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListCriteriaTransfer;
use Generated\Shared\Transfer\PickingListItemCollectionTransfer;
use Generated\Shared\Transfer\PickingListItemCriteriaTransfer;

interface PickingListRepositoryInterface
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
     * @param \Generated\Shared\Transfer\PickingListItemCriteriaTransfer $pickingListItemCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListItemCollectionTransfer
     */
    public function getPickingListItemCollection(
        PickingListItemCriteriaTransfer $pickingListItemCriteriaTransfer
    ): PickingListItemCollectionTransfer;
}
