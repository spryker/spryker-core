<?php

namespace SprykerFeature\Zed\ProductCategory\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Shared\Locale\Dto\LocaleDto;
use SprykerFeature\Zed\ProductCategory\Persistence\Propel\SpyProductCategoryQuery;

interface ProductCategoryQueryContainerInterface
{
    /**
     * @param ModelCriteria $query
     * @param LocaleDto $locale
     * @param bool $excludeDirectParent
     * @param bool $excludeRoot
     *
     * @return ModelCriteria
     */
    public function expandProductCategoryPathQuery(ModelCriteria $query, LocaleDto $locale, $excludeDirectParent = true, $excludeRoot = true);

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
     * @param LocaleDto $locale
     *
     * @return SpyProductCategoryQuery
     */
    public function queryLocalizedProductCategoryMappingBySkuAndCategoryName($sku, $categoryName, LocaleDto $locale);
}
