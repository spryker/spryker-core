<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Persistence;

use DateTime;
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
    protected const ORDERS_COUNT_TOTAL = 'ordersCountTotal';
    protected const ORDERS_COUNT_NEW = 'ordersCountNew';

    /**
     * @param int $idMerchant
     *
     * @return mixed[][]
     */
    public function getOrdersStoresCountData(int $idMerchant): array
    {
        $merchantSalesOrderQuery = $this->getFactory()->getMerchantSalesOrderPropelQuery();
        $merchantSalesOrderQuery = $this->filterMerchantSalesOrderQueryByIdMerchant($merchantSalesOrderQuery, $idMerchant);

        return $merchantSalesOrderQuery->joinOrder()
            ->addAsColumn(OrderTransfer::STORE, SpySalesOrderTableMap::COL_STORE)
            ->addAsColumn(static::ORDERS_COUNT_TOTAL, 'COUNT(*)')
            ->useOrderQuery()
                ->groupByStore()
            ->endUse()
            ->select([
                OrderTransfer::STORE,
                static::ORDERS_COUNT_TOTAL,
            ])
            ->find()
        ->toArray();
    }

    /**
     * @module Merchant
     * @module MerchantSalesOrder
     *
     * @param int $idMerchant
     *
     * @return int[]
     */
    public function getOrdersDashboardCardCounts(int $idMerchant): array
    {
        $salesMerchantPortalGuiConfig = $this->getFactory()->getConfig();
        $dashboardNewOrdersLimit = $salesMerchantPortalGuiConfig->getDashboardNewOrdersLimit();
        $newOrdersDateTime = (new DateTime(sprintf('-%s Days', $dashboardNewOrdersLimit)))->format('Y-m-d H:i:s');

        $merchantSalesOrderQuery = $this->getFactory()->getMerchantSalesOrderPropelQuery();
        $merchantSalesOrderQuery = $this->filterMerchantSalesOrderQueryByIdMerchant($merchantSalesOrderQuery, $idMerchant);

        return $merchantSalesOrderQuery
            ->addAsColumn(static::ORDERS_COUNT_TOTAL, 'COUNT(*)')
            ->addAsColumn(static::ORDERS_COUNT_NEW, sprintf(
                "COUNT(CASE WHEN '%s' < %s THEN 1 END)",
                $newOrdersDateTime,
                SpyMerchantSalesOrderTableMap::COL_CREATED_AT
            ))
            ->select([
                static::ORDERS_COUNT_TOTAL,
                static::ORDERS_COUNT_NEW,
            ])
            ->findOne();
    }

    /**
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
}
