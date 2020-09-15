<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundleCartsRestApi\Plugin\OrdersRestApi;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RestOrderItemsAttributesTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\OrdersRestApiExtension\Dependency\Plugin\RestOrderItemsAttributesMapperPluginInterface;

/**
 * @method \Spryker\Glue\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiFactory getFactory()
 */
class SalesConfiguredBundleOrderItemsAttributesMapperPlugin extends AbstractPlugin implements RestOrderItemsAttributesMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps `item.salesOrderConfiguredBundle` and `item.salesOrderConfiguredBundleItem` to `restOrderItemsAttributes`.
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
            ->createItemMapper()
            ->mapItemToRestOrderItemsAttributes($itemTransfer, $restOrderItemsAttributesTransfer);
    }
}
