<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPackagingUnitsBackendApi\Plugin\PickingListsBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractPlugin;
use Spryker\Glue\PickingListsBackendApiExtension\Dependency\Plugin\ApiPickingListItemsAttributesMapperPluginInterface;

/**
 * @method \Spryker\Glue\ProductPackagingUnitsBackendApi\ProductPackagingUnitsBackendApiFactory getFactory()
 */
class ProductPackagingUnitApiPickingListItemsAttributesMapperPlugin extends AbstractPlugin implements ApiPickingListItemsAttributesMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `PickingListItemTransfer.uuid` transfer property to be set.
     * - Requires `ApiPickingListItemsAttributesTransfer.uuid` transfer property to be set.
     * - Requires `PickingListItemTransfer.orderItem.amountSalesUnit.productMeasurementUnit` transfer property to be set when `PickingListItemTransfer.orderItem.amountSalesUnit` is provided.
     * - Does nothing if `PickingListItemTransfer.orderItem` is not set.
     * - Does nothing if PickingListItemTransfer.orderItem.amountSalesUnit` is not set.
     * - Does nothing if PickingListItemTransfer.orderItem.amount` is not set.
     * - Maps amount sales unit from `PickingListItemTransfer.orderItem.amountSalesUnit` to `ApiPickingListItemsAttributesTransfer.orderItem.amountSalesUnit` transfer property.
     * - Maps amount sales unit from `PickingListItemTransfer.orderItem.amount` to `ApiPickingListItemsAttributesTransfer.orderItem.amount` transfer property.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItemTransfers
     * @param array<string, \Generated\Shared\Transfer\ApiPickingListItemsAttributesTransfer> $apiPickingListItemsAttributesTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\ApiPickingListItemsAttributesTransfer>
     */
    public function mapPickingListItemTransfersToApiPickingListItemsAttributesTransfers(
        array $pickingListItemTransfers,
        array $apiPickingListItemsAttributesTransfers
    ): array {
        return $this->getFactory()
            ->createProductPackagingUnitPickingListItemsMapper()
            ->mapPickingListItemTransfersToApiPickingListItemsAttributesTransfers(
                $pickingListItemTransfers,
                $apiPickingListItemsAttributesTransfers,
            );
    }
}
