<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence;

use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistoryQuery;
use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderCommentQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderTotalsQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesExpenseMapper;
use Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesExpenseMapperInterface;
use Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderAddressMapper;
use Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderAddressMapperInterface;
use Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderItemMapper;
use Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderItemMapperInterface;
use Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderMapper;
use Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchFilterFieldQueryBuilder;
use Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchFilterFieldQueryBuilderInterface;
use Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchQueryJoinQueryBuilder;
use Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchQueryJoinQueryBuilderInterface;

/**
 * @method \Spryker\Zed\Sales\SalesConfig getConfig()
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface getRepository()
 */
class SalesPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function createSalesOrderQuery()
    {
        return SpySalesOrderQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function createSalesOrderItemQuery()
    {
        return SpySalesOrderItemQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpenseQuery
     */
    public function createSalesExpenseQuery()
    {
        return SpySalesExpenseQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery
     */
    public function createSalesOrderAddressQuery()
    {
        return SpySalesOrderAddressQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderCommentQuery
     */
    public function createSalesOrderCommentQuery()
    {
        return SpySalesOrderCommentQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderTotalsQuery
     */
    public function getSalesOrderTotalsPropelQuery(): SpySalesOrderTotalsQuery
    {
        return SpySalesOrderTotalsQuery::create();
    }

    /**
     * @deprecated Will be removed with the next major
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateHistoryQuery
     */
    public function createOmsOrderItemStateHistoryQuery()
    {
        return SpyOmsOrderItemStateHistoryQuery::create();
    }

    /**
     * @return \Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesExpenseMapperInterface
     */
    public function createSalesExpenseMapper(): SalesExpenseMapperInterface
    {
        return new SalesExpenseMapper();
    }

    /**
     * @return \Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderAddressMapperInterface
     */
    public function createSalesOrderAddressMapper(): SalesOrderAddressMapperInterface
    {
        return new SalesOrderAddressMapper();
    }

    /**
     * @return \Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderMapper
     */
    public function createSalesOrderMapper(): SalesOrderMapper
    {
        return new SalesOrderMapper();
    }

    /**
     * @return \Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderItemMapperInterface
     */
    public function createSalesOrderItemMapper(): SalesOrderItemMapperInterface
    {
        return new SalesOrderItemMapper();
    }

    /**
     * @return \Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchFilterFieldQueryBuilderInterface
     */
    public function createOrderSearchFilterFieldQueryBuilder(): OrderSearchFilterFieldQueryBuilderInterface
    {
        return new OrderSearchFilterFieldQueryBuilder();
    }

    /**
     * @return \Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchQueryJoinQueryBuilderInterface
     */
    public function createOrderSearchQueryJoinQueryBuilder(): OrderSearchQueryJoinQueryBuilderInterface
    {
        return new OrderSearchQueryJoinQueryBuilder();
    }

    /**
     * @return \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    public function getSalesQueryContainer(): SalesQueryContainerInterface
    {
        return $this->getQueryContainer();
    }
}
