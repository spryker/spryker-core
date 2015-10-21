<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Product\Business\Exception\MissingProductException;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProduct;
use SprykerFeature\Zed\ProductCategory\Business\Exception\MissingCategoryNodeException;
use SprykerFeature\Zed\ProductCategory\Business\Exception\ProductCategoryMappingExistsException;
use SprykerFeature\Zed\ProductCategory\Persistence\Propel\SpyProductCategoryQuery;

interface ProductCategoryManagerInterface
{

    /**
     * @param string $sku
     * @param string $categoryName
     * @param LocaleTransfer $locale
     *
     * @return bool
     */
    public function hasProductCategoryMapping($sku, $categoryName, LocaleTransfer $locale);

    /**
     * @param string $sku
     * @param string $categoryName
     * @param LocaleTransfer $locale
     *
     * @throws ProductCategoryMappingExistsException
     * @throws MissingProductException
     * @throws MissingCategoryNodeException
     * @throws PropelException
     *
     * @return int
     */
    public function createProductCategoryMapping($sku, $categoryName, LocaleTransfer $locale);

    /**
     * @param int $idCategory
     *
     * @param LocaleTransfer $locale
     * @return SpyProductCategoryQuery[]
     */
    public function getProductsByCategory($idCategory, LocaleTransfer $locale);
    
    /**
     * @param SpyAbstractProduct $abstractProduct
     *
     * @return SpyProductCategoryQuery
     */
    public function getCategoriesByAbstractProduct(SpyAbstractProduct $abstractProduct);

    /**
     * @param int $idCategory
     * @param int $idAbstractProduct
     *
     * @return SpyProductCategoryQuery
     */
    public function getProductCategoryMappingById($idCategory, $idAbstractProduct);

    /**
     * @param int $idCategory
     * @param array $productIdsToAssign
     *
     * @throws PropelException
     */
    public function createProductCategoryMappings($idCategory, array $productIdsToAssign);

    /**
     * @param int $idCategory
     * @param array $productIdsToDeassign
     */
    public function removeProductCategoryMappings($idCategory, array $productIdsToDeassign);

    /**
     * @param $idCategory
     * @param array $productOrderList
     * @throws PropelException
     */
    public function updateProductMappingsOrder($idCategory, array $productOrderList);

    /**
     * @param int $idCategory
     * @param array $productPreconfigList
     * @throws PropelException
     */
    public function updateProductMappingsPreconfig($idCategory, array $productPreconfigList);

    /**
     * @param NodeTransfer $sourceNodeTransfer
     * @param NodeTransfer $destinationNodeTransfer
     * @param LocaleTransfer $localeTransfer
     * @return
     */
    public function moveCategoryChildrenAndDeleteNode(NodeTransfer $sourceNodeTransfer, NodeTransfer $destinationNodeTransfer, LocaleTransfer $localeTransfer);

    /**
     * @param int $idCategory
     * @param LocaleTransfer $localeTransfer
     */
    public function deleteCategoryRecursive($idCategory, LocaleTransfer $localeTransfer);

}
