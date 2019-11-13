<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Persistence;

use Generated\Shared\Transfer\SalesOrderMerchantCriteriaFilterTransfer;
use Generated\Shared\Transfer\SalesOrderMerchantTransfer;
use Orm\Zed\SalesMerchantConnector\Persistence\SpySalesOrderMerchantQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SalesMerchantConnector\Persistence\SalesMerchantConnectorPersistenceFactory getFactory()
 */
class SalesMerchantConnectorRepository extends AbstractRepository implements SalesMerchantConnectorRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderMerchantCriteriaFilterTransfer $salesOrderMerchantCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderMerchantTransfer|null
     */
    public function findOne(SalesOrderMerchantCriteriaFilterTransfer $salesOrderMerchantCriteriaFilterTransfer): ?SalesOrderMerchantTransfer
    {
        $salesOrderMerchantQuery = $this->getFactory()->createSalesOrderMerchantQuery();
        $salesOrderMerchantEntity = $this->applyFilters($salesOrderMerchantQuery, $salesOrderMerchantCriteriaFilterTransfer)->findOne();

        if (!$salesOrderMerchantEntity) {
            return null;
        }

        return $this->getFactory()
            ->createSalesMerchantConnectorMapper()
            ->mapSalesOrderMerchantEntityToSalesOrderMerchantTransfer($salesOrderMerchantEntity, new SalesOrderMerchantTransfer());
    }

    /**
     * @param \Orm\Zed\SalesMerchantConnector\Persistence\SpySalesOrderMerchantQuery $salesOrderMerchantQuery
     * @param \Generated\Shared\Transfer\SalesOrderMerchantCriteriaFilterTransfer $salesOrderMerchantCriteriaFilterTransfer
     *
     * @return \Orm\Zed\SalesMerchantConnector\Persistence\SpySalesOrderMerchantQuery
     */
    protected function applyFilters(
        SpySalesOrderMerchantQuery $salesOrderMerchantQuery,
        SalesOrderMerchantCriteriaFilterTransfer $salesOrderMerchantCriteriaFilterTransfer
    ): SpySalesOrderMerchantQuery {
        if ($salesOrderMerchantCriteriaFilterTransfer->getSalesOrderMerchantReference() !== null) {
            $salesOrderMerchantQuery->filterBySalesOrderMerchantReference($salesOrderMerchantCriteriaFilterTransfer->getSalesOrderMerchantReference());
        }

        return $salesOrderMerchantQuery;
    }
}
