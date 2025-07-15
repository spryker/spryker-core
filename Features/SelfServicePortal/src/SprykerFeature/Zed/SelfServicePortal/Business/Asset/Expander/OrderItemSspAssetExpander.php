<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Extractor\SalesOrderItemIdExtractorInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Reader\SspAssetReaderInterface;

class OrderItemSspAssetExpander implements OrderItemSspAssetExpanderInterface
{
    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Business\Asset\Reader\SspAssetReaderInterface $sspAssetReader
     * @param \SprykerFeature\Zed\SelfServicePortal\Business\Asset\Extractor\SalesOrderItemIdExtractorInterface $salesOrderItemIdExtractor
     */
    public function __construct(
        protected readonly SspAssetReaderInterface $sspAssetReader,
        protected readonly SalesOrderItemIdExtractorInterface $salesOrderItemIdExtractor
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderItemsWithSspAssets(OrderTransfer $orderTransfer): OrderTransfer
    {
        $salesOrderItemIds = $this->salesOrderItemIdExtractor->extractSalesOrderItemIds($orderTransfer);

        if (!$salesOrderItemIds) {
            return $orderTransfer;
        }

        $sspAssetsIndexedByIdSalesOrderItem = $this->sspAssetReader->indexSspAssetsBySalesOrderItemIds($salesOrderItemIds);

        return $this->setAssetsToOrderItems($orderTransfer, $sspAssetsIndexedByIdSalesOrderItem);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<int, \Generated\Shared\Transfer\SspAssetTransfer> $sspAssetsIndexedByIdSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function setAssetsToOrderItems(
        OrderTransfer $orderTransfer,
        array $sspAssetsIndexedByIdSalesOrderItem
    ): OrderTransfer {
        foreach ($orderTransfer->getItems() as $orderItemTransfer) {
            $salesOrderItemId = $orderItemTransfer->getIdSalesOrderItemOrFail();

            if (!isset($sspAssetsIndexedByIdSalesOrderItem[$salesOrderItemId])) {
                continue;
            }

            $orderItemTransfer->setSspAsset($sspAssetsIndexedByIdSalesOrderItem[$salesOrderItemId]);
        }

        return $orderTransfer;
    }
}
