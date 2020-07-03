<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Persistence;

use DateTime;
use Generated\Shared\Transfer\MerchantOrderCountsTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiPersistenceFactory getFactory()
 */
class SalesMerchantPortalGuiRepository extends AbstractRepository implements SalesMerchantPortalGuiRepositoryInterface
{
    /**
     * @module Merchant
     * @module MerchantSalesOrder
     *
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantOrderCountsTransfer
     */
    public function getMerchantOrderCounts(int $idMerchant): MerchantOrderCountsTransfer
    {
        $salesMerchantPortalGuiConfig = $this->getFactory()->getConfig();
        $newOrdersDaysThreshold = $salesMerchantPortalGuiConfig->getDashboardNewOrdersDaysThreshold();
        $newOrdersDateTime = (new DateTime(sprintf('-%s Days', $newOrdersDaysThreshold)))->format('Y-m-d H:i:s');

        $merchantSalesOrderQuery = $this->getFactory()->getMerchantSalesOrderPropelQuery();
        $merchantSalesOrderQuery = $this->filterMerchantSalesOrderQueryByIdMerchant($merchantSalesOrderQuery, $idMerchant);

        $merchantOrderCounts = $merchantSalesOrderQuery
            ->addAsColumn(MerchantOrderCountsTransfer::TOTAL, 'COUNT(*)')
            ->addAsColumn(
                MerchantOrderCountsTransfer::NEW,
                "COUNT(CASE WHEN '$newOrdersDateTime' < " . SpyMerchantSalesOrderTableMap::COL_CREATED_AT . ' THEN 1 END)'
            )
            ->select([
                MerchantOrderCountsTransfer::TOTAL,
                MerchantOrderCountsTransfer::NEW,
            ])
            ->findOne();

        $merchantOrderCountsTransfer = (new MerchantOrderCountsTransfer())
            ->fromArray($merchantOrderCounts, true);

        $totalsPerStore = $this->getOrderTotalsPerStore($idMerchant);
        foreach ($totalsPerStore as $totalPerStore) {
            $merchantOrderCountsTransfer->addTotalPerStore(
                $totalPerStore[OrderTransfer::STORE],
                $totalPerStore[MerchantOrderCountsTransfer::TOTAL_PER_STORE]
            );
        }

        return $merchantOrderCountsTransfer;
    }

    /**
     * @phpstan-param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery<mixed> $merchantSalesOrderQuery
     *
     * @phpstan-return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery<mixed>
     *
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery $merchantSalesOrderQuery
     * @param int $idMerchant
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function filterMerchantSalesOrderQueryByIdMerchant(
        SpyMerchantSalesOrderQuery $merchantSalesOrderQuery,
        int $idMerchant
    ): SpyMerchantSalesOrderQuery {
        $merchantSalesOrderQuery->addJoin(
            SpyMerchantSalesOrderTableMap::COL_MERCHANT_REFERENCE,
            SpyMerchantTableMap::COL_MERCHANT_REFERENCE,
            Criteria::INNER_JOIN
        );
        $merchantSalesOrderQuery->addAnd(
            SpyMerchantTableMap::COL_ID_MERCHANT,
            $idMerchant
        );

        return $merchantSalesOrderQuery;
    }

    /**
     * @param int $idMerchant
     *
     * @return mixed[][]
     */
    protected function getOrderTotalsPerStore(int $idMerchant): array
    {
        $merchantSalesOrderQuery = $this->getFactory()->getMerchantSalesOrderPropelQuery();
        $merchantSalesOrderQuery = $this->filterMerchantSalesOrderQueryByIdMerchant($merchantSalesOrderQuery, $idMerchant);

        return $merchantSalesOrderQuery->joinOrder()
            ->addAsColumn(OrderTransfer::STORE, SpySalesOrderTableMap::COL_STORE)
            ->addAsColumn(MerchantOrderCountsTransfer::TOTAL_PER_STORE, 'COUNT(*)')
            ->useOrderQuery()
                ->groupByStore()
            ->endUse()
            ->select([
                OrderTransfer::STORE,
                MerchantOrderCountsTransfer::TOTAL_PER_STORE,
            ])
            ->find()
            ->toArray();
    }
}
