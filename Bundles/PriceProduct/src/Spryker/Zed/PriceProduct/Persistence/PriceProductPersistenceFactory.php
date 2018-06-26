<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Persistence;

use Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\PriceProduct\Persistence\Propel\Mapper\PriceProductMapper;
use Spryker\Zed\PriceProduct\Persistence\Propel\Mapper\PriceProductMapperInterface;
use Spryker\Zed\PriceProduct\Persistence\Propel\PriceDimensionQueryExpander\DefaultPriceQueryExpander;
use Spryker\Zed\PriceProduct\Persistence\Propel\PriceDimensionQueryExpander\DefaultPriceQueryExpanderInterface;
use Spryker\Zed\PriceProduct\PriceProductDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProduct\PriceProductConfig getConfig()
 * @method \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface getQueryContainer()
 */
class PriceProductPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery
     */
    public function createPriceTypeQuery()
    {
        return SpyPriceTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function createPriceProductQuery()
    {
        return SpyPriceProductQuery::create();
    }

    /**
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductDefaultQuery
     */
    public function createPriceProductDefaultQuery(): SpyPriceProductDefaultQuery
    {
        return SpyPriceProductDefaultQuery::create();
    }

    /**
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    public function createPriceProductStoreQuery()
    {
        return SpyPriceProductStoreQuery::create();
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Persistence\Propel\Mapper\PriceProductMapperInterface
     */
    public function createPropelPriceProductMapper(): PriceProductMapperInterface
    {
        return new PriceProductMapper();
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Persistence\PriceProductDimensionQueryExpanderInterface
     */
    public function createPriceProductDimensionQueryExpander(): PriceProductDimensionQueryExpanderInterface
    {
        return new PriceProductDimensionQueryExpander($this->getPriceDimensionQueryCriteriaPlugins());
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Persistence\Propel\PriceDimensionQueryExpander\DefaultPriceQueryExpanderInterface
     */
    public function createDefaultPriceQueryExpander(): DefaultPriceQueryExpanderInterface
    {
        return new DefaultPriceQueryExpander();
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Dependency\Plugin\PriceDimensionQueryCriteriaPluginInterface[]
     */
    public function getPriceDimensionQueryCriteriaPlugins(): array
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::PLUGIN_PRICE_DIMENSION_QUERY_CRITERIA);
    }
}
