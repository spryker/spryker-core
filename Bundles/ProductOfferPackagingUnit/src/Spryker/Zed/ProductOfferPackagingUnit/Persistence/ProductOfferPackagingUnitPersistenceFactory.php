<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferPackagingUnit\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductOfferPackagingUnit\ProductOfferPackagingUnitDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferPackagingUnit\ProductOfferPackagingUnitConfig getConfig()
 * @method \Spryker\Zed\ProductOfferPackagingUnit\Persistence\ProductOfferPackagingUnitQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductOfferPackagingUnit\Persistence\ProductOfferPackagingUnitRepositoryInterface getRepository()
 */
class ProductOfferPackagingUnitPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function getSalesOrderItemPropelQuery(): SpySalesOrderItemQuery
    {
        return $this->getProvidedDependency(ProductOfferPackagingUnitDependencyProvider::PROPEL_QUERY_SALES_ORDER_ITEM);
    }
}
