<?php

namespace SprykerFeature\Zed\ProductSearch\Persistence;

use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductQuery;
use SprykerFeature\Zed\ProductSearch\Persistence\Propel\SpyProductSearchAttributesOperationQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;

interface ProductSearchQueryContainerInterface
{
    /**
     * @return SpyProductSearchAttributesOperationQuery
     */
    public function queryFieldOperations();

    /**
     * @param array $productIds
     * @param string $locale
     *
     * @return SpyProductQuery
     */
    public function getExportableProductsByLocale(array $productIds, $locale);

    /**
     * @param int $idAttribute
     * @param string $copyTarget
     *
     * @return SpyProductSearchAttributesOperationQuery
     */
    public function queryAttributeOperation($idAttribute, $copyTarget);

    /**
     * @param ModelCriteria $expandableQuery
     * @param string $localeName
     *
     * @return ModelCriteria
     */
    public function expandProductQuery(ModelCriteria $expandableQuery, $localeName);
}
