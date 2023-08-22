<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPackagingUnitsBackendApi\Plugin\PickingListsBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractPlugin;
use Spryker\Glue\PickingListsBackendApiExtension\Dependency\Plugin\PickingListItemsBackendApiAttributesMapperPluginInterface;

/**
 * @method \Spryker\Glue\ProductPackagingUnitsBackendApi\ProductPackagingUnitsBackendApiFactory getFactory()
 */
class ProductPackagingUnitPickingListItemsBackendApiAttributesMapperPlugin extends AbstractPlugin implements PickingListItemsBackendApiAttributesMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `PickingListItemTransfer.uuid` transfer property to be set.
     * - Requires `PickingListItemsBackendApiAttributesTransfer.uuid` transfer property to be set.
     * - Requires `PickingListItemTransfer.orderItem.amountSalesUnit.productMeasurementUnit` transfer property to be set when `PickingListItemTransfer.orderItem.amountSalesUnit` is provided.
     * - Does nothing if `PickingListItemTransfer.orderItem` is not set.
     * - Does nothing if PickingListItemTransfer.orderItem.amountSalesUnit` is not set.
     * - Does nothing if PickingListItemTransfer.orderItem.amount` is not set.
     * - Maps amount sales unit from `PickingListItemTransfer.orderItem.amountSalesUnit` to `PickingListItemsBackendApiAttributesTransfer.orderItem.amountSalesUnit` transfer property.
     * - Maps amount sales unit from `PickingListItemTransfer.orderItem.amount` to `PickingListItemsBackendApiAttributesTransfer.orderItem.amount` transfer property.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItemTransfers
     * @param array<string, \Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer> $pickingListItemsBackendApiAttributesTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer>
     */
    public function mapPickingListItemTransfersToPickingListItemsBackendApiAttributesTransfers(
        array $pickingListItemTransfers,
        array $pickingListItemsBackendApiAttributesTransfers
    ): array {
        return $this->getFactory()
            ->createProductPackagingUnitPickingListItemsMapper()
            ->mapPickingListItemTransfersToPickingListItemsBackendApiAttributesTransfers(
                $pickingListItemTransfers,
                $pickingListItemsBackendApiAttributesTransfers,
            );
    }
}
