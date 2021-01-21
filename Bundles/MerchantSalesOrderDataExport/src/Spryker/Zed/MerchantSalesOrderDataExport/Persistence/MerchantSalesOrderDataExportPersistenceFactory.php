<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport\Persistence;

use Orm\Zed\Merchant\Persistence\Base\SpyMerchantQuery;
use Orm\Zed\MerchantSalesOrder\Persistence\Base\SpyMerchantSalesOrderItemQuery;
use Orm\Zed\MerchantSalesOrder\Persistence\Base\SpyMerchantSalesOrderQuery;
use Orm\Zed\Sales\Persistence\Base\SpySalesOrderCommentQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantSalesOrderDataExport\Dependency\Service\MerchantSalesOrderDataExportToUtilEncodingServiceInterface;
use Spryker\Zed\MerchantSalesOrderDataExport\MerchantSalesOrderDataExportDependencyProvider;
use Spryker\Zed\MerchantSalesOrderDataExport\Persistence\Propel\Mapper\MerchantSalesExpenseMapper;
use Spryker\Zed\MerchantSalesOrderDataExport\Persistence\Propel\Mapper\MerchantSalesOrderCommentMapper;
use Spryker\Zed\MerchantSalesOrderDataExport\Persistence\Propel\Mapper\MerchantSalesOrderItemMapper;
use Spryker\Zed\MerchantSalesOrderDataExport\Persistence\Propel\Mapper\MerchantSalesOrderMapper;

/**
 * @method \Spryker\Zed\MerchantSalesOrderDataExport\Persistence\MerchantSalesOrderDataExportRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantSalesOrderDataExport\MerchantSalesOrderDataExportConfig getConfig()
 */
class MerchantSalesOrderDataExportPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\MerchantSalesOrderDataExport\Persistence\Propel\Mapper\MerchantSalesOrderMapper
     */
    public function createMerchantSalesOrderMapper(): MerchantSalesOrderMapper
    {
        return new MerchantSalesOrderMapper($this->createMerchantSalesOrderCommentMapper());
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderDataExport\Persistence\Propel\Mapper\MerchantSalesOrderItemMapper
     */
    public function createMerchantSalesOrderItemMapper(): MerchantSalesOrderItemMapper
    {
        return new MerchantSalesOrderItemMapper();
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderDataExport\Persistence\Propel\Mapper\MerchantSalesOrderCommentMapper
     */
    public function createMerchantSalesOrderCommentMapper(): MerchantSalesOrderCommentMapper
    {
        return new MerchantSalesOrderCommentMapper($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderDataExport\Persistence\Propel\Mapper\MerchantSalesExpenseMapper
     */
    public function createMerchantSalesExpenseMapper(): MerchantSalesExpenseMapper
    {
        return new MerchantSalesExpenseMapper();
    }

    /**
     * @phpstan-return \Orm\Zed\Merchant\Persistence\Base\SpyMerchantQuery<\Orm\Zed\Merchant\Persistence\SpyMerchant>
     *
     * @return \Orm\Zed\Merchant\Persistence\Base\SpyMerchantQuery
     */
    public function getMerchantPropelQuery(): SpyMerchantQuery
    {
        return $this->getProvidedDependency(MerchantSalesOrderDataExportDependencyProvider::PROPEL_QUERY_MERCHANT);
    }

    /**
     * @phpstan-return \Orm\Zed\MerchantSalesOrder\Persistence\Base\SpyMerchantSalesOrderQuery<\Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder>
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\Base\SpyMerchantSalesOrderQuery
     */
    public function getMerchantSalesOrderPropelQuery(): SpyMerchantSalesOrderQuery
    {
        return $this->getProvidedDependency(MerchantSalesOrderDataExportDependencyProvider::PROPEL_QUERY_MERCHANT_SALES_ORDER);
    }

    /**
     * @phpstan-return \Orm\Zed\MerchantSalesOrder\Persistence\Base\SpyMerchantSalesOrderItemQuery<\Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem>
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\Base\SpyMerchantSalesOrderItemQuery
     */
    public function getMerchantSalesOrderItemPropelQuery(): SpyMerchantSalesOrderItemQuery
    {
        return $this->getProvidedDependency(MerchantSalesOrderDataExportDependencyProvider::PROPEL_QUERY_MERCHANT_SALES_ORDER_ITEM);
    }

    /**
     * @phpstan-return \Orm\Zed\Sales\Persistence\Base\SpySalesOrderCommentQuery
     *
     * @return \Orm\Zed\Sales\Persistence\Base\SpySalesOrderCommentQuery
     */
    public function getSalesOrderCommentPropelQuery(): SpySalesOrderCommentQuery
    {
        return $this->getProvidedDependency(MerchantSalesOrderDataExportDependencyProvider::PROPEL_QUERY_SALES_ORDER_COMMENT);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderDataExport\Dependency\Service\MerchantSalesOrderDataExportToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): MerchantSalesOrderDataExportToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderDataExportDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
