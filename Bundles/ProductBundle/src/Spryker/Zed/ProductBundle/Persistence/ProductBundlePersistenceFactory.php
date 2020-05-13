<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Persistence;

use Orm\Zed\ProductBundle\Persistence\SpyProductBundleQuery;
use Orm\Zed\ProductBundle\Persistence\SpySalesOrderItemBundleQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductBundle\Persistence\Propel\Mapper\ProductBundleMapper;
use Spryker\Zed\ProductBundle\ProductBundleDependencyProvider;

/**
 * @method \Spryker\Zed\ProductBundle\ProductBundleConfig getConfig()
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface getRepository()
 */
class ProductBundlePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductBundle\Persistence\SpySalesOrderItemBundleQuery
     */
    public function createSalesOrderItemBundleQuery(): SpySalesOrderItemBundleQuery
    {
        return new SpySalesOrderItemBundleQuery();
    }

    /**
     * @return \Orm\Zed\ProductBundle\Persistence\SpyProductBundleQuery
     */
    public function createProductBundleQuery()
    {
        return SpyProductBundleQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Persistence\Propel\Mapper\ProductBundleMapper
     */
    public function createProductBundleMapper(): ProductBundleMapper
    {
        return new ProductBundleMapper();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function getSalesOrderItemPropelQuery(): SpySalesOrderItemQuery
    {
        return $this->getProvidedDependency(ProductBundleDependencyProvider::PROPEL_QUERY_SALES_ORDER_ITEM);
    }
}
