<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Persistence;

use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStoragePersistenceFactory getFactory()
 */
class CategoryStorageQueryContainer extends AbstractQueryContainer implements CategoryStorageQueryContainerInterface
{
    /**
     * @var string
     */
    public const ID_CATEGORY_NODE = 'idCategoryNode';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $categoryNodeIds
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryCategoryNodeByIds(array $categoryNodeIds): SpyCategoryNodeQuery
    {
        $query = $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryAllCategoryNodes()
            ->filterByIdCategoryNode_In($categoryNodeIds);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorageQuery
     */
    public function queryCategoryStorage()
    {
        return $this->getFactory()
            ->createSpyCategoryTreeStorageQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $categoryIds
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryCategoryNodeIdsByCategoryIds(array $categoryIds)
    {
        return $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryAllCategoryNodes()
            ->filterByFkCategory_In($categoryIds)
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, static::ID_CATEGORY_NODE)
            ->select([static::ID_CATEGORY_NODE]);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $categoryNodeIds
     *
     * @return \Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorageQuery
     */
    public function queryCategoryNodeStorageByIds(array $categoryNodeIds)
    {
        return $this->getFactory()
            ->createSpyCategoryNodeStorageQuery()
            ->filterByFkCategoryNode_In($categoryNodeIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $categoryTemplateIds
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryCategoryNodeIdsByTemplateIds(array $categoryTemplateIds)
    {
        return $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryAllCategoryNodes()
            ->useCategoryQuery()
                ->filterByFkCategoryTemplate_In($categoryTemplateIds)
            ->endUse()
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, static::ID_CATEGORY_NODE)
            ->select([static::ID_CATEGORY_NODE]);
    }
}
