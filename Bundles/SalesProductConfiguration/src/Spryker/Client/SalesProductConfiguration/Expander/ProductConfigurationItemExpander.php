<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesProductConfiguration\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;

class ProductConfigurationItemExpander implements ProductConfigurationItemExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandItemsWithProductConfiguration(array $itemTransfers, OrderTransfer $orderTransfer): array
    {
        $productConfigurationSalesOrderItemsGroupedByGroupKey = $this->getProductConfigurationSalesOrderItemsGroupedByGroupKey($orderTransfer);
        foreach ($itemTransfers as $itemTransfer) {
            if (!array_key_exists($itemTransfer->getGroupKeyOrFail(), $productConfigurationSalesOrderItemsGroupedByGroupKey)) {
                continue;
            }

            $productConfigurationInstanceTransfer = $this->createProductConfigurationInstanceTransfer(
                $productConfigurationSalesOrderItemsGroupedByGroupKey[$itemTransfer->getGroupKey()]
            );
            $itemTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    protected function createProductConfigurationInstanceTransfer(
        ItemTransfer $itemTransfer
    ): ProductConfigurationInstanceTransfer {
        return (new ProductConfigurationInstanceTransfer())
            ->fromArray($itemTransfer->getSalesOrderItemConfiguration()->toArray(), true)
            ->setIsComplete(true);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getProductConfigurationSalesOrderItemsGroupedByGroupKey(OrderTransfer $orderTransfer): array
    {
        $productConfigurationSalesOrderItemsGroupedByGroupKey = [];
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getSalesOrderItemConfiguration()) {
                continue;
            }

            $productConfigurationSalesOrderItemsGroupedByGroupKey[$itemTransfer->getGroupKeyOrFail()] = $itemTransfer;
        }

        return $productConfigurationSalesOrderItemsGroupedByGroupKey;
    }
}
