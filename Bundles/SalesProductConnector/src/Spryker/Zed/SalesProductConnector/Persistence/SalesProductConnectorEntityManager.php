<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Persistence;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\Propel\Persistence\BatchProcessor\ActiveRecordBatchProcessorTrait;

/**
 * @method \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorPersistenceFactory getFactory()
 */
class SalesProductConnectorEntityManager extends AbstractEntityManager implements SalesProductConnectorEntityManagerInterface
{
    use ActiveRecordBatchProcessorTrait;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<int, array<string, mixed>> $supperAttributesGroupedByIdItem
     *
     * @return void
     */
    public function saveItemsMetadata(QuoteTransfer $quoteTransfer, array $supperAttributesGroupedByIdItem): void
    {
        $salesOrderItemMetadataMapper = $this->getFactory()->createSalesOrderItemMetadataMapper();

        foreach ($quoteTransfer->getItems() as $item) {
            $salesOrderItemMetadataEntity = $salesOrderItemMetadataMapper->mapItemTransferToSalesOrderItemMetadataEntity(
                $item,
                $supperAttributesGroupedByIdItem[$item->getIdSalesOrderItemOrFail()],
            );

            $this->persist($salesOrderItemMetadataEntity);
        }

        $this->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<int, array<string, mixed>> $supperAttributesGroupedByIdItem
     *
     * @return void
     */
    public function saveItemsMetadataByFkSalesOrderItem(QuoteTransfer $quoteTransfer, array $supperAttributesGroupedByIdItem): void
    {
        $salesOrderItemMetadataMapper = $this->getFactory()->createSalesOrderItemMetadataMapper();

        foreach ($quoteTransfer->getItems() as $item) {
            $salesOrderItemMetadataEntity = $this->getFactory()
                ->createProductMetadataQuery()
                ->filterByFkSalesOrderItem($item->getIdSalesOrderItemOrFail())
                ->findOneOrCreate();

            $salesOrderItemMetadataEntity = $salesOrderItemMetadataMapper->mapItemTransferToSalesOrderItemMetadataEntity(
                $item,
                $supperAttributesGroupedByIdItem[$item->getIdSalesOrderItemOrFail()],
                $salesOrderItemMetadataEntity,
            );

            $this->persist($salesOrderItemMetadataEntity);
        }

        $this->commit();
    }

    /**
     * @param list<int> $salesOrderItemIds
     *
     * @return void
     */
    public function deleteSalesOrderItemMetadataCollectionBySalesOrderItemIds(
        array $salesOrderItemIds
    ): void {
        $this->getFactory()
            ->createProductMetadataQuery()
            ->filterByFkSalesOrderItem_In($salesOrderItemIds)
            ->delete();
    }
}
