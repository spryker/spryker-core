<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Dependency\Facade;

use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;

interface MerchantSalesReturnToSalesFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderItemFilterTransfer $orderItemFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function getOrderItems(OrderItemFilterTransfer $orderItemFilterTransfer): ItemCollectionTransfer;
}
