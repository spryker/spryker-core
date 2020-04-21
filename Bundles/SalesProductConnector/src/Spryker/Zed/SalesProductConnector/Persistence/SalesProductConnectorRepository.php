<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorPersistenceFactory getFactory()
 */
class SalesProductConnectorRepository extends AbstractRepository implements SalesProductConnectorRepositoryInterface
{
    /**
     * @param int[] $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\ItemMetadataTransfer[]
     */
    public function getSalesOrderItemMetadataByOrderItemIds(array $salesOrderItemIds): array
    {
        if (!$salesOrderItemIds) {
            return [];
        }

        $salesOrderItemMetadataQuery = $this->getFactory()
            ->createProductMetadataQuery()
            ->filterByFkSalesOrderItem_In($salesOrderItemIds);

        return $this->getFactory()
            ->createSalesOrderItemMetadataMapper()
            ->mapSalesOrderItemMetadataEntityCollectionToItemMetadataTransfers($salesOrderItemMetadataQuery->find());
    }
}
