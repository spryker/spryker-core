<?php

namespace SprykerFeature\Zed\ProductSearch\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedAbstractProductAttributesTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyLocalizedProductAttributesTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyProductTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductQuery;
use SprykerFeature\Zed\ProductSearch\Persistence\Propel\Map\SpyProductSearchAttributesOperationTableMap;
use SprykerFeature\Zed\ProductSearch\Persistence\Propel\SpyProductSearchAttributesOperationQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;

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
     * @param array     $productIds
     * @param string    $locale
     *
     * @return SpyProductQuery
     */
    public function getExportableProductsByLocale(array $productIds, $locale)
    {
        $query = SpyProductQuery::create();
        $query
            ->filterByIdProduct($productIds)
            ->useSpyLocalizedProductAttributesQuery()
                ->useLocaleQuery()
                    ->filterByLocaleName($locale)
                ->endUse()
            ->endUse()
            ->addSelectColumn(SpyProductTableMap::COL_SKU)
            ->addSelectColumn(SpyLocalizedProductAttributesTableMap::COL_ATTRIBUTES)
            ->addSelectColumn(SpyLocalizedProductAttributesTableMap::COL_NAME);
        $query
            ->useSpyAbstractProductQuery()
                ->useSpyLocalizedAbstractProductAttributesQuery()
                    ->useLocaleQuery()
                        ->filterByLocaleName($locale)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->addAsColumn(
                '/**
 * @method ProductSearchDependencyContainer getDependencyContainer()
 */
abstract_attributes',
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
     * @param string $localeName
     *
     * @return ModelCriteria
     */
    public function expandProductQuery(ModelCriteria $expandableQuery, $localeName)
    {
        $productSearchQueryExpander = $this->getDependencyContainer()->createProductSearchQueryExpander();

        return $productSearchQueryExpander->expandProductQuery($expandableQuery, $localeName);
    }
}
