<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Persistence;

use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Service\SalesMerchantPortalGuiToUtilEncodingServiceInterface;
use Spryker\Zed\SalesMerchantPortalGui\Persistence\Propel\Mapper\MerchantOrderItemTableDataMapper;
use Spryker\Zed\SalesMerchantPortalGui\Persistence\Propel\Mapper\MerchantOrderTableDataMapper;
use Spryker\Zed\SalesMerchantPortalGui\SalesMerchantPortalGuiDependencyProvider;

/**
 * @method \Spryker\Zed\SalesMerchantPortalGui\SalesMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface getRepository()
 */
class SalesMerchantPortalGuiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\SalesMerchantPortalGui\Persistence\Propel\Mapper\MerchantOrderTableDataMapper
     */
    public function createMerchantOrderTableDataMapper(): MerchantOrderTableDataMapper
    {
        return new MerchantOrderTableDataMapper();
    }

    /**
     * @return \Spryker\Zed\SalesMerchantPortalGui\Persistence\Propel\Mapper\MerchantOrderItemTableDataMapper
     */
    public function createMerchantOrderItemTableDataMapper(): MerchantOrderItemTableDataMapper
    {
        return new MerchantOrderItemTableDataMapper($this->getUtilEncodingService());
    }

    /**
     * @phpstan-return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery<mixed>
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    public function getMerchantSalesOrderPropelQuery(): SpyMerchantSalesOrderQuery
    {
        return $this->getProvidedDependency(SalesMerchantPortalGuiDependencyProvider::PROPEL_QUERY_MERCHANT_SALES_ORDER);
    }

    /**
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery
     */
    public function getMerchantSalesOrderItemPropelQuery(): SpyMerchantSalesOrderItemQuery
    {
        return $this->getProvidedDependency(SalesMerchantPortalGuiDependencyProvider::PROPEL_QUERY_MERCHANT_SALES_ORDER_ITEM);
    }

    /**
     * @return \Spryker\Zed\SalesMerchantPortalGui\Dependency\Service\SalesMerchantPortalGuiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): SalesMerchantPortalGuiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(SalesMerchantPortalGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
