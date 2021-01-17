<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Persistence;

use Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery;
use Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Orm\Zed\Category\Persistence\SpyCategoryStoreQuery;
use Orm\Zed\Category\Persistence\SpyCategoryTemplateQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Category\Persistence\Propel\Mapper\CategoryLocalizedAttributeMapper;
use Spryker\Zed\Category\Persistence\Propel\Mapper\CategoryLocalizedAttributesUrlMapper;
use Spryker\Zed\Category\Persistence\Propel\Mapper\CategoryMapper;
use Spryker\Zed\Category\Persistence\Propel\Mapper\CategoryMapperInterface;
use Spryker\Zed\Category\Persistence\Propel\Mapper\CategoryNodeMapper;
use Spryker\Zed\Category\Persistence\Propel\Mapper\CategoryStoreRelationMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Category\CategoryConfig getConfig()
 * @method \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface getRepository()
 * @method \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface getEntityManager()
 */
class CategoryPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @param string|null $modelAlias
     * @param \Propel\Runtime\ActiveQuery\Criteria|null $criteria
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function createCategoryNodeQuery($modelAlias = null, ?Criteria $criteria = null)
    {
        return SpyCategoryNodeQuery::create($modelAlias, $criteria);
    }

    /**
     * @param string|null $modelAlias
     * @param \Propel\Runtime\ActiveQuery\Criteria|null $criteria
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function createUrlQuery($modelAlias = null, ?Criteria $criteria = null)
    {
        return SpyUrlQuery::create($modelAlias, $criteria);
    }

    /**
     * @param string|null $modelAlias
     * @param \Propel\Runtime\ActiveQuery\Criteria|null $criteria
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function createCategoryAttributeQuery($modelAlias = null, ?Criteria $criteria = null)
    {
        return SpyCategoryAttributeQuery::create($modelAlias, $criteria);
    }

    /**
     * @param string|null $modelAlias
     * @param \Propel\Runtime\ActiveQuery\Criteria|null $criteria
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function createCategoryQuery($modelAlias = null, ?Criteria $criteria = null)
    {
        return SpyCategoryQuery::create($modelAlias, $criteria);
    }

    /**
     * @param string|null $modelAlias
     * @param \Propel\Runtime\ActiveQuery\Criteria|null $criteria
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryClosureTableQuery
     */
    public function createCategoryClosureTableQuery($modelAlias = null, ?Criteria $criteria = null)
    {
        return SpyCategoryClosureTableQuery::create($modelAlias, $criteria);
    }

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryTemplateQuery
     */
    public function createCategoryTemplateQuery()
    {
        return SpyCategoryTemplateQuery::create();
    }

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryStoreQuery
     */
    public function createCategoryStoreQuery(): SpyCategoryStoreQuery
    {
        return SpyCategoryStoreQuery::create();
    }

    /**
     * @return \Spryker\Zed\Category\Persistence\Propel\Mapper\CategoryMapperInterface
     */
    public function createCategoryMapper(): CategoryMapperInterface
    {
        return new CategoryMapper(
            $this->createCategoryNodeMapper(),
            $this->createCategoryStoreRelationMapper(),
            $this->createCategoryLocalizedAttributesUrlMapper()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Persistence\Propel\Mapper\CategoryLocalizedAttributeMapper
     */
    public function createCategoryLocalizedAttributeMapper(): CategoryLocalizedAttributeMapper
    {
        return new CategoryLocalizedAttributeMapper();
    }

    /**
     * @return \Spryker\Zed\Category\Persistence\Propel\Mapper\CategoryNodeMapper
     */
    public function createCategoryNodeMapper(): CategoryNodeMapper
    {
        return new CategoryNodeMapper();
    }

    /**
     * @return \Spryker\Zed\Category\Persistence\Propel\Mapper\CategoryStoreRelationMapper
     */
    public function createCategoryStoreRelationMapper(): CategoryStoreRelationMapper
    {
        return new CategoryStoreRelationMapper();
    }

    /**
     * @return \Spryker\Zed\Category\Persistence\Propel\Mapper\CategoryLocalizedAttributesUrlMapper
     */
    public function createCategoryLocalizedAttributesUrlMapper(): CategoryLocalizedAttributesUrlMapper
    {
        return new CategoryLocalizedAttributesUrlMapper();
    }
}
