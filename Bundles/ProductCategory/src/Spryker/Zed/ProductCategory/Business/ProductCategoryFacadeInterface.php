<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Business;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;

interface ProductCategoryFacadeInterface
{

    /**
     * @api
     *
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
     * @api
     *
     * @param string $sku
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return bool
     */
    public function hasProductCategoryMapping($sku, $categoryName, LocaleTransfer $locale);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryTransfer[]
     */
    public function getCategoriesByProductAbstract(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * @api
     *
     * @param int $idCategory
     * @param array $productIdsToAssign
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function createProductCategoryMappings($idCategory, array $productIdsToAssign);

    /**
     * @api
     *
     * @param int $idCategory
     * @param array $productIdsToUnAssign
     *
     * @return void
     */
    public function removeProductCategoryMappings($idCategory, array $productIdsToUnAssign);

    /**
     * @api
     *
     * @param int $idCategory
     * @param array $productOrderList
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function updateProductMappingsOrder($idCategory, array $productOrderList);

    /**
     * @api
     *
     * @param int $idCategory
     * @param array $productPreConfig
     *
     * @return void
     */
    public function updateProductCategoryPreConfig($idCategory, array $productPreConfig);

    /**
     * @api
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return void
     */
    public function deleteCategoryRecursive($idCategory, LocaleTransfer $locale);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\NodeTransfer $sourceNode
     * @param \Generated\Shared\Transfer\NodeTransfer $destinationNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return void
     */
    public function moveCategoryChildrenAndDeleteNode(NodeTransfer $sourceNode, NodeTransfer $destinationNode, LocaleTransfer $locale);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return int
     */
    public function addCategory(CategoryTransfer $categoryTransfer, NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer);

    /**
     * @api
     *
     * @param int $idCategoryNode
     * @param int $fkParentCategoryNode
     * @param bool $deleteChildren
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function deleteCategory($idCategoryNode, $fkParentCategoryNode, $deleteChildren, LocaleTransfer $localeTransfer);

    /**
     * @api
     *
     * @param int $idCategory
     *
     * @return void
     */
    public function removeAllProductMappingsForCategory($idCategory);

    /**
     * @api
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer[]
     */
    public function getAbstractProductsByIdCategory($idCategory, LocaleTransfer $localeTransfer);

}
