<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Business;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\ProductCategoryTransfer;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\ProductCategory\Business\Exception\MissingProductException;
use Spryker\Zed\ProductCategory\Business\Exception\MissingCategoryNodeException;
use Spryker\Zed\ProductCategory\Business\Exception\ProductCategoryMappingExistsException;

/**
 * @property ProductCategoryBusinessFactory $factory
 *
 * @method ProductCategoryBusinessFactory getFactory()
 * @method ProductCategoryManager createProductManager()
 */
class ProductCategoryFacade extends AbstractFacade
{

    /**
     * @param string $sku
     * @param string $categoryName
     * @param LocaleTransfer $locale
     *
     * @throws \Spryker\Zed\ProductCategory\Business\Exception\ProductCategoryMappingExistsException
     * @throws \Spryker\Zed\ProductCategory\Business\Exception\MissingProductException
     * @throws \Spryker\Zed\ProductCategory\Business\Exception\MissingCategoryNodeException
     * @throws \Propel\Runtime\Exception\PropelException
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
     * @param string $sku
     * @param string $categoryName
     * @param LocaleTransfer $locale
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
     * @param ProductAbstractTransfer $productAbstractTransfer
     *
     * @return ProductCategoryTransfer[]
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
     * @param int $idCategory
     * @param array $productIdsToAssign
     *
     * @throws \Propel\Runtime\Exception\PropelException
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
     * @param int $idCategory
     * @param array $productOrderList
     *
     * @throws \Propel\Runtime\Exception\PropelException
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
     * @param int $idCategory
     * @param $productPreConfig
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
     * @param int $idCategory
     * @param LocaleTransfer $locale
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
     * @param NodeTransfer $sourceNode
     * @param NodeTransfer $destinationNode
     * @param LocaleTransfer $locale
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
     * @param CategoryTransfer $categoryTransfer
     * @param NodeTransfer $categoryNodeTransfer
     * @param LocaleTransfer $localeTransfer
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
     * @param int $idCategoryNode
     * @param int $fkParentCategoryNode
     * @param bool $deleteChildren
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function deleteCategory($idCategoryNode, $fkParentCategoryNode, $deleteChildren, LocaleTransfer $localeTransfer)
    {
        $this->getFactory()
            ->createProductCategoryManager()
            ->deleteCategory($idCategoryNode, $fkParentCategoryNode, $deleteChildren, $localeTransfer);
    }

}
