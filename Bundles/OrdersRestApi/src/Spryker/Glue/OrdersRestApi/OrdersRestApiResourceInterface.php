<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RestOrderItemsAttributesTransfer;

interface OrdersRestApiResourceInterface
{
    /**
     * Specification:
     * - Maps `ItemTransfer` to `RestOrderItemsAttributesTransfer`.
     * - Runs the stack of {@link \Spryker\Glue\OrdersRestApiExtension\Dependency\Plugin\RestOrderItemsAttributesMapperPluginInterface} plugins.
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
    ): RestOrderItemsAttributesTransfer;
}
