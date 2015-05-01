<?php

namespace SprykerFeature\Zed\ProductSearch\Persistence;

use SprykerEngine\Shared\Dto\LocaleDto;
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
     * @param LocaleDto $locale
     *
     * @return SpyProductQuery
     */
    public function getExportableProductsByLocale(array $productIds, LocaleDto $locale);

    /**
     * @param int $idAttribute
     * @param string $copyTarget
     *
     * @return SpyProductSearchAttributesOperationQuery
     */
    public function queryAttributeOperation($idAttribute, $copyTarget);

    /**
     * @param ModelCriteria $expandableQuery
     * @param LocaleDto $locale
     *
     * @return ModelCriteria
     */
    public function expandProductQuery(ModelCriteria $expandableQuery, LocaleDto $locale);
}
