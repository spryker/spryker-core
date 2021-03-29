<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesProductConfiguration\Expander;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer;

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
        foreach ($itemTransfers as $item) {
            if (!array_key_exists($item->getGroupKey(), $productConfigurationSalesOrderItemsGroupedByGroupKey)) {
                continue;
            }
            $item->setProductConfigurationInstance(
                $this->createProductConfigurationInstanceTransfer(
                    $productConfigurationSalesOrderItemsGroupedByGroupKey[$item->getGroupKey()]
                )
            );
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    protected function createProductConfigurationInstanceTransfer(
        SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer
    ): ProductConfigurationInstanceTransfer {
        return (new ProductConfigurationInstanceTransfer())
            ->fromArray($salesOrderItemConfigurationTransfer->toArray(), true)
            ->setIsComplete(true);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer[]
     */
    protected function getProductConfigurationSalesOrderItemsGroupedByGroupKey(OrderTransfer $orderTransfer): array
    {
        $productConfigurationSalesOrderItemsGroupedByGroupKey = [];
        foreach ($orderTransfer->getItems() as $item) {
            if (!$item->getSalesOrderItemConfiguration()) {
                continue;
            }

            $productConfigurationSalesOrderItemsGroupedByGroupKey[$item->getGroupKey()] = $item->getSalesOrderItemConfiguration();
        }

        return $productConfigurationSalesOrderItemsGroupedByGroupKey;
    }
}
