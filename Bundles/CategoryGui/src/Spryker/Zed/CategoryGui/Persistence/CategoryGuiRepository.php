<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Persistence;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CategoryGui\Persistence\CategoryGuiPersistenceFactory getFactory()
 */
class CategoryGuiRepository extends AbstractRepository implements CategoryGuiRepositoryInterface
{
    protected const ID_CATEGORY_TEMPLATE = 'idCategoryTemplate';
    protected const CATEGORY_TEMPLATE_NAME = 'name';

    protected const CHILDREN_ID_CATEGORY_NODE = 'id';
    protected const CHILDREN_CATEGORY_ATTRIBUTE_NAME = 'text';

    /**
     * @param string $categoryKey
     *
     * @return bool
     */
    public function isCategoryKeyUsed(string $categoryKey): bool
    {
        return $this->getFactory()
            ->getCategoryPropelQuery()
            ->filterByCategoryKey($categoryKey)
            ->count() > 0;
    }

    /**
     * @return string[]
     */
    public function getIndexedCategoryTemplateNames(): array
    {
        return $this->getFactory()
            ->getCategoryTemplatePropelQuery()
            ->find()
            ->toKeyValue(static::ID_CATEGORY_TEMPLATE, static::CATEGORY_TEMPLATE_NAME);
    }

    /**
     * @param int $idParentNode
     * @param int $idLocale
     *
     * @return string[]
     */
    public function getChildrenCategoryNodeNames(int $idParentNode, int $idLocale): array
    {
        return $this->getFactory()
            ->getCategoryNodePropelQuery()
            ->filterByFkParentCategoryNode($idParentNode)
            ->useCategoryQuery()
                ->innerJoinAttribute()
                ->addAnd(SpyCategoryAttributeTableMap::COL_FK_LOCALE, $idLocale, Criteria::EQUAL)
            ->endUse()
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, static::CHILDREN_CATEGORY_ATTRIBUTE_NAME)
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, static::CHILDREN_ID_CATEGORY_NODE)
            ->orderBy(SpyCategoryNodeTableMap::COL_NODE_ORDER, Criteria::DESC)
            ->select([static::CHILDREN_ID_CATEGORY_NODE, static::CHILDREN_CATEGORY_ATTRIBUTE_NAME])
            ->find()
            ->toArray();
    }

    /**
     * @module Category
     * @module Store
     *
     * @param int[] $categoryIds
     *
     * @return string[][]
     */
    public function getCategoryStoreNamesGroupedByIdCategory(array $categoryIds): array
    {
        $categoryStoreNames = $this->getFactory()->getCategoryPropelQuery()
            ->filterByIdCategory_In($categoryIds)
            ->leftJoinWithSpyCategoryStore()
            ->useSpyCategoryStoreQuery()
                ->leftJoinWithSpyStore()
            ->endUse()
            ->select([
                SpyStoreTableMap::COL_NAME,
                SpyCategoryTableMap::COL_ID_CATEGORY,
            ])->find()->toArray();

        $categoryStoreNamesGroupedByIdCategory = [];
        foreach ($categoryStoreNames as $categoryStoreName) {
            $idCategory = $categoryStoreName[SpyCategoryTableMap::COL_ID_CATEGORY];
            $storeName = $categoryStoreName[SpyStoreTableMap::COL_NAME];
            $categoryStoreNamesGroupedByIdCategory[$idCategory][] = $storeName;
        }

        return $categoryStoreNamesGroupedByIdCategory;
    }
}
