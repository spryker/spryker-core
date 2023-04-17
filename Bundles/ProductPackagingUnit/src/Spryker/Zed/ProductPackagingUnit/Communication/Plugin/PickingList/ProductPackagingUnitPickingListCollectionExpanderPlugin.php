<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Communication\Plugin\PickingList;

use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListCollectionExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPackagingUnit\Communication\ProductPackagingUnitCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig getConfig()
 */
class ProductPackagingUnitPickingListCollectionExpanderPlugin extends AbstractPlugin implements PickingListCollectionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `PickingListCollectionTransfer.pickingList.pickingListItem.orderItem` transfer property to be set.
     * - Expands `PickingListCollectionTransfer.pickingList.pickingListItem.orderItem` transfer objects with `amountSalesUnit` property.
     * - Returns expanded `PickingListCollectionTransfer` transfer object with amount sales unit.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function expand(PickingListCollectionTransfer $pickingListCollectionTransfer): PickingListCollectionTransfer
    {
        return $this->getFacade()->expandPickingListCollection($pickingListCollectionTransfer);
    }
}
