<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerFeature\Zed\ProductCategory\Persistence\Propel\SpyProductCategoryQuery;

interface ProductCategoryQueryContainerInterface
{

    /**
     * @param ModelCriteria $query
     * @param LocaleTransfer $locale
     * @param bool $excludeDirectParent
     * @param bool $excludeRoot
     *
     * @return ModelCriteria
     */
    public function expandProductCategoryPathQuery(ModelCriteria $query, LocaleTransfer $locale, $excludeDirectParent = true, $excludeRoot = true);

    /**
     * @param int $idAbstractProduct
     * @param int $idCategoryNode
     *
     * @return SpyProductCategoryQuery
     */
    public function queryProductCategoryMappingByIds($idAbstractProduct, $idCategoryNode);

    /**
     * @param string $sku
     * @param string $categoryName
     * @param LocaleTransfer $locale
     *
     * @return SpyProductCategoryQuery
     */
    public function queryLocalizedProductCategoryMappingBySkuAndCategoryName($sku, $categoryName, LocaleTransfer $locale);

}
