<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Persistence\QueryExpander;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

/**
 * @deprecated Will be removed with next major release
 */
class ProductCategoryPathQueryExpander
{

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $locale;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $categoryQueryContainer
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     */
    public function __construct(
        CategoryQueryContainerInterface $categoryQueryContainer,
        LocaleTransfer $locale
    ) {
        $this->locale = $locale;
        $this->categoryQueryContainer = $categoryQueryContainer;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     * @param bool $excludeDirectParent
     * @param bool $excludeRoot
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, $excludeDirectParent = true, $excludeRoot = true)
    {
        $expandableQuery
            ->addJoin(
                SpyTouchTableMap::COL_ITEM_ID,
                SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT,
                Criteria::LEFT_JOIN
            );
        $expandableQuery
            ->addJoin(
                SpyProductCategoryTableMap::COL_FK_CATEGORY_NODE,
                SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE,
                Criteria::INNER_JOIN
            );
        $expandableQuery
            ->addJoin(
                SpyCategoryNodeTableMap::COL_FK_CATEGORY,
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY,
                Criteria::INNER_JOIN
            );

        $expandableQuery = $this->categoryQueryContainer->joinCategoryQueryWithUrls($expandableQuery);
        $expandableQuery = $this->categoryQueryContainer->selectCategoryAttributeColumns($expandableQuery);

        $expandableQuery = $this->categoryQueryContainer->joinCategoryQueryWithChildrenCategories($expandableQuery);
        $expandableQuery = $this->categoryQueryContainer->joinLocalizedRelatedCategoryQueryWithAttributes($expandableQuery, 'categoryChildren', 'child');
        $expandableQuery = $this->categoryQueryContainer->joinRelatedCategoryQueryWithUrls($expandableQuery, 'categoryChildren', 'child');

        $expandableQuery = $this->categoryQueryContainer->joinCategoryQueryWithParentCategories($expandableQuery, $excludeDirectParent, $excludeRoot);
        $expandableQuery = $this->categoryQueryContainer->joinLocalizedRelatedCategoryQueryWithAttributes($expandableQuery, 'categoryParents', 'parent');
        $expandableQuery = $this->categoryQueryContainer->joinRelatedCategoryQueryWithUrls($expandableQuery, 'categoryParents', 'parent');

        $expandableQuery->withColumn(
            'GROUP_CONCAT(DISTINCT spy_category_node.id_category_node)',
            'node_id'
        );
        $expandableQuery->withColumn(
            SpyCategoryNodeTableMap::COL_FK_CATEGORY,
            'category_id'
        );
        $expandableQuery->orderBy('depth', Criteria::DESC);
        $expandableQuery->orderBy('descendant_id', Criteria::DESC);
        $expandableQuery->groupBy('abstract_sku');

        return $expandableQuery;
    }

}
