<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundlesOrdersRestApi\Dependency\RestResource;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RestOrderItemsAttributesTransfer;

class ProductBundlesOrdersRestApiToOrdersRestApiResourceBridge implements ProductBundlesOrdersRestApiToOrdersRestApiResourceInterface
{
    /**
     * @var \Spryker\Glue\OrdersRestApi\OrdersRestApiResourceInterface
     */
    protected $ordersRestApiResource;

    /**
     * @param \Spryker\Glue\OrdersRestApi\OrdersRestApiResourceInterface $ordersRestApiResource
     */
    public function __construct($ordersRestApiResource)
    {
        $this->ordersRestApiResource = $ordersRestApiResource;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\RestOrderItemsAttributesTransfer $restOrderItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderItemsAttributesTransfer
     */
    public function mapItemTransferToRestOrderItemsAttributesTransfer(
        ItemTransfer $itemTransfer,
        RestOrderItemsAttributesTransfer $restOrderItemsAttributesTransfer
    ): RestOrderItemsAttributesTransfer {
        return $this->ordersRestApiResource
            ->mapItemTransferToRestOrderItemsAttributesTransfer($itemTransfer, $restOrderItemsAttributesTransfer);
    }
}
