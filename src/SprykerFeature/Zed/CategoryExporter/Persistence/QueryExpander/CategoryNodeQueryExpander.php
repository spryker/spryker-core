<?php

namespace SprykerFeature\Zed\CategoryExporter\Persistence\QueryExpander;

use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryAttributeTableMap;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryNodeTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchTableMap;

class CategoryNodeQueryExpander
{
    /**
     * @var int
     */
    protected $localeId;

    /**
     * @var CategoryQueryContainer
     */
    protected $categoryQueryContainer;

    /**
     * @param CategoryQueryContainer $categoryQueryContainer
     * @param int $localeId
     */
    public function __construct(CategoryQueryContainer $categoryQueryContainer, $localeId)
    {
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->localeId = $localeId;
    }

    /**
     * @param ModelCriteria $expandableQuery
     *
     * @return ModelCriteria
     * @throws PropelException
     */
    public function expandQuery(ModelCriteria $expandableQuery)
    {
        $expandableQuery
            ->addJoin(
                SpyTouchTableMap::COL_ITEM_ID,
                SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE,
                Criteria::LEFT_JOIN
            );
        $expandableQuery
            ->addJoin(
                SpyCategoryNodeTableMap::COL_FK_CATEGORY,
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY,
                Criteria::INNER_JOIN
            );
        $expandableQuery->addAnd(
            SpyCategoryAttributeTableMap::COL_FK_LOCALE,
            $this->localeId,
            Criteria::EQUAL
        );

        $expandableQuery = $this->categoryQueryContainer->joinCategoryQueryWithUrls($expandableQuery);
        $expandableQuery = $this->categoryQueryContainer->selectCategoryAttributeColumns($expandableQuery);

        $expandableQuery = $this->categoryQueryContainer->joinCategoryQueryWithChildrenCategories($expandableQuery);
        $expandableQuery = $this->categoryQueryContainer->joinLocalizedRelatedCategoryQueryWithAttributes($expandableQuery, 'categoryChildren', 'child');
        $expandableQuery = $this->categoryQueryContainer->joinRelatedCategoryQueryWithUrls($expandableQuery, 'categoryChildren', 'child');

        $expandableQuery = $this->categoryQueryContainer->joinCategoryQueryWithParentCategories($expandableQuery, true, false);
        $expandableQuery = $this->categoryQueryContainer->joinLocalizedRelatedCategoryQueryWithAttributes($expandableQuery, 'categoryParents', 'parent');
        $expandableQuery = $this->categoryQueryContainer->joinRelatedCategoryQueryWithUrls($expandableQuery, 'categoryParents', 'parent');

        $expandableQuery->withColumn(
            SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE,
            'node_id'
        );
        $expandableQuery->withColumn(
            SpyCategoryNodeTableMap::COL_FK_CATEGORY,
            'category_id'
        );

        $expandableQuery->orderBy('depth', Criteria::DESC);
        $expandableQuery->orderBy('descendant_id', Criteria::DESC);
        $expandableQuery->groupBy('category_id');

        return $expandableQuery;
    }
}
