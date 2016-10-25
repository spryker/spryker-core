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
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductCategory\Business\ProductCategoryBusinessFactory getFactory()
 */
class ProductCategoryFacade extends AbstractFacade implements ProductCategoryFacadeInterface
{

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
     * @param string $sku
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return int
     */
    public function createProductCategoryMapping($sku, $categoryName, LocaleTransfer $locale)
    {
        return $this->getFactory()
            ->createProductCategoryManager()
            ->createProductCategoryMapping($sku, $categoryName, $locale);
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
     * @param string $sku
     * @param string $categoryName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return bool
     */
    public function hasProductCategoryMapping($sku, $categoryName, LocaleTransfer $locale)
    {
        return $this->getFactory()
            ->createProductCategoryManager()
            ->hasProductCategoryMapping($sku, $categoryName, $locale);
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryTransfer[]
     */
    public function getCategoriesByProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        $entities = $this->getFactory()
            ->createProductCategoryManager()
            ->getCategoriesByProductAbstract($productAbstractTransfer);

        return $this->getFactory()
            ->createProductCategoryTransferGenerator()
            ->convertProductCategoryCollection($entities);
    }

    /**
     * @api
     *
     * @param int $idCategory
     * @param array $productIdsToAssign
     *
     * @return void
     */
    public function createProductCategoryMappings($idCategory, array $productIdsToAssign)
    {
        $this->getFactory()
            ->createProductCategoryManager()
            ->createProductCategoryMappings($idCategory, $productIdsToAssign);
    }

    /**
     * @api
     *
     * @param int $idCategory
     * @param array $productIdsToUnAssign
     *
     * @return void
     */
    public function removeProductCategoryMappings($idCategory, array $productIdsToUnAssign)
    {
        $this->getFactory()
            ->createProductCategoryManager()
            ->removeProductCategoryMappings($idCategory, $productIdsToUnAssign);
    }

    /**
     * @api
     *
     * @param int $idCategory
     * @param array $productOrderList
     *
     * @return void
     */
    public function updateProductMappingsOrder($idCategory, array $productOrderList)
    {
        $this->getFactory()
            ->createProductCategoryManager()
            ->updateProductMappingsOrder($idCategory, $productOrderList);
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
     * @param int $idCategory
     * @param array $productPreConfig
     *
     * @return void
     */
    public function updateProductCategoryPreConfig($idCategory, array $productPreConfig)
    {
        $this->getFactory()
            ->createProductCategoryManager()
            ->updateProductMappingsPreConfig($idCategory, $productPreConfig);
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return void
     */
    public function deleteCategoryRecursive($idCategory, LocaleTransfer $locale)
    {
        $this->getFactory()
            ->createProductCategoryManager()
            ->deleteCategoryRecursive($idCategory, $locale);
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NodeTransfer $sourceNode
     * @param \Generated\Shared\Transfer\NodeTransfer $destinationNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return void
     */
    public function moveCategoryChildrenAndDeleteNode(NodeTransfer $sourceNode, NodeTransfer $destinationNode, LocaleTransfer $locale)
    {
        $this->getFactory()
            ->createProductCategoryManager()
            ->moveCategoryChildrenAndDeleteNode($sourceNode, $destinationNode, $locale);
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return int
     */
    public function addCategory(CategoryTransfer $categoryTransfer, NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createProductCategoryManager()
            ->addCategory($categoryTransfer, $categoryNodeTransfer, $localeTransfer);
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @api
     *
     * @param int $idCategoryNode
     * @param int $fkParentCategoryNode
     * @param bool $deleteChildren
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function deleteCategory($idCategoryNode, $fkParentCategoryNode, $deleteChildren, LocaleTransfer $localeTransfer)
    {
        $this->getFactory()
            ->createProductCategoryManager()
            ->deleteCategory($idCategoryNode, $fkParentCategoryNode, $deleteChildren, $localeTransfer);
    }

    /**
     * @api
     *
     * @param int $idCategory
     *
     * @return void
     */
    public function removeAllProductMappingsForCategory($idCategory)
    {
        $this
            ->getFactory()
            ->createProductCategoryManager()
            ->removeMappings($idCategory);
    }

    /**
     * @api
     *
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer[]
     */
    public function getAbstractProductsByIdCategory($idCategory, LocaleTransfer $localeTransfer)
    {
        return $this
            ->getFactory()
            ->createProductCategoryManager()
            ->getAbstractProductTransferCollectionByCategory($idCategory, $localeTransfer);
    }

}
