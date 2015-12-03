<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductCategoryQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param bool $excludeDirectParent
     * @param bool $excludeRoot
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function expandProductCategoryPathQuery(ModelCriteria $query, LocaleTransfer $locale, $excludeDirectParent = true, $excludeRoot = true);

    /**
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductCategoryMappingsByCategoryId($idCategory);

    /**
     * @param int $idProductAbstract
     * @param int $idCategoryNode
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductCategoryMappingByIds($idProductAbstract, $idCategoryNode);

    /**
     * @param string $sku
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryLocalizedProductCategoryMappingBySkuAndCategoryName($sku, $categoryName, LocaleTransfer $locale);

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryLocalizedProductCategoryMappingByIdProduct($idProductAbstract);

    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryProductsByCategoryId($idCategory, LocaleTransfer $locale);

    /**
     * @param string $term
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param null $idExcludedCategory
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractCollectionBySearchTerm($term, LocaleTransfer $locale, $idExcludedCategory = null);

}
