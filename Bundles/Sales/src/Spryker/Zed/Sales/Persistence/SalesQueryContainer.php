<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderItemStateHistoryTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\Propel\PropelFilterCriteria;

/**
 * @method \Spryker\Zed\Sales\Persistence\SalesPersistenceFactory getFactory()
 */
class SalesQueryContainer extends AbstractQueryContainer implements SalesQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function querySalesOrder()
    {
        return $this->getFactory()->createSalesOrderQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItem()
    {
        return $this->getFactory()->createSalesOrderItemQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpenseQuery
     */
    public function querySalesExpense()
    {
        return $this->getFactory()->createSalesExpenseQuery();
    }

    /**
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByIdSalesOrder($idOrder)
    {
        $query = $this->getFactory()->createSalesOrderItemQuery();

        return $query->filterByFkSalesOrder($idOrder);
    }

    /**
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsWithState($idOrder)
    {
        $query = $this->querySalesOrderItemsByIdSalesOrder($idOrder);
        $query->joinWith('State');
        $query->joinWith('Process');

        return $query;
    }

    /**
     * @api
     *
     * @param int $idSalesOrderAddress
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery
     */
    public function querySalesOrderAddressById($idSalesOrderAddress)
    {
        $query = $this->getFactory()->createSalesOrderAddressQuery();
        $query->filterByIdSalesOrderAddress($idSalesOrderAddress);

        return $query;
    }

    /**
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpenseQuery
     */
    public function querySalesExpensesByOrderId($idOrder)
    {
        $query = $this->getFactory()->createSalesExpenseQuery();
        $query->filterByFkSalesOrder($idOrder);

        return $query;
    }

    /**
     * @api
     *
     * @param int $idOrderItem
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function queryOrderItemById($idOrderItem)
    {
        $query = $this->getFactory()->createSalesOrderItemQuery();
        $query->filterByIdSalesOrderItem($idOrderItem);

        return $query;
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderCommentQuery
     */
    public function queryComments()
    {
        $query = $this->getFactory()->createSalesOrderCommentQuery();

        return $query;
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderCommentQuery
     */
    public function queryCommentsByIdSalesOrder($idSalesOrder)
    {
        return $this->getFactory()->createSalesOrderCommentQuery()->filterByFkSalesOrder($idSalesOrder);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function querySalesOrderById($idSalesOrder)
    {
        $query = $this->getFactory()->createSalesOrderQuery();
        $query->filterByIdSalesOrder($idSalesOrder);

        return $query;
    }

    /**
     * @api
     *
     * @param int $idCustomer
     * @param \Propel\Runtime\ActiveQuery\Criteria|null $criteria
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function querySalesOrdersByCustomerId($idCustomer, ?Criteria $criteria = null)
    {
        $query = $this->getFactory()->createSalesOrderQuery();
        $query->filterByFkCustomer($idCustomer);
        if ($criteria !== null) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function querySalesOrderDetails($idSalesOrder)
    {
        $query = $this->getFactory()->createSalesOrderQuery()
         ->setModelAlias('order')
         ->filterByIdSalesOrder($idSalesOrder)
         ->innerJoinWith('order.BillingAddress billingAddress')
         ->innerJoinWith('billingAddress.Country billingCountry')
         ->innerJoinWith('order.ShippingAddress shippingAddress')
         ->innerJoinWith('shippingAddress.Country shippingCountry');

        return $query;
    }

    /**
     * @api
     *
     * @param int $idCustomer
     * @param \Generated\Shared\Transfer\FilterTransfer|null $filterTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function queryCustomerOrders($idCustomer, ?FilterTransfer $filterTransfer = null)
    {
        $criteria = new Criteria();
        if ($filterTransfer !== null) {
            $criteria = (new PropelFilterCriteria($filterTransfer))
                ->toCriteria();
        }

        return $this->querySalesOrdersByCustomerId($idCustomer, $criteria);
    }

    /**
     * {@inheritdoc}
     *
     * Note: For performance reasons, the state history join is separated into this method.
     *
     * @api
     *
     * @param \Propel\Runtime\Collection\ObjectCollection $salesOrderItems
     *
     * @return void
     */
    public function fillOrderItemsWithLatestStates(ObjectCollection $salesOrderItems)
    {
        /** @var \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItemEntity */
        foreach ($salesOrderItems as $orderItemEntity) {
            $criteria = new Criteria();
            $criteria->addDescendingOrderByColumn(SpyOmsOrderItemStateHistoryTableMap::COL_ID_OMS_ORDER_ITEM_STATE_HISTORY);
            $orderItemEntity->getStateHistoriesJoinState($criteria);
            $orderItemEntity->resetPartialStateHistories(false);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @deprecated Use SalesQueryContainerInterface::fillOrderItemsWithLatestStates() instead.
     *
     * @param \Propel\Runtime\Collection\ObjectCollection $salesOrderItems
     *
     * @return void
     */
    public function queryOrderItemsStateHistoriesOrderedByNewestState(ObjectCollection $salesOrderItems)
    {
        $this->fillOrderItemsWithLatestStates($salesOrderItems);
    }

    /**
     * @api
     *
     * @deprecated Will be removed with the next major
     *
     * @param int $idSalesOrderItem
     * @param int $idOmsOrderItemState
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistoryQuery
     */
    public function queryOmsOrderItemStateHistoryByOrderItemIdAndOmsStateIdDesc($idSalesOrderItem, $idOmsOrderItemState)
    {
        return $this->getFactory()
            ->createOmsOrderItemStateHistoryQuery()
            ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->filterByFkOmsOrderItemState($idOmsOrderItemState)
            ->orderByIdOmsOrderItemStateHistory(Criteria::DESC);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function queryCountUniqueProductsForOrder($idSalesOrder)
    {
        return $this->querySalesOrderItemsByIdSalesOrder($idSalesOrder)
            ->withColumn('COUNT(*)', 'Count')
            ->select(['Count'])
            ->groupBySku()
            ->orderByCount();
    }
}
