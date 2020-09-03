<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundlesOrdersRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer;
use Generated\Shared\Transfer\RestOrderItemsAttributesTransfer;
use Spryker\Glue\ProductBundlesOrdersRestApi\Dependency\RestResource\ProductBundlesOrdersRestApiToOrdersRestApiResourceInterface;

class OrderMapper implements OrderMapperInterface
{
    /**
     * @var \Spryker\Glue\ProductBundlesOrdersRestApi\Dependency\RestResource\ProductBundlesOrdersRestApiToOrdersRestApiResourceInterface
     */
    protected $ordersRestApiResource;

    /**
     * @param \Spryker\Glue\ProductBundlesOrdersRestApi\Dependency\RestResource\ProductBundlesOrdersRestApiToOrdersRestApiResourceInterface $ordersRestApiResource
     */
    public function __construct(ProductBundlesOrdersRestApiToOrdersRestApiResourceInterface $ordersRestApiResource)
    {
        $this->ordersRestApiResource = $ordersRestApiResource;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer $restOrderDetailsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer
     */
    public function mapOrderTransferToRestOrderDetailsAttributesTransfer(
        OrderTransfer $orderTransfer,
        RestOrderDetailsAttributesTransfer $restOrderDetailsAttributesTransfer
    ): RestOrderDetailsAttributesTransfer {
        if (!$orderTransfer->getBundleItems()->count()) {
            return $restOrderDetailsAttributesTransfer;
        }

        $restOrderItemsAttributesTransfers = new ArrayObject();
        foreach ($orderTransfer->getBundleItems() as $itemTransfer) {
            $restOrderItemsAttributesTransfer = $this->ordersRestApiResource->mapItemTransferToRestOrderItemsAttributesTransfer(
                $itemTransfer,
                new RestOrderItemsAttributesTransfer()
            );

            $restOrderItemsAttributesTransfers->append($restOrderItemsAttributesTransfer);
        }

        return $restOrderDetailsAttributesTransfer->setBundleItems($restOrderItemsAttributesTransfers);
    }
}
