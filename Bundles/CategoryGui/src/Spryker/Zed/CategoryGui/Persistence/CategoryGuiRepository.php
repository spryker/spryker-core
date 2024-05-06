<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Persistence;

use Generated\Shared\Transfer\CategoryTransfer;
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
    /**
     * @var string
     */
    protected const ID_CATEGORY_TEMPLATE = 'idCategoryTemplate';

    /**
     * @var string
     */
    protected const CATEGORY_TEMPLATE_NAME = 'name';

    /**
     * @var string
     */
    protected const CHILDREN_ID_CATEGORY_NODE = 'id';

    /**
     * @var string
     */
    protected const CHILDREN_CATEGORY_ATTRIBUTE_NAME = 'text';

    /**
     * @module Category
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return bool
     */
    public function isCategoryKeyUsed(CategoryTransfer $categoryTransfer): bool
    {
        return $this->getFactory()
            ->getCategoryPropelQuery()
            ->filterByCategoryKey($categoryTransfer->getCategoryKeyOrFail())
            ->filterByIdCategory($categoryTransfer->getIdCategory(), Criteria::NOT_EQUAL)
            ->count() > 0;
    }

    /**
     * @module Category
     *
     * @return array<string>
     */
    public function getIndexedCategoryTemplateNames(): array
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $categoryTemplateCollection */
        $categoryTemplateCollection = $this->getFactory()
            ->getCategoryTemplatePropelQuery()
            ->find();

        return $categoryTemplateCollection->toKeyValue(static::ID_CATEGORY_TEMPLATE, static::CATEGORY_TEMPLATE_NAME);
    }

    /**
     * @module Category
     *
     * @param int $idParentNode
     * @param int $idLocale
     *
     * @return array<string>
     */
    public function getChildrenCategoryNodeNames(int $idParentNode, int $idLocale): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $categoryNodeNameCollection */
        $categoryNodeNameCollection = $this->getFactory()
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
            ->find();

        return $categoryNodeNameCollection->toArray();
    }

    /**
     * @module Category
     * @module Store
     *
     * @param array<int> $categoryIds
     *
     * @return array<array<string>>
     */
    public function getCategoryStoreNamesGroupedByIdCategory(array $categoryIds): array
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $categoryStoreNameCollection */
        $categoryStoreNameCollection = $this->getFactory()->getCategoryPropelQuery()
            ->filterByIdCategory_In($categoryIds)
            ->leftJoinWithSpyCategoryStore()
            ->useSpyCategoryStoreQuery()
                ->leftJoinWithSpyStore()
            ->endUse()
            ->select([
                SpyStoreTableMap::COL_NAME,
                SpyCategoryTableMap::COL_ID_CATEGORY,
            ])->find();
        $categoryStoreNames = $categoryStoreNameCollection->toArray();

        $categoryStoreNamesGroupedByIdCategory = [];
        foreach ($categoryStoreNames as $categoryStoreName) {
            $idCategory = $categoryStoreName[SpyCategoryTableMap::COL_ID_CATEGORY];
            $storeName = $categoryStoreName[SpyStoreTableMap::COL_NAME];
            $categoryStoreNamesGroupedByIdCategory[$idCategory][] = $storeName;
        }

        return $categoryStoreNamesGroupedByIdCategory;
    }
}
