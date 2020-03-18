<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Persistence;

use Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstractQuery;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationStoreQuery;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationTypeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface;
use Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\ProductMapper;
use Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\ProductRelationMapper;
use Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\ProductRelationTypeMapper;
use Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\RuleSetMapper;
use Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\StoreRelationMapper;
use Spryker\Zed\ProductRelation\Persistence\Rule\ProductRelationRuleQueryCreator;
use Spryker\Zed\ProductRelation\Persistence\Rule\Query\ProductQuery;
use Spryker\Zed\ProductRelation\ProductRelationDependencyProvider;

/**
 * @method \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductRelation\ProductRelationConfig getConfig()
 * @method \Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductRelation\Persistence\ProductRelationEntityManagerInterface getEntityManager()
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
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationStoreQuery
     */
    public function createProductRelationStoreQuery(): SpyProductRelationStoreQuery
    {
        return SpyProductRelationStoreQuery::create();
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
     * @return \Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\ProductRelationTypeMapper
     */
    public function createProductRelationTypeMapper(): ProductRelationTypeMapper
    {
        return new ProductRelationTypeMapper();
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\ProductRelationMapper
     */
    public function createProductRelationMapper(): ProductRelationMapper
    {
        return new ProductRelationMapper(
            $this->createProductRelationTypeMapper(),
            $this->createStoreRelationMapper(),
            $this->createRuleSetMapper(),
            $this->createProductMapper(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\ProductMapper
     */
    public function createProductMapper(): ProductMapper
    {
        return new ProductMapper();
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\StoreRelationMapper
     */
    public function createStoreRelationMapper(): StoreRelationMapper
    {
        return new StoreRelationMapper();
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Persistence\Propel\Mapper\RuleSetMapper
     */
    public function createRuleSetMapper(): RuleSetMapper
    {
        return new RuleSetMapper(
            $this->getUtilEncodingService()
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
     * @return \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface
     */
    public function getProductRelationQueryContainer(): ProductRelationQueryContainerInterface
    {
        return $this->getQueryContainer();
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

    /**
     * @return \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface
     */
    public function getUtilEncodingService(): ProductRelationToUtilEncodingInterface
    {
        return $this->getProvidedDependency(ProductRelationDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
