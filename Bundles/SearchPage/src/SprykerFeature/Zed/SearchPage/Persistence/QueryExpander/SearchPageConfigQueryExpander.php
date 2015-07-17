<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Persistence\QueryExpander;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Criterion\BasicCriterion;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchTableMap;
use SprykerFeature\Zed\SearchPage\Persistence\Propel\Map\SpySearchDocumentAttributeTableMap;
use SprykerFeature\Zed\SearchPage\Persistence\Propel\Map\SpySearchPageElementTableMap;
use SprykerFeature\Zed\SearchPage\Persistence\Propel\Map\SpySearchPageElementTemplateTableMap;

class SearchPageConfigQueryExpander implements SearchPageConfigQueryExpanderInterface
{

    /**
     * @param ModelCriteria $expandableQuery
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery)
    {
        $join = new Join();
        $join
            ->setLeftTableName(SpyTouchTableMap::TABLE_NAME)
            ->setRightTableName(SpySearchPageElementTableMap::TABLE_NAME)
            ->setJoinCondition(new BasicCriterion(new Criteria(), 'is_element_active', '1'))
        ;
        $expandableQuery->addJoinObject($join);
        $expandableQuery->addJoin(
            SpySearchPageElementTableMap::COL_FK_SEARCH_DOCUMENT_ATTRIBUTE,
            SpySearchDocumentAttributeTableMap::COL_ID_SEARCH_DOCUMENT_ATTRIBUTE,
            Criteria::INNER_JOIN
        );
        $expandableQuery->addJoin(
            SpySearchPageElementTableMap::COL_FK_SEARCH_PAGE_ELEMENT_TEMPLATE,
            SpySearchPageElementTemplateTableMap::COL_ID_SEARCH_PAGE_ELEMENT_TEMPLATE,
            Criteria::INNER_JOIN
        );
        $expandableQuery->addAnd(
            SpySearchPageElementTableMap::COL_IS_ELEMENT_ACTIVE,
            true,
            Criteria::EQUAL
        );
        $expandableQuery->withColumn(SpySearchPageElementTableMap::COL_ELEMENT_KEY, 'element_key');
        $expandableQuery->withColumn(SpySearchPageElementTemplateTableMap::COL_TEMPLATE_NAME, 'template_name');
        $expandableQuery->withColumn(SpySearchDocumentAttributeTableMap::COL_ATTRIBUTE_NAME, 'attribute_name');
        $expandableQuery->withColumn(SpySearchDocumentAttributeTableMap::COL_ATTRIBUTE_TYPE, 'attribute_type');

        return $expandableQuery;
    }

}
