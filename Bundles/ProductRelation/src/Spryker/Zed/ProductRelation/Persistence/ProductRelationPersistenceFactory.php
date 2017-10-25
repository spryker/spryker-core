<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Persistence;

use Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstractQuery;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationTypeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductRelation\Persistence\Rule\ProductRelationRuleQueryCreator;
use Spryker\Zed\ProductRelation\Persistence\Rule\Query\ProductQuery;
use Spryker\Zed\ProductRelation\ProductRelationDependencyProvider;

/**
 * @method \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductRelation\ProductRelationConfig getConfig()
 */
class ProductRelationPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    public function createProductRelationQuery()
    {
        return SpyProductRelationQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationTypeQuery
     */
    public function createProductRelationTypeQuery()
    {
        return SpyProductRelationTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstractQuery
     */
    public function createProductRelationProductAbstractQuery()
    {
        return SpyProductRelationProductAbstractQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Persistence\Rule\ProductRelationRuleQueryCreatorInterface
     */
    public function createCatalogPriceRuleQueryCreator()
    {
        return new ProductRelationRuleQueryCreator(
            $this->getQueryPropelQueryBuilderContainer(),
            $this->createProductQuery()
        );
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Persistence\Rule\Query\QueryInterface
     */
    protected function createProductQuery()
    {
        return new ProductQuery($this->getProductQueryContainer(), $this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductRelationDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Dependency\QueryContainer\ProductRelationToPropelQueryBuilderInterface
     */
    protected function getQueryPropelQueryBuilderContainer()
    {
        return $this->getProvidedDependency(ProductRelationDependencyProvider::QUERY_CONTAINER_PROPEL_QUERY_BUILDER);
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Dependency\QueryContainer\ProductRelationToProductInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(ProductRelationDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }
}
