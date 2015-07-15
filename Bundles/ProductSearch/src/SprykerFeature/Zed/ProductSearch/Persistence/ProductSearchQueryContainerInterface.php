<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductSearch\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductQuery;
use SprykerFeature\Zed\ProductSearch\Persistence\Propel\SpyProductSearchAttributesOperationQuery;

interface ProductSearchQueryContainerInterface
{

    /**
     * @return SpyProductSearchAttributesOperationQuery
     */
    public function queryFieldOperations();

    /**
     * @param array $productIds
     * @param LocaleTransfer $locale
     *
     * @return SpyProductQuery
     */
    public function getExportableProductsByLocale(array $productIds, LocaleTransfer $locale);

    /**
     * @param int $idAttribute
     * @param string $copyTarget
     *
     * @return SpyProductSearchAttributesOperationQuery
     */
    public function queryAttributeOperation($idAttribute, $copyTarget);

    /**
     * @param ModelCriteria $expandableQuery
     * @param LocaleTransfer $locale
     *
     * @return ModelCriteria
     */
    public function expandProductQuery(ModelCriteria $expandableQuery, LocaleTransfer $locale);

}
