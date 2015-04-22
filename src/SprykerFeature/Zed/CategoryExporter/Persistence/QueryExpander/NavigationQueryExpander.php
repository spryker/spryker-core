<?php

namespace SprykerFeature\Zed\CategoryExporter\Persistence\QueryExpander;

use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryAttributeTableMap;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryNodeTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchTableMap;

/**
 * Class NavigationQueryExpander
 * @package SprykerFeature\Zed\CategoryExporter\Business\QueryExpander
 */
class NavigationQueryExpander
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
        $this->localeId = $localeId;
        $this->categoryQueryContainer = $categoryQueryContainer;
    }

    /**
     * @param ModelCriteria $expandableQuery
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery)
    {
        $expandableQuery->clearSelectColumns();
        $expandableQuery
            ->addJoin(
                SpyTouchTableMap::COL_ITEM_ID,
                SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE,
                Criteria::LEFT_JOIN
            );

        $expandableQuery->addAnd(
            SpyCategoryAttributeTableMap::COL_FK_LOCALE,
            $this->localeId,
            Criteria::EQUAL
        );

        $expandableQuery = $this->categoryQueryContainer->joinCategoryQueryWithChildrenCategories($expandableQuery, 'rootChildren', 'rootChild');
        $expandableQuery = $this->categoryQueryContainer->joinLocalizedRelatedCategoryQueryWithAttributes($expandableQuery, 'rootChildren', 'rootChild');
        $expandableQuery = $this->categoryQueryContainer->joinCategoryQueryWithChildrenCategories($expandableQuery, 'categoryChildren', 'child', 'rootChildren');
        $expandableQuery = $this->categoryQueryContainer->joinLocalizedRelatedCategoryQueryWithAttributes($expandableQuery, 'categoryChildren', 'child');
        $expandableQuery = $this->categoryQueryContainer->joinCategoryQueryWithParentCategories($expandableQuery, true, false, 'rootChildren');
        $expandableQuery = $this->categoryQueryContainer->joinLocalizedRelatedCategoryQueryWithAttributes($expandableQuery, 'categoryParents', 'parent');
        $expandableQuery = $this->categoryQueryContainer->joinRelatedCategoryQueryWithUrls($expandableQuery, 'categoryChildren', 'child');
        $expandableQuery = $this->categoryQueryContainer->joinRelatedCategoryQueryWithUrls($expandableQuery, 'categoryParents', 'parent');
        $expandableQuery = $this->categoryQueryContainer->joinCategoryQueryWithUrls($expandableQuery, 'rootChildren');
        $expandableQuery = $this->categoryQueryContainer->selectCategoryAttributeColumns($expandableQuery, 'rootChildrenAttributes');

        $expandableQuery->withColumn(
            'rootChildren.id_category_node',
            'node_id'
        );

        $expandableQuery->orderBy('depth', Criteria::DESC);
        $expandableQuery->orderBy('descendant_id', Criteria::DESC);
        $expandableQuery->groupBy('node_id');

        return $expandableQuery;
    }
}

