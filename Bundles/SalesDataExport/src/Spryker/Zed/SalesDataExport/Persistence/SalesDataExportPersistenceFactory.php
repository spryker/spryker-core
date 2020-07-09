<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Persistence;

use Orm\Zed\Sales\Persistence\Base\SpySalesOrderQuery;
use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SalesDataExport\Dependency\Service\SalesDataExportToUtilEncodingServiceInterface;
use Spryker\Zed\SalesDataExport\Persistence\Propel\Mapper\SalesExpenseMapper;
use Spryker\Zed\SalesDataExport\Persistence\Propel\Mapper\SalesOrderCommentMapper;
use Spryker\Zed\SalesDataExport\Persistence\Propel\Mapper\SalesOrderItemMapper;
use Spryker\Zed\SalesDataExport\Persistence\Propel\Mapper\SalesOrderMapper;
use Spryker\Zed\SalesDataExport\SalesDataExportDependencyProvider;

/**
 * @method \Spryker\Zed\SalesDataExport\Persistence\SalesDataExportRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesDataExport\SalesDataExportConfig getConfig()
 */
class SalesDataExportPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\SalesDataExport\Persistence\Propel\Mapper\SalesOrderMapper
     */
    public function createSalesOrderMapper(): SalesOrderMapper
    {
        return new SalesOrderMapper($this->createSalesOrderCommentMapper());
    }

    /**
     * @return \Spryker\Zed\SalesDataExport\Persistence\Propel\Mapper\SalesOrderItemMapper
     */
    public function createSalesOrderItemMapper(): SalesOrderItemMapper
    {
        return new SalesOrderItemMapper();
    }

    /**
     * @return \Spryker\Zed\SalesDataExport\Persistence\Propel\Mapper\SalesOrderCommentMapper
     */
    public function createSalesOrderCommentMapper(): SalesOrderCommentMapper
    {
        return new SalesOrderCommentMapper($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Zed\SalesDataExport\Persistence\Propel\Mapper\SalesExpenseMapper
     */
    public function createSalesExpenseMapper(): SalesExpenseMapper
    {
        return new SalesExpenseMapper();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\Base\SpySalesOrderQuery
     */
    public function getSalesOrderPropelQuery(): SpySalesOrderQuery
    {
        return $this->getProvidedDependency(SalesDataExportDependencyProvider::PROPEL_QUERY_SALES_ORDER);
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function getSalesOrderItemPropelQuery(): SpySalesOrderItemQuery
    {
        return $this->getProvidedDependency(SalesDataExportDependencyProvider::PROPEL_QUERY_SALES_ORDER_ITEM);
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpenseQuery
     */
    public function getSalesExpensePropelQuery(): SpySalesExpenseQuery
    {
        return $this->getProvidedDependency(SalesDataExportDependencyProvider::PROPEL_QUERY_SALES_EXPENSE);
    }

    /**
     * @return \Spryker\Zed\SalesDataExport\Dependency\Service\SalesDataExportToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): SalesDataExportToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(SalesDataExportDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
