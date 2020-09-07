<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundlesRestApi\Plugin\OrdersRestApi;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\OrdersRestApiExtension\Dependency\Plugin\RestOrderDetailsAttributesMapperPluginInterface;

/**
 * @method \Spryker\Glue\ProductBundlesRestApi\ProductBundlesRestApiFactory getFactory()
 */
class BundleItemRestOrderDetailsAttributesMapperPlugin extends AbstractPlugin implements RestOrderDetailsAttributesMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps `OrderTransfer.bundleItems` to `RestOrderDetailsAttributesTransfer.bundleItems`.
     * - Will overwrite any automatically mapped items.
     * - Uses `OrdersRestApiResource::mapItemTransferToRestOrderItemsAttributesTransfer()` to do the mapping.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer $restOrderDetailsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer
     */
    public function mapOrderTransferToRestOrderDetailsAttributesTransfer(
        OrderTransfer $orderTransfer,
        RestOrderDetailsAttributesTransfer $restOrderDetailsAttributesTransfer
    ): RestOrderDetailsAttributesTransfer {
        return $this->getFactory()
            ->createOrderMapper()
            ->mapOrderTransferToRestOrderDetailsAttributesTransfer($orderTransfer, $restOrderDetailsAttributesTransfer);
    }
}
