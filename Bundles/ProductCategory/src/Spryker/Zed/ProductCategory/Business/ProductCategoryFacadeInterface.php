<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Business;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;

interface ProductCategoryFacadeInterface
{

    /**
     * @param string $sku
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @throws \Spryker\Zed\ProductCategory\Business\Exception\ProductCategoryMappingExistsException
     * @throws \Spryker\Zed\ProductCategory\Business\Exception\MissingProductException
     * @throws \Spryker\Zed\ProductCategory\Business\Exception\MissingCategoryNodeException
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return int
     */
    public function createProductCategoryMapping($sku, $categoryName, LocaleTransfer $locale);

    /**
     * @param string $sku
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return bool
     */
    public function hasProductCategoryMapping($sku, $categoryName, LocaleTransfer $locale);

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryTransfer[]
     */
    public function getCategoriesByProductAbstract(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * @param int $idCategory
     * @param array $productIdsToAssign
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function createProductCategoryMappings($idCategory, array $productIdsToAssign);

    /**
     * @param int $idCategory
     * @param array $productIdsToUnAssign
     *
     * @return void
     */
    public function removeProductCategoryMappings($idCategory, array $productIdsToUnAssign);

    /**
     * @param int $idCategory
     * @param array $productOrderList
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function updateProductMappingsOrder($idCategory, array $productOrderList);

    /**
     * @param int $idCategory
     * @param array $productPreConfig
     *
     * @return void
     */
    public function updateProductCategoryPreConfig($idCategory, array $productPreConfig);

    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return void
     */
    public function deleteCategoryRecursive($idCategory, LocaleTransfer $locale);

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $sourceNode
     * @param \Generated\Shared\Transfer\NodeTransfer $destinationNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return void
     */
    public function moveCategoryChildrenAndDeleteNode(NodeTransfer $sourceNode, NodeTransfer $destinationNode, LocaleTransfer $locale);

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return int
     */
    public function addCategory(CategoryTransfer $categoryTransfer, NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer);

    /**
     * @param int $idCategoryNode
     * @param int $fkParentCategoryNode
     * @param bool $deleteChildren
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function deleteCategory($idCategoryNode, $fkParentCategoryNode, $deleteChildren, LocaleTransfer $localeTransfer);

}
