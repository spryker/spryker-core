<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Persistence;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundleQuery;
use Orm\Zed\ProductBundle\Persistence\SpySalesOrderItemBundleQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductBundle\Persistence\Propel\Mapper\ProductBundleMapper;

/**
 * @method \Spryker\Zed\ProductBundle\ProductBundleConfig getConfig()
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface getRepository()
 */
class ProductBundlePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductBundle\Persistence\SpyProductBundleQuery
     */
    public function createProductBundleQuery()
    {
        return SpyProductBundleQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductBundle\Persistence\SpySalesOrderItemBundleQuery
     */
    public function createSalesOrderItemBundleQuery(): SpySalesOrderItemBundleQuery
    {
        return new SpySalesOrderItemBundleQuery();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function createProductQuery(): SpyProductQuery
    {
        return new SpyProductQuery();
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Persistence\Propel\Mapper\ProductBundleMapper
     */
    public function createProductBundleMapper(): ProductBundleMapper
    {
        return new ProductBundleMapper();
    }
}
