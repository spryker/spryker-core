<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductSearch\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Product\Persistence\Map\SpyLocalizedAbstractProductAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyLocalizedProductAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchAttributesOperationTableMap;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributesOperationQuery;

/**
 * @method ProductSearchDependencyContainer getDependencyContainer()
 */
class ProductSearchQueryContainer extends AbstractQueryContainer implements ProductSearchQueryContainerInterface
{

    /**
     * @return SpyProductSearchAttributesOperationQuery
     */
    public function queryFieldOperations()
    {
        $fieldOperations = SpyProductSearchAttributesOperationQuery::create()
            ->joinWith('SpyProductAttributesMetadata')
            ->addAscendingOrderByColumn(
                SpyProductSearchAttributesOperationTableMap::COL_SOURCE_ATTRIBUTE_ID
            )
            ->addAscendingOrderByColumn(
                SpyProductSearchAttributesOperationTableMap::COL_WEIGHTING
            );

        return $fieldOperations;
    }

    /**
     * @todo CD-427 Follow naming conventions and use method name starting with 'query*'
     *
     * @param array $productIds
     * @param LocaleTransfer $locale
     *
     * @return SpyProductQuery
     */
    public function getExportableProductsByLocale(array $productIds, LocaleTransfer $locale)
    {
        $query = SpyProductQuery::create();
        $query
            ->filterByIdProduct($productIds)
            ->useSpyLocalizedProductAttributesQuery()
            ->filterByFkLocale($locale->getIdLocale())
            ->endUse()
            ->addSelectColumn(SpyProductTableMap::COL_SKU)
            ->addSelectColumn(SpyLocalizedProductAttributesTableMap::COL_ATTRIBUTES)
            ->addSelectColumn(SpyLocalizedProductAttributesTableMap::COL_NAME);
        $query
            ->useSpyAbstractProductQuery()
            ->useSpyLocalizedAbstractProductAttributesQuery()
            ->filterByFkLocale($locale->getIdLocale())
            ->endUse()
            ->endUse()
            ->addAsColumn(
                'abstract_attributes',
                SpyLocalizedAbstractProductAttributesTableMap::COL_ATTRIBUTES
            );

        return $query;
    }

    /**
     * @param int $idAttribute
     * @param string $copyTarget
     *
     * @return SpyProductSearchAttributesOperationQuery
     */
    public function queryAttributeOperation($idAttribute, $copyTarget)
    {
        $query = SpyProductSearchAttributesOperationQuery::create();
        $query
            ->filterBySourceAttributeId($idAttribute)
            ->filterByTargetField($copyTarget)
        ;

        return $query;
    }

    /**
     * @param ModelCriteria $expandableQuery
     * @param LocaleTransfer $locale
     *
     * @return ModelCriteria
     */
    public function expandProductQuery(ModelCriteria $expandableQuery, LocaleTransfer $locale)
    {
        $productSearchQueryExpander = $this->getDependencyContainer()->createProductSearchQueryExpander();

        return $productSearchQueryExpander->expandProductQuery($expandableQuery, $locale);
    }

}
