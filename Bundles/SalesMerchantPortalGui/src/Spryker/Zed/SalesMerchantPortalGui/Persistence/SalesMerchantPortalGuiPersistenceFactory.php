<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Persistence;

use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SalesMerchantPortalGui\Persistence\Propel\Mapper\SalesMerchantPortalGuiMapper;
use Spryker\Zed\SalesMerchantPortalGui\SalesMerchantPortalGuiDependencyProvider;

/**
 * @method \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface getRepository()
 */
class SalesMerchantPortalGuiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\SalesMerchantPortalGui\Persistence\Propel\Mapper\SalesMerchantPortalGuiMapper
     */
    public function createSalesMerchantPortalGuiMapper(): SalesMerchantPortalGuiMapper
    {
        return new SalesMerchantPortalGuiMapper();
    }

    /**
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    public function getMerchantSalesOrderPropelQuery(): SpyMerchantSalesOrderQuery
    {
        return $this->getProvidedDependency(SalesMerchantPortalGuiDependencyProvider::PROPEL_QUERY_MERCHANT_SALES_ORDER);
    }
}