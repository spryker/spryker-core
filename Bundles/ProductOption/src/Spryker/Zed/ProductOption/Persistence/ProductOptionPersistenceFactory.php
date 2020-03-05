<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Persistence;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroupQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePriceQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductOption\Persistence\Propel\Mapper\ProductOptionMapper;
use Spryker\Zed\ProductOption\ProductOptionDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOption\ProductOptionConfig getConfig()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionRepositoryInterface getRepository()
 */
class ProductOptionPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery
     */
    public function createProductOptionGroupQuery()
    {
        return SpyProductOptionGroupQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePriceQuery
     */
    public function createProductOptionValuePriceQuery()
    {
        return SpyProductOptionValuePriceQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function createProductOptionValueQuery()
    {
        return SpyProductOptionValueQuery::create();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function createProductAbstractQuery()
    {
        return SpyProductAbstractQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroupQuery
     */
    public function createProductAbstractProductOptionGroupQuery()
    {
        return SpyProductAbstractProductOptionGroupQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductOption\Persistence\Propel\Mapper\ProductOptionMapper
     */
    public function createProductOptionMapper(): ProductOptionMapper
    {
        return new ProductOptionMapper();
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\QueryContainer\ProductOptionToSalesQueryContainerInterface
     */
    public function getSalesQueryContainer()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::QUERY_CONTAINER_SALES);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\QueryContainer\ProductOptionToCountryQueryContainerInterface
     */
    public function getCountryQueryContainer()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::QUERY_CONTAINER_COUNTRY);
    }
}
