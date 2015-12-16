<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;

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
     * @return SpyProductCategoryQuery
     */
    public function queryProductCategoryMappingsByCategoryId($idCategory);

    /**
     * @param int $idProductAbstract
     * @param int $idCategoryNode
     *
     * @return SpyProductCategoryQuery
     */
    public function queryProductCategoryMappingByIds($idProductAbstract, $idCategoryNode);

    /**
     * @param string $sku
     * @param string $categoryName
     * @param LocaleTransfer $locale
     *
     * @return SpyProductCategoryQuery
     */
    public function queryLocalizedProductCategoryMappingBySkuAndCategoryName($sku, $categoryName, LocaleTransfer $locale);

    /**
     * @param int $idProductAbstract
     *
     * @return SpyProductCategoryQuery
     */
    public function queryLocalizedProductCategoryMappingByIdProduct($idProductAbstract);

    /**
     * @param int $idCategory
     * @param LocaleTransfer $locale
     *
     * @return SpyProductCategoryQuery
     */
    public function queryProductsByCategoryId($idCategory, LocaleTransfer $locale);

    /**
     * @param $term
     * @param LocaleTransfer $locale
     * @param null $idExcludedCategory
     *
     * @return SpyProductAbstractQuery
     */
    public function queryAbstractProductsBySearchTerm($term, LocaleTransfer $locale, $idExcludedCategory = null);

}
