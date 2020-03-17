<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Business\Expander;

use Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepositoryInterface;

class ItemMetadataExpander implements ItemMetadataExpanderInterface
{
    /**
     * @var \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepositoryInterface
     */
    protected $salesProductConnectorRepository;

    /**
     * @param \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepositoryInterface $salesProductConnectorRepository
     */
    public function __construct(SalesProductConnectorRepositoryInterface $salesProductConnectorRepository)
    {
        $this->salesProductConnectorRepository = $salesProductConnectorRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithMetadata(array $itemTransfers): array
    {
        $salesOrderItemIds = $this->extractSalesOrderItemIds($itemTransfers);

        $itemMetadataTransfers = $this->salesProductConnectorRepository->getSalesOrderItemMetadataByOrderItemIds($salesOrderItemIds);
        $mappedItemMetadataTransfers = $this->mapItemMetadataTransfersByIdSalesOrderItem($itemMetadataTransfers);

        foreach ($itemTransfers as $itemTransfer) {
            if (isset($mappedItemMetadataTransfers[$itemTransfer->getIdSalesOrderItem()])) {
                $itemTransfer->setMetadata($mappedItemMetadataTransfers[$itemTransfer->getIdSalesOrderItem()]);
            }
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return int[]
     */
    protected function extractSalesOrderItemIds(array $itemTransfers): array
    {
        $salesOrderItemIds = [];

        foreach ($itemTransfers as $itemTransfer) {
            $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItem();
        }

        return array_unique($salesOrderItemIds);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemMetadataTransfer[] $itemMetadataTransfers
     *
     * @return \Generated\Shared\Transfer\ItemMetadataTransfer[]
     */
    protected function mapItemMetadataTransfersByIdSalesOrderItem(array $itemMetadataTransfers): array
    {
        $mappedItemMetadataTransfers = [];

        foreach ($itemMetadataTransfers as $itemMetadataTransfer) {
            $mappedItemMetadataTransfers[$itemMetadataTransfer->getFkSalesOrderItem()] = $itemMetadataTransfer;
        }

        return $mappedItemMetadataTransfers;
    }
}
