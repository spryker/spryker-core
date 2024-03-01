<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Persistence;

use Orm\Zed\ProductSet\Persistence\SpyProductAbstractSetQuery;
use Orm\Zed\ProductSet\Persistence\SpyProductSetDataQuery;
use Orm\Zed\ProductSet\Persistence\SpyProductSetQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductSet\Persistence\Propel\Mapper\ProductSetMapper;
use Spryker\Zed\ProductSet\ProductSetDependencyProvider;

/**
 * @method \Spryker\Zed\ProductSet\ProductSetConfig getConfig()
 * @method \Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductSet\Persistence\ProductSetRepositoryInterface getRepository()
 */
class ProductSetPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function getUrlPropelQuery(): SpyUrlQuery
    {
        return SpyUrlQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSetQuery
     */
    public function createProductSetQuery()
    {
        return SpyProductSetQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductAbstractSetQuery
     */
    public function createProductAbstractSetQuery()
    {
        return SpyProductAbstractSetQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSetDataQuery
     */
    public function createProductSetDataQuery()
    {
        return SpyProductSetDataQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductSet\Dependency\QueryContainer\ProductSetToUrlInterface
     */
    public function getUrlQueryContainer()
    {
        return $this->getProvidedDependency(ProductSetDependencyProvider::QUERY_CONTAINER_URL);
    }

    /**
     * @return \Spryker\Zed\ProductSet\Dependency\QueryContainer\ProductSetToProductImageInterface
     */
    public function getProductImageQueryContainer()
    {
        return $this->getProvidedDependency(ProductSetDependencyProvider::QUERY_CONTAINER_PRODUCT_IMAGE);
    }

    /**
     * @return \Spryker\Zed\ProductSet\Persistence\Propel\Mapper\ProductSetMapper
     */
    public function createProductSetMapper(): ProductSetMapper
    {
        return new ProductSetMapper();
    }
}
