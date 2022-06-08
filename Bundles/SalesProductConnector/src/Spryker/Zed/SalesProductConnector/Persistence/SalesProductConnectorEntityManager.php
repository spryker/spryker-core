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
     * @param array $supperAttributesGroupedByIdItem
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
}
