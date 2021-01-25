<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Plugin\OrdersRestApi;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RestOrderItemsAttributesTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\OrdersRestApiExtension\Dependency\Plugin\RestOrderItemsAttributesMapperPluginInterface;

/**
 * @method \Spryker\Glue\ProductMeasurementUnitsRestApi\ProductMeasurementUnitsRestApiFactory getFactory()
 */
class SalesUnitRestOrderItemsAttributesMapperPlugin extends AbstractPlugin implements RestOrderItemsAttributesMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps `ItemTransfer::$amountSalesUnit` to `RestOrderItemsAttributesTransfer::$salesUnit`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\RestOrderItemsAttributesTransfer $restOrderItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderItemsAttributesTransfer
     */
    public function mapItemTransferToRestOrderItemsAttributesTransfer(
        ItemTransfer $itemTransfer,
        RestOrderItemsAttributesTransfer $restOrderItemsAttributesTransfer
    ): RestOrderItemsAttributesTransfer {
        return $this->getFactory()
            ->createSalesUnitMapper()
            ->mapItemTransferToRestOrderItemsAttributesTransfer($itemTransfer, $restOrderItemsAttributesTransfer);
    }
}
