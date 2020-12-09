<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Persistence;

use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
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
     * @uses \Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap::COL_LOCALE_NAME
     */
    protected const COL_LOCALE_NAME = 'spy_locale.locale_name';

    /**
     * @param string $categoryKey
     * @param int $idCategory
     *
     * @return bool
     */
    public function isCategoryKeyUsed(string $categoryKey, int $idCategory): bool
    {
        return $this->getFactory()
            ->getCategoryPropelQuery()
            ->filterByCategoryKey($categoryKey)
            ->filterByIdCategory($idCategory, Criteria::NOT_EQUAL)
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
     * @return array
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
     * @param int[] $categoryNodeIds
     *
     * @return \Generated\Shared\Transfer\UrlTransfer[]
     */
    public function getCategoryNodeUrls(array $categoryNodeIds): array
    {
        $urlEntities = $this->getFactory()
            ->getUrlPropelQuery()
            ->joinSpyLocale()
            ->filterByFkResourceCategorynode_In(array_unique($categoryNodeIds))
            ->withColumn(static::COL_LOCALE_NAME)
            ->find();

        $urlTransfers = [];

        foreach ($urlEntities as $urlEntity) {
            $urlTransfers[] = (new UrlTransfer())->fromArray($urlEntity->toArray(), true);
        }

        return $urlTransfers;
    }
}
