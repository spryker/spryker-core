<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesProductConfiguration\Expander;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;

class ItemExpander implements ItemExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandItemsWithProductConfiguration(array $itemTransfers, OrderTransfer $orderTransfer): array
    {
        foreach ($itemTransfers as &$item) {
            if ($item->getSalesOrderItemConfiguration()) {
                $productConfigurationInstanceTransfer = new ProductConfigurationInstanceTransfer();
                $productConfigurationInstanceTransfer->fromArray($item->getSalesOrderItemConfiguration()->toArray(), true);
                $productConfigurationInstanceTransfer->setIsComplete(true);
                $item->setProductConfigurationInstance($productConfigurationInstanceTransfer);
            }
        }

        return $itemTransfers;
    }
}
