<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesProductConfiguration\Expander;

use Generated\Shared\Transfer\ItemTransfer;
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
            if (!$item->getSalesOrderItemConfiguration()) {
                continue;
            }
            $item->setProductConfigurationInstance(
                $this->createProductConfigurationInstanceTransfer($item)
            );
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    protected function createProductConfigurationInstanceTransfer(ItemTransfer $itemTransfer): ProductConfigurationInstanceTransfer
    {
        return (new ProductConfigurationInstanceTransfer())
            ->fromArray($itemTransfer->getSalesOrderItemConfiguration()->toArray(), true)
            ->setIsComplete(true);
    }
}
