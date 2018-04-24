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
use Orm\Zed\Category\Persistence\SpyCategoryTemplateQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Category\CategoryConfig getConfig()
 * @method \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface getQueryContainer()
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
}
