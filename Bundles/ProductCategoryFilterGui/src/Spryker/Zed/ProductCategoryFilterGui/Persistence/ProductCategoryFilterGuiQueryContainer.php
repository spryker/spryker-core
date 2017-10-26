<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Persistence;

use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductCategoryFilterGui\Persistence\ProductCategoryFilterGuiPersistenceFactory getFactory()
 */
class ProductCategoryFilterGuiQueryContainer extends AbstractQueryContainer implements ProductCategoryFilterGuiQueryContainerInterface
{
    /**
     * @param int $idCategory
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryCategoryByIdAndLocale($idCategory, $idLocale)
    {
        return $this->getFactory()->getCategoryQueryContainer()
                ->queryAttributeByCategoryId($idCategory)
                ->joinLocale()
                ->filterByFkLocale($idLocale)
                ->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME);
    }

    /**
     * @param string $searchText
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryFilterSuggestions($searchTerm, $idLocale)
    {
        $searchTerm = trim($searchTerm);

        $query = $this->getFactory()
            ->createCategoryAttributeQuery()
            ->addJoin(
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY,
                SpyCategoryNodeTableMap::COL_FK_CATEGORY,
                Criteria::RIGHT_JOIN
            )
            ->addJoin(
                [SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, SpyCategoryAttributeTableMap::COL_FK_LOCALE],
                [SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE, SpyUrlTableMap::COL_FK_LOCALE],
                Criteria::RIGHT_JOIN
            )
            ->filterByFkLocale($idLocale)
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, 'name')
            ->withColumn(SpyUrlTableMap::COL_URL, 'url')
            ->setFormatter(new PropelArraySetFormatter())
            ->where(
                'LOWER(' . SpyCategoryAttributeTableMap::COL_NAME . ') LIKE ?',
                '%' . mb_strtolower($$searchTerm) . '%'
            );

        return $query;
    }
}
