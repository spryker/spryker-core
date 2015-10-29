<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Persistence\QueryExpander;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Exception\PropelException;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;

class ProductCategoryPathQueryExpander
{

    /**
     * @var LocaleTransfer
     */
    protected $locale;

    /**
     * @var CategoryQueryContainer
     */
    protected $categoryQueryContainer;

    /**
     * @param CategoryQueryContainer $categoryQueryContainer
     * @param LocaleTransfer $locale
     */
    public function __construct(
        CategoryQueryContainer $categoryQueryContainer,
        LocaleTransfer $locale
    ) {
        $this->locale = $locale;
        $this->categoryQueryContainer = $categoryQueryContainer;
    }

    /**
     * @param ModelCriteria $expandableQuery
     * @param bool $excludeDirectParent
     * @param bool $excludeRoot
     *
     * @throws PropelException
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, $excludeDirectParent = true, $excludeRoot = true)
    {
        $expandableQuery
            ->addJoin(
                SpyTouchTableMap::COL_ITEM_ID,
                SpyProductCategoryTableMap::COL_FK_ABSTRACT_PRODUCT,
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
